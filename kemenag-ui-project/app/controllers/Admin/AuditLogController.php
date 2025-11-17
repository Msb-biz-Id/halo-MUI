<?php

namespace App\Controllers\Admin;

use Core\Controller;
use App\Models\AuditLog;
use App\Services\ExcelService;

/**
 * Admin Audit Log Controller
 * View system audit logs
 */
class AuditLogController extends Controller
{
    private $auditModel;
    private $excelService;
    
    public function __construct()
    {
        parent::__construct();
        $this->requireAuth();
        $this->requirePermission('system_settings');
        
        $this->auditModel = $this->model('AuditLog');
        $this->excelService = new ExcelService();
    }
    
    /**
     * List all audit logs
     */
    public function index()
    {
        $action = $_GET['action'] ?? '';
        $table = $_GET['table'] ?? '';
        $userId = $_GET['user_id'] ?? '';
        $dateFrom = $_GET['date_from'] ?? '';
        $dateTo = $_GET['date_to'] ?? '';
        
        $page = $_GET['page'] ?? 1;
        $perPage = 50;
        
        // Build query
        $conditions = [];
        $params = [];
        
        if (!empty($action)) {
            $conditions[] = "al.action = :action";
            $params[':action'] = $action;
        }
        
        if (!empty($table)) {
            $conditions[] = "al.table_name = :table";
            $params[':table'] = $table;
        }
        
        if (!empty($userId)) {
            $conditions[] = "al.user_id = :user_id";
            $params[':user_id'] = $userId;
        }
        
        if (!empty($dateFrom)) {
            $conditions[] = "DATE(al.created_at) >= :date_from";
            $params[':date_from'] = $dateFrom;
        }
        
        if (!empty($dateTo)) {
            $conditions[] = "DATE(al.created_at) <= :date_to";
            $params[':date_to'] = $dateTo;
        }
        
        $where = !empty($conditions) ? 'WHERE ' . implode(' AND ', $conditions) : '';
        
        // Get logs
        $logs = $this->auditModel->query(
            "SELECT al.*, u.full_name as user_name, u.username
             FROM audit_logs al
             LEFT JOIN users u ON al.user_id = u.id
             {$where}
             ORDER BY al.created_at DESC
             LIMIT " . (($page - 1) * $perPage) . ", {$perPage}",
            $params
        )->fetchAll();
        
        // Get total for pagination
        $total = $this->auditModel->query(
            "SELECT COUNT(*) as total FROM audit_logs al {$where}",
            $params
        )->fetch()['total'];
        
        // Get distinct actions and tables for filters
        $actions = $this->auditModel->query(
            "SELECT DISTINCT action FROM audit_logs ORDER BY action"
        )->fetchAll();
        
        $tables = $this->auditModel->query(
            "SELECT DISTINCT table_name FROM audit_logs ORDER BY table_name"
        )->fetchAll();
        
        // Get statistics
        $stats = $this->auditModel->query(
            "SELECT 
                COUNT(*) as total_logs,
                COUNT(CASE WHEN action = 'create' THEN 1 END) as total_creates,
                COUNT(CASE WHEN action = 'update' THEN 1 END) as total_updates,
                COUNT(CASE WHEN action = 'delete' THEN 1 END) as total_deletes,
                COUNT(DISTINCT user_id) as total_users,
                COUNT(CASE WHEN DATE(created_at) = CURDATE() THEN 1 END) as today_logs
             FROM audit_logs"
        )->fetch();
        
        $data = [
            'page_title' => 'Audit Log',
            'logs' => $logs,
            'actions' => $actions,
            'tables' => $tables,
            'stats' => $stats,
            'pagination' => [
                'current_page' => $page,
                'total_pages' => ceil($total / $perPage),
                'total_items' => $total,
                'per_page' => $perPage
            ],
            'filters' => [
                'action' => $action,
                'table' => $table,
                'user_id' => $userId,
                'date_from' => $dateFrom,
                'date_to' => $dateTo
            ]
        ];
        
        $this->view('admin/audit/index', $data);
    }
    
    /**
     * View single audit log detail
     */
    public function view($id)
    {
        $log = $this->auditModel->query(
            "SELECT al.*, u.full_name as user_name, u.username, u.email
             FROM audit_logs al
             LEFT JOIN users u ON al.user_id = u.id
             WHERE al.id = :id",
            [':id' => $id]
        )->fetch();
        
        if (!$log) {
            $this->setFlash('error', 'Log tidak ditemukan');
            $this->redirect('/admin/audit');
        }
        
        // Decode old_value and new_value JSON
        if (!empty($log['old_value'])) {
            $log['old_value_decoded'] = json_decode($log['old_value'], true);
        }
        
        if (!empty($log['new_value'])) {
            $log['new_value_decoded'] = json_decode($log['new_value'], true);
        }
        
        $data = [
            'page_title' => 'Detail Audit Log',
            'log' => $log
        ];
        
        $this->view('admin/audit/view', $data);
    }
    
    /**
     * Export audit logs to Excel
     */
    public function export()
    {
        $action = $_GET['action'] ?? '';
        $table = $_GET['table'] ?? '';
        $dateFrom = $_GET['date_from'] ?? '';
        $dateTo = $_GET['date_to'] ?? '';
        
        // Build query
        $conditions = [];
        $params = [];
        
        if (!empty($action)) {
            $conditions[] = "al.action = :action";
            $params[':action'] = $action;
        }
        
        if (!empty($table)) {
            $conditions[] = "al.table_name = :table";
            $params[':table'] = $table;
        }
        
        if (!empty($dateFrom)) {
            $conditions[] = "DATE(al.created_at) >= :date_from";
            $params[':date_from'] = $dateFrom;
        }
        
        if (!empty($dateTo)) {
            $conditions[] = "DATE(al.created_at) <= :date_to";
            $params[':date_to'] = $dateTo;
        }
        
        $where = !empty($conditions) ? 'WHERE ' . implode(' AND ', $conditions) : '';
        
        // Get data
        $logs = $this->auditModel->query(
            "SELECT al.*, u.full_name as user_name, u.username
             FROM audit_logs al
             LEFT JOIN users u ON al.user_id = u.id
             {$where}
             ORDER BY al.created_at DESC",
            $params
        )->fetchAll();
        
        // Generate Excel
        $filename = 'audit_logs_' . date('Ymd_His') . '.xlsx';
        $filepath = $this->excelService->exportAuditLogs($logs, $filename);
        
        // Download file
        if (file_exists($filepath)) {
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header('Content-Length: ' . filesize($filepath));
            readfile($filepath);
            
            // Delete file after download
            unlink($filepath);
            
            log_audit($_SESSION['user_id'], 'export', 'audit_logs', 0);
            exit();
        } else {
            $this->setFlash('error', 'Gagal generate export file');
            $this->redirect('/admin/audit');
        }
    }
    
    /**
     * Get user activity
     */
    public function userActivity($userId)
    {
        $page = $_GET['page'] ?? 1;
        $perPage = 20;
        
        $logs = $this->auditModel->query(
            "SELECT al.*, u.full_name as user_name
             FROM audit_logs al
             LEFT JOIN users u ON al.user_id = u.id
             WHERE al.user_id = :user_id
             ORDER BY al.created_at DESC
             LIMIT " . (($page - 1) * $perPage) . ", {$perPage}",
            [':user_id' => $userId]
        )->fetchAll();
        
        $total = $this->auditModel->query(
            "SELECT COUNT(*) as total FROM audit_logs WHERE user_id = :user_id",
            [':user_id' => $userId]
        )->fetch()['total'];
        
        $userModel = $this->model('User');
        $user = $userModel->findById($userId);
        
        $data = [
            'page_title' => 'Aktivitas Pengguna',
            'user' => $user,
            'logs' => $logs,
            'pagination' => [
                'current_page' => $page,
                'total_pages' => ceil($total / $perPage),
                'total_items' => $total,
                'per_page' => $perPage
            ]
        ];
        
        $this->view('admin/audit/user_activity', $data);
    }
    
    /**
     * Dashboard statistics
     */
    public function dashboard()
    {
        // Get activity trends (last 30 days)
        $trends = $this->auditModel->query(
            "SELECT 
                DATE(created_at) as date,
                COUNT(*) as count,
                COUNT(CASE WHEN action = 'create' THEN 1 END) as creates,
                COUNT(CASE WHEN action = 'update' THEN 1 END) as updates,
                COUNT(CASE WHEN action = 'delete' THEN 1 END) as deletes
             FROM audit_logs
             WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
             GROUP BY DATE(created_at)
             ORDER BY date DESC"
        )->fetchAll();
        
        // Get top users
        $topUsers = $this->auditModel->query(
            "SELECT u.full_name, u.username, COUNT(al.id) as activity_count
             FROM audit_logs al
             LEFT JOIN users u ON al.user_id = u.id
             WHERE al.created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
             GROUP BY al.user_id
             ORDER BY activity_count DESC
             LIMIT 10"
        )->fetchAll();
        
        // Get top actions
        $topActions = $this->auditModel->query(
            "SELECT action, table_name, COUNT(*) as count
             FROM audit_logs
             WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
             GROUP BY action, table_name
             ORDER BY count DESC
             LIMIT 10"
        )->fetchAll();
        
        $data = [
            'page_title' => 'Audit Dashboard',
            'trends' => $trends,
            'top_users' => $topUsers,
            'top_actions' => $topActions
        ];
        
        $this->view('admin/audit/dashboard', $data);
    }
}
