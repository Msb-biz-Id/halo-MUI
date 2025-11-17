<?php

namespace App\Controllers\Admin;

use Core\Controller;
use App\Services\BackupService;

/**
 * Backup Schedule Controller
 * Manage automated backup schedules
 */
class BackupScheduleController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->requireAdmin();
    }
    
    /**
     * List backup schedules
     */
    public function index()
    {
        $sql = "SELECT * FROM backup_schedules ORDER BY created_at DESC";
        $schedules = $this->db->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
        
        $this->view('admin/backup/schedule', [
            'title' => 'Backup Schedules',
            'schedules' => $schedules
        ]);
    }
    
    /**
     * Create schedule form
     */
    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->store();
            return;
        }
        
        $this->view('admin/backup/schedule_create', [
            'title' => 'Create Backup Schedule'
        ]);
    }
    
    /**
     * Store new schedule
     */
    private function store()
    {
        $name = $_POST['name'] ?? '';
        $frequency = $_POST['frequency'] ?? 'daily';
        $time = $_POST['time'] ?? '02:00:00';
        $dayOfWeek = $_POST['day_of_week'] ?? null;
        $dayOfMonth = $_POST['day_of_month'] ?? null;
        
        $sql = "INSERT INTO backup_schedules (
                    name, frequency, time, day_of_week, day_of_month,
                    backup_database, backup_files, backup_uploads, backup_logs,
                    compression, retention_days, cloud_upload, email_notification,
                    status, created_at, next_run
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'active', NOW(), ?)";
        
        $nextRun = $this->calculateNextRun($frequency, $time, $dayOfWeek, $dayOfMonth);
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $name,
            $frequency,
            $time,
            $dayOfWeek,
            $dayOfMonth,
            isset($_POST['backup_database']) ? 1 : 0,
            isset($_POST['backup_files']) ? 1 : 0,
            isset($_POST['backup_uploads']) ? 1 : 0,
            isset($_POST['backup_logs']) ? 1 : 0,
            isset($_POST['compression']) ? 1 : 0,
            $_POST['retention_days'] ?? 30,
            isset($_POST['cloud_upload']) ? 1 : 0,
            isset($_POST['email_notification']) ? 1 : 0,
            $nextRun
        ]);
        
        $_SESSION['success'] = 'Backup schedule created successfully';
        redirect('admin/backup/schedule');
    }
    
    /**
     * Edit schedule
     */
    public function edit($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->update($id);
            return;
        }
        
        $sql = "SELECT * FROM backup_schedules WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $schedule = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        if (!$schedule) {
            $_SESSION['error'] = 'Schedule not found';
            redirect('admin/backup/schedule');
            return;
        }
        
        $this->view('admin/backup/schedule_edit', [
            'title' => 'Edit Backup Schedule',
            'schedule' => $schedule
        ]);
    }
    
    /**
     * Update schedule
     */
    private function update($id)
    {
        $name = $_POST['name'] ?? '';
        $frequency = $_POST['frequency'] ?? 'daily';
        $time = $_POST['time'] ?? '02:00:00';
        $dayOfWeek = $_POST['day_of_week'] ?? null;
        $dayOfMonth = $_POST['day_of_month'] ?? null;
        
        $sql = "UPDATE backup_schedules SET 
                    name = ?, frequency = ?, time = ?, day_of_week = ?, day_of_month = ?,
                    backup_database = ?, backup_files = ?, backup_uploads = ?, backup_logs = ?,
                    compression = ?, retention_days = ?, cloud_upload = ?, email_notification = ?,
                    updated_at = NOW(), next_run = ?
                WHERE id = ?";
        
        $nextRun = $this->calculateNextRun($frequency, $time, $dayOfWeek, $dayOfMonth);
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $name,
            $frequency,
            $time,
            $dayOfWeek,
            $dayOfMonth,
            isset($_POST['backup_database']) ? 1 : 0,
            isset($_POST['backup_files']) ? 1 : 0,
            isset($_POST['backup_uploads']) ? 1 : 0,
            isset($_POST['backup_logs']) ? 1 : 0,
            isset($_POST['compression']) ? 1 : 0,
            $_POST['retention_days'] ?? 30,
            isset($_POST['cloud_upload']) ? 1 : 0,
            isset($_POST['email_notification']) ? 1 : 0,
            $nextRun,
            $id
        ]);
        
        $_SESSION['success'] = 'Backup schedule updated successfully';
        redirect('admin/backup/schedule');
    }
    
    /**
     * Delete schedule
     */
    public function delete($id)
    {
        $sql = "DELETE FROM backup_schedules WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        
        $_SESSION['success'] = 'Backup schedule deleted successfully';
        redirect('admin/backup/schedule');
    }
    
    /**
     * Toggle schedule status
     */
    public function toggle($id)
    {
        $sql = "UPDATE backup_schedules 
                SET status = CASE WHEN status = 'active' THEN 'inactive' ELSE 'active' END 
                WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        
        $this->json(['success' => true]);
    }
    
    /**
     * Run schedule manually
     */
    public function run($id)
    {
        $sql = "SELECT * FROM backup_schedules WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $schedule = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        if (!$schedule) {
            $this->json(['error' => 'Schedule not found'], 404);
            return;
        }
        
        try {
            $backupService = new BackupService();
            $result = $backupService->performFullBackup();
            
            // Update last run
            $updateSql = "UPDATE backup_schedules SET last_run = NOW(), 
                          next_run = ? WHERE id = ?";
            $nextRun = $this->calculateNextRun(
                $schedule['frequency'],
                $schedule['time'],
                $schedule['day_of_week'],
                $schedule['day_of_month']
            );
            $stmt = $this->db->prepare($updateSql);
            $stmt->execute([$nextRun, $id]);
            
            $_SESSION['success'] = 'Backup completed successfully';
            redirect('admin/backup/schedule');
            
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Backup failed: ' . $e->getMessage();
            redirect('admin/backup/schedule');
        }
    }
    
    /**
     * Calculate next run time
     */
    private function calculateNextRun(
        string $frequency, 
        string $time, 
        ?int $dayOfWeek = null, 
        ?int $dayOfMonth = null
    ): string {
        $now = new \DateTime();
        $runTime = \DateTime::createFromFormat('H:i:s', $time);
        
        switch ($frequency) {
            case 'daily':
                $next = new \DateTime('tomorrow ' . $time);
                break;
                
            case 'weekly':
                $dayOfWeek = $dayOfWeek ?? 0; // Sunday
                $next = new \DateTime('next ' . $this->getDayName($dayOfWeek) . ' ' . $time);
                break;
                
            case 'monthly':
                $dayOfMonth = $dayOfMonth ?? 1;
                $next = new \DateTime('first day of next month ' . $time);
                $next->setDate($next->format('Y'), $next->format('m'), $dayOfMonth);
                break;
                
            default:
                $next = new \DateTime('tomorrow ' . $time);
        }
        
        return $next->format('Y-m-d H:i:s');
    }
    
    /**
     * Get day name from number
     */
    private function getDayName(int $day): string
    {
        $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        return $days[$day] ?? 'Sunday';
    }
}
