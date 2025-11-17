<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\CertificateApplication;
use App\Models\ForumTopic;
use App\Models\InternalMessage;
use App\Models\Notification;
use App\Models\AuditLog;

/**
 * Dashboard Controller
 * Handles USER Dashboard (not admin)
 * User dashboard panel with multiple features like admin panel
 */
class DashboardController extends Controller
{
    private $certModel;
    private $forumModel;
    private $messageModel;
    private $notificationModel;
    private $auditModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->requireAuth(); // User must be logged in
        
        $this->certModel = $this->model('CertificateApplication');
        $this->forumModel = $this->model('ForumTopic');
        $this->messageModel = $this->model('InternalMessage');
        $this->notificationModel = $this->model('Notification');
        $this->auditModel = $this->model('AuditLog');
    }
    
    /**
     * User Dashboard Index
     * Main dashboard with statistics and quick actions
     */
    public function index()
    {
        $userId = $_SESSION['user_id'];
        
        // Get user statistics
        $certStats = $this->getCertificateStats($userId);
        $forumStats = $this->getForumStats($userId);
        $messageStats = $this->getMessageStats($userId);
        
        // Get recent activity
        $recentActivity = $this->getRecentActivity($userId);
        
        // Get unread notifications
        $notifications = $this->notificationModel->getUnread($userId);
        $notificationCount = count($notifications);
        
        // Get recent certificates
        $recentCerts = $this->certModel->query(
            "SELECT * FROM certificate_applications 
             WHERE user_id = :user_id 
             ORDER BY submitted_at DESC 
             LIMIT 5",
            [':user_id' => $userId]
        )->fetchAll();
        
        // Get active forum topics by user
        $myTopics = $this->forumModel->query(
            "SELECT ft.*, fc.name as category_name,
                    COUNT(fp.id) as post_count
             FROM forum_topics ft
             LEFT JOIN forum_categories fc ON ft.category_id = fc.id
             LEFT JOIN forum_posts fp ON ft.id = fp.topic_id
             WHERE ft.user_id = :user_id
             GROUP BY ft.id
             ORDER BY ft.updated_at DESC
             LIMIT 5",
            [':user_id' => $userId]
        )->fetchAll();
        
        $data = [
            'page_title' => 'Dashboard',
            'cert_stats' => $certStats,
            'forum_stats' => $forumStats,
            'message_stats' => $messageStats,
            'recent_activity' => $recentActivity,
            'notifications' => $notifications,
            'notification_count' => $notificationCount,
            'recent_certs' => $recentCerts,
            'my_topics' => $myTopics,
            'user' => user()
        ];
        
        $this->view('user/dashboard/index', $data);
    }
    
    /**
     * Get certificate statistics for user
     */
    private function getCertificateStats($userId)
    {
        $sql = "SELECT 
                    COUNT(*) as total,
                    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
                    SUM(CASE WHEN status = 'in_review' THEN 1 ELSE 0 END) as in_review,
                    SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved,
                    SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as rejected,
                    SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed
                FROM certificate_applications
                WHERE user_id = :user_id";
        
        return $this->certModel->query($sql, [':user_id' => $userId])->fetch();
    }
    
    /**
     * Get forum statistics for user
     */
    private function getForumStats($userId)
    {
        $sql = "SELECT 
                    COUNT(DISTINCT ft.id) as topics_created,
                    COUNT(fp.id) as posts_created,
                    SUM(ft.views) as total_views
                FROM forum_topics ft
                LEFT JOIN forum_posts fp ON ft.user_id = :user_id OR fp.user_id = :user_id
                WHERE ft.user_id = :user_id";
        
        return $this->forumModel->query($sql, [':user_id' => $userId])->fetch();
    }
    
    /**
     * Get message statistics for user
     */
    private function getMessageStats($userId)
    {
        $unread = $this->messageModel->countUnread($userId);
        
        $sql = "SELECT 
                    COUNT(*) as total_received
                FROM internal_messages
                WHERE receiver_id = :user_id";
        
        $received = $this->messageModel->query($sql, [':user_id' => $userId])->fetch();
        
        return [
            'unread' => $unread,
            'total_received' => $received['total_received'] ?? 0
        ];
    }
    
    /**
     * Get recent activity for user
     */
    private function getRecentActivity($userId)
    {
        $activities = $this->auditModel->getByUser($userId);
        
        // Limit to last 10 activities
        return array_slice($activities, 0, 10);
    }
    
    /**
     * My Certificates Page
     * List all certificates with filtering
     */
    public function myCertificates()
    {
        $userId = $_SESSION['user_id'];
        $status = $_GET['status'] ?? 'all';
        
        // Build query based on filter
        $sql = "SELECT * FROM certificate_applications WHERE user_id = :user_id";
        $params = [':user_id' => $userId];
        
        if ($status !== 'all') {
            $sql .= " AND status = :status";
            $params[':status'] = $status;
        }
        
        $sql .= " ORDER BY submitted_at DESC";
        
        $certificates = $this->certModel->query($sql, $params)->fetchAll();
        
        $data = [
            'page_title' => 'Pengajuan Sertifikat Saya',
            'certificates' => $certificates,
            'current_status' => $status
        ];
        
        $this->view('user/certificates/index', $data);
    }
    
    /**
     * My Forum Topics
     */
    public function myForumTopics()
    {
        $userId = $_SESSION['user_id'];
        
        $topics = $this->forumModel->query(
            "SELECT ft.*, fc.name as category_name,
                    COUNT(fp.id) as post_count,
                    ft.views as view_count
             FROM forum_topics ft
             LEFT JOIN forum_categories fc ON ft.category_id = fc.id
             LEFT JOIN forum_posts fp ON ft.id = fp.topic_id
             WHERE ft.user_id = :user_id
             GROUP BY ft.id
             ORDER BY ft.updated_at DESC",
            [':user_id' => $userId]
        )->fetchAll();
        
        $data = [
            'page_title' => 'Topik Forum Saya',
            'topics' => $topics
        ];
        
        $this->view('user/forum/my_topics', $data);
    }
    
    /**
     * My Messages
     */
    public function myMessages()
    {
        $userId = $_SESSION['user_id'];
        $type = $_GET['type'] ?? 'inbox';
        
        if ($type === 'sent') {
            $messages = $this->messageModel->getSent($userId);
        } else {
            $messages = $this->messageModel->getInbox($userId);
        }
        
        $data = [
            'page_title' => 'Pesan Saya',
            'messages' => $messages,
            'type' => $type,
            'unread_count' => $this->messageModel->countUnread($userId)
        ];
        
        $this->view('user/messages/index', $data);
    }
    
    /**
     * My Notifications
     */
    public function myNotifications()
    {
        $userId = $_SESSION['user_id'];
        $notifications = $this->notificationModel->getByUser($userId);
        
        $data = [
            'page_title' => 'Notifikasi Saya',
            'notifications' => $notifications
        ];
        
        $this->view('user/notifications/index', $data);
    }
    
    /**
     * Activity History
     */
    public function activityHistory()
    {
        $userId = $_SESSION['user_id'];
        $activities = $this->auditModel->getByUser($userId);
        
        $data = [
            'page_title' => 'Riwayat Aktivitas',
            'activities' => $activities
        ];
        
        $this->view('user/activity/history', $data);
    }
    
    /**
     * Quick Actions Handler
     */
    public function quickAction()
    {
        $action = $_GET['action'] ?? '';
        
        switch ($action) {
            case 'new_certificate':
                $this->redirect('/certificate/apply');
                break;
            case 'new_forum_topic':
                $this->redirect('/forum/create-topic');
                break;
            case 'check_status':
                $this->redirect('/dashboard/my-certificates');
                break;
            default:
                $this->redirect('/dashboard');
        }
    }
}
