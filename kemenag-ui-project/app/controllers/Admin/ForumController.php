<?php

namespace App\Controllers\Admin;

use Core\Controller;
use App\Models\ForumTopic;
use App\Models\ForumPost;
use App\Models\User;
use App\Models\Notification;

/**
 * Admin Forum Controller
 * Manage forum topics approval and moderation
 */
class ForumController extends Controller
{
    private $topicModel;
    private $postModel;
    private $userModel;
    private $notificationModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->requireAuth();
        $this->requirePermission('forum_moderation');
        
        $this->topicModel = $this->model('ForumTopic');
        $this->postModel = $this->model('ForumPost');
        $this->userModel = $this->model('User');
        $this->notificationModel = $this->model('Notification');
    }
    
    /**
     * List pending topics for approval
     */
    public function index()
    {
        $status = $_GET['status'] ?? 'pending';
        $search = $_GET['search'] ?? '';
        
        $sql = "SELECT ft.*, u.username, u.full_name, u.email,
                fc.name as category_name
                FROM forum_topics ft
                LEFT JOIN users u ON ft.user_id = u.id
                LEFT JOIN forum_categories fc ON ft.category_id = fc.id
                WHERE 1=1";
        
        $params = [];
        
        if ($status === 'pending') {
            $sql .= " AND ft.is_approved = 0";
        } elseif ($status === 'approved') {
            $sql .= " AND ft.is_approved = 1";
        }
        
        if (!empty($search)) {
            $sql .= " AND (ft.title LIKE :search OR ft.content LIKE :search OR u.username LIKE :search)";
            $params[':search'] = "%{$search}%";
        }
        
        $sql .= " ORDER BY ft.created_at DESC LIMIT 100";
        
        $topics = $this->topicModel->query($sql, $params)->fetchAll();
        
        // Get statistics
        $stats = $this->topicModel->query(
            "SELECT 
                COUNT(*) as total_topics,
                COUNT(CASE WHEN is_approved = 0 THEN 1 END) as pending_count,
                COUNT(CASE WHEN is_approved = 1 THEN 1 END) as approved_count,
                COUNT(CASE WHEN DATE(created_at) = CURDATE() THEN 1 END) as today_count
             FROM forum_topics"
        )->fetch();
        
        $data = [
            'page_title' => 'Moderasi Forum',
            'topics' => $topics,
            'stats' => $stats,
            'current_status' => $status,
            'search' => $search
        ];
        
        $this->view('admin/forum/index', $data);
    }
    
    /**
     * View topic detail for approval
     */
    public function view($id)
    {
        $topic = $this->topicModel->query(
            "SELECT ft.*, u.username, u.full_name, u.email,
                    fc.name as category_name,
                    approver.full_name as approver_name
             FROM forum_topics ft
             LEFT JOIN users u ON ft.user_id = u.id
             LEFT JOIN forum_categories fc ON ft.category_id = fc.id
             LEFT JOIN users approver ON ft.approved_by = approver.id
             WHERE ft.id = :id",
            [':id' => $id]
        )->fetch();
        
        if (!$topic) {
            $this->setFlash('error', 'Topik tidak ditemukan');
            $this->redirect('/admin/forum');
        }
        
        // Get posts in this topic
        $posts = $this->postModel->query(
            "SELECT fp.*, u.username, u.full_name
             FROM forum_posts fp
             LEFT JOIN users u ON fp.user_id = u.id
             WHERE fp.topic_id = :topic_id
             ORDER BY fp.created_at ASC",
            [':topic_id' => $id]
        )->fetchAll();
        
        // Check for blacklisted words
        $blacklistModel = $this->model('WordBlacklist');
        $blacklistCheck = $blacklistModel->checkContent($topic['title'] . ' ' . $topic['content']);
        
        $data = [
            'page_title' => 'Detail Topik - ' . $topic['title'],
            'topic' => $topic,
            'posts' => $posts,
            'blacklist_check' => $blacklistCheck,
            'csrf_token' => $this->generateCsrf()
        ];
        
        $this->view('admin/forum/view', $data);
    }
    
    /**
     * Approve topic
     */
    public function approve($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$this->verifyCsrf()) {
            $this->setFlash('error', 'Invalid request');
            $this->redirect('/admin/forum');
        }
        
        $topic = $this->topicModel->findById($id);
        
        if (!$topic) {
            $this->setFlash('error', 'Topik tidak ditemukan');
            $this->redirect('/admin/forum');
        }
        
        if ($topic['is_approved']) {
            $this->setFlash('info', 'Topik sudah disetujui sebelumnya');
            $this->redirect('/admin/forum/view/' . $id);
        }
        
        // Approve topic
        $updateData = [
            'is_approved' => 1,
            'approved_by' => $_SESSION['user_id'],
            'approved_at' => date('Y-m-d H:i:s'),
            'rejection_reason' => null
        ];
        
        if ($this->topicModel->update($id, $updateData)) {
            // Notify topic creator
            $this->notificationModel->createNotification([
                'user_id' => $topic['user_id'],
                'type' => 'forum_approved',
                'title' => 'Topik Forum Disetujui',
                'message' => "Topik Anda \"{$topic['title']}\" telah disetujui dan dipublikasikan!",
                'link' => '/forum/topic/' . $id
            ]);
            
            log_audit($_SESSION['user_id'], 'approve_forum_topic', 'forum_topics', $id);
            
            $this->setFlash('success', 'Topik berhasil disetujui');
        } else {
            $this->setFlash('error', 'Gagal menyetujui topik');
        }
        
        $this->redirect('/admin/forum/view/' . $id);
    }
    
    /**
     * Reject topic
     */
    public function reject($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$this->verifyCsrf()) {
            $this->setFlash('error', 'Invalid request');
            $this->redirect('/admin/forum');
        }
        
        $reason = $_POST['reason'] ?? '';
        
        if (empty($reason)) {
            $this->setFlash('error', 'Alasan penolakan wajib diisi');
            $this->redirect('/admin/forum/view/' . $id);
        }
        
        $topic = $this->topicModel->findById($id);
        
        if (!$topic) {
            $this->setFlash('error', 'Topik tidak ditemukan');
            $this->redirect('/admin/forum');
        }
        
        // Update with rejection reason
        $updateData = [
            'is_approved' => 0,
            'approved_by' => $_SESSION['user_id'],
            'approved_at' => date('Y-m-d H:i:s'),
            'rejection_reason' => $reason
        ];
        
        if ($this->topicModel->update($id, $updateData)) {
            // Notify topic creator
            $this->notificationModel->createNotification([
                'user_id' => $topic['user_id'],
                'type' => 'forum_rejected',
                'title' => 'Topik Forum Ditolak',
                'message' => "Topik Anda \"{$topic['title']}\" ditolak. Alasan: {$reason}",
                'link' => '/dashboard'
            ]);
            
            log_audit($_SESSION['user_id'], 'reject_forum_topic', 'forum_topics', $id);
            
            $this->setFlash('success', 'Topik berhasil ditolak');
        } else {
            $this->setFlash('error', 'Gagal menolak topik');
        }
        
        $this->redirect('/admin/forum');
    }
    
    /**
     * Delete topic
     */
    public function delete($id)
    {
        $topic = $this->topicModel->findById($id);
        
        if (!$topic) {
            $this->setFlash('error', 'Topik tidak ditemukan');
            $this->redirect('/admin/forum');
        }
        
        if ($this->topicModel->delete($id)) {
            log_audit($_SESSION['user_id'], 'delete', 'forum_topics', $id);
            $this->setFlash('success', 'Topik berhasil dihapus');
        } else {
            $this->setFlash('error', 'Gagal menghapus topik');
        }
        
        $this->redirect('/admin/forum');
    }
    
    /**
     * Lock/Unlock topic
     */
    public function toggleLock($id)
    {
        $topic = $this->topicModel->findById($id);
        
        if (!$topic) {
            $this->json(['success' => false, 'message' => 'Not found'], 404);
        }
        
        $newStatus = $topic['is_locked'] ? 0 : 1;
        
        if ($this->topicModel->update($id, ['is_locked' => $newStatus])) {
            log_audit($_SESSION['user_id'], 'toggle_lock_topic', 'forum_topics', $id);
            $this->json([
                'success' => true,
                'is_locked' => $newStatus,
                'message' => $newStatus ? 'Topik dikunci' : 'Topik dibuka'
            ]);
        } else {
            $this->json(['success' => false, 'message' => 'Failed'], 500);
        }
    }
    
    /**
     * Pin/Unpin topic
     */
    public function togglePin($id)
    {
        $topic = $this->topicModel->findById($id);
        
        if (!$topic) {
            $this->json(['success' => false, 'message' => 'Not found'], 404);
        }
        
        $newStatus = $topic['is_pinned'] ? 0 : 1;
        
        if ($this->topicModel->update($id, ['is_pinned' => $newStatus])) {
            log_audit($_SESSION['user_id'], 'toggle_pin_topic', 'forum_topics', $id);
            $this->json([
                'success' => true,
                'is_pinned' => $newStatus,
                'message' => $newStatus ? 'Topik dipinned' : 'Topik unpinned'
            ]);
        } else {
            $this->json(['success' => false, 'message' => 'Failed'], 500);
        }
    }
    
    /**
     * Delete post
     */
    public function deletePost($postId)
    {
        $post = $this->postModel->findById($postId);
        
        if (!$post) {
            $this->setFlash('error', 'Post tidak ditemukan');
            $this->redirect('/admin/forum');
        }
        
        $topicId = $post['topic_id'];
        
        if ($this->postModel->delete($postId)) {
            // Update reply count
            $this->topicModel->query(
                "UPDATE forum_topics SET reply_count = reply_count - 1 WHERE id = :id",
                [':id' => $topicId]
            )->execute();
            
            log_audit($_SESSION['user_id'], 'delete', 'forum_posts', $postId);
            $this->setFlash('success', 'Post berhasil dihapus');
        } else {
            $this->setFlash('error', 'Gagal menghapus post');
        }
        
        $this->redirect('/admin/forum/view/' . $topicId);
    }
}
