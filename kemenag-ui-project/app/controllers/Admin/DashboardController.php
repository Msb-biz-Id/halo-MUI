<?php

namespace App\Controllers\Admin;

use Core\Controller;

class DashboardController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->requireRole('superadmin'); // Or check for admin roles
    }
    
    public function index()
    {
        $userId = $_SESSION['user_id'];
        
        // Get statistics
        $userModel = $this->model('User');
        $certModel = $this->model('CertificateApplication');
        $forumModel = $this->model('ForumTopic');
        $qaModel = $this->model('QuestionAnswer');
        
        $stats = [
            'total_users' => $userModel->count(),
            'total_certificates' => $certModel->count(),
            'pending_certificates' => $certModel->countByStatus('pending'),
            'total_qa' => $qaModel->count(),
            'total_forum_topics' => $forumModel->count()
        ];
        
        // Get recent activities
        $auditModel = $this->model('AuditLog');
        $recentActivities = $auditModel->getRecent(20);
        
        // Get certificate statistics
        $certStats = $certModel->getStatistics();
        
        $this->view('admin/dashboard/index', [
            'page_title' => 'Admin Dashboard',
            'stats' => $stats,
            'cert_stats' => $certStats,
            'recent_activities' => $recentActivities
        ]);
    }
}
