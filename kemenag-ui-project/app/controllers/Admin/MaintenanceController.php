<?php

namespace App\Controllers\Admin;

use Core\Controller;

/**
 * Maintenance Mode Controller
 */
class MaintenanceController extends Controller
{
    private $maintenanceFile;
    
    public function __construct()
    {
        parent::__construct();
        $this->requireAdmin();
        $this->maintenanceFile = __DIR__ . '/../../../storage/maintenance.json';
    }
    
    /**
     * Maintenance settings page
     */
    public function index()
    {
        $settings = $this->getMaintenanceSettings();
        
        $this->view('admin/maintenance/index', [
            'title' => 'Maintenance Mode',
            'settings' => $settings
        ]);
    }
    
    /**
     * Enable maintenance mode
     */
    public function enable()
    {
        $settings = [
            'enabled' => true,
            'title' => $_POST['title'] ?? 'Site Under Maintenance',
            'message' => $_POST['message'] ?? 'We are currently performing scheduled maintenance. We will be back soon!',
            'retry_after' => $_POST['retry_after'] ?? 3600,
            'allowed_ips' => array_filter(array_map('trim', explode("\n", $_POST['allowed_ips'] ?? ''))),
            'enabled_at' => date('Y-m-d H:i:s'),
            'enabled_by' => $_SESSION['user_id'] ?? 0
        ];
        
        file_put_contents($this->maintenanceFile, json_encode($settings, JSON_PRETTY_PRINT));
        
        $_SESSION['success'] = 'Maintenance mode enabled';
        redirect('admin/maintenance');
    }
    
    /**
     * Disable maintenance mode
     */
    public function disable()
    {
        if (file_exists($this->maintenanceFile)) {
            unlink($this->maintenanceFile);
        }
        
        $_SESSION['success'] = 'Maintenance mode disabled';
        redirect('admin/maintenance');
    }
    
    /**
     * Get maintenance settings
     */
    private function getMaintenanceSettings(): array
    {
        if (!file_exists($this->maintenanceFile)) {
            return ['enabled' => false];
        }
        
        return json_decode(file_get_contents($this->maintenanceFile), true);
    }
}
