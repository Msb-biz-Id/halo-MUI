<?php

namespace App\Controllers\Admin;

use Core\Controller;
use App\Services\BackupService;

class BackupController extends Controller
{
    private $backupService;
    
    public function __construct()
    {
        parent::__construct();
        $this->requireAdmin();
        $this->backupService = new BackupService();
    }
    
    public function index()
    {
        $backups = $this->backupService->listBackups();
        $stats = $this->backupService->getBackupStats();
        
        $this->view('admin/backup/index', [
            'title' => 'Backup Management',
            'backups' => $backups,
            'stats' => $stats
        ]);
    }
    
    public function create()
    {
        try {
            $result = $this->backupService->performFullBackup();
            $_SESSION['success'] = 'Backup created successfully';
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Backup failed: ' . $e->getMessage();
        }
        
        redirect('/admin/backup');
    }
    
    public function restore($id)
    {
        try {
            $this->backupService->restoreDatabase($id);
            $_SESSION['success'] = 'Backup restored successfully';
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Restore failed: ' . $e->getMessage();
        }
        
        redirect('/admin/backup');
    }
}
