<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\ForumCategory;
use App\Models\ForumTopic;
use App\Models\ForumPost;
use App\Models\Notification;
use App\Models\WordBlacklist;
use App\Models\Setting;

/**
 * Forum Controller
 * Handles forum discussion system with moderation and blacklist checking
 */
class ForumController extends Controller
{
    private $categoryModel;
    private $topicModel;
    private $postModel;
    private $notificationModel;
    private $blacklistModel;
    private $settingModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->categoryModel = $this->model('ForumCategory');
        $this->topicModel = $this->model('ForumTopic');
        $this->postModel = $this->model('ForumPost');
        $this->notificationModel = $this->model('Notification');
        $this->blacklistModel = $this->model('WordBlacklist');
        $this->settingModel = $this->model('Setting');
    }
    
    /**
     * Forum Index - List all categories
     */
    public function index()
    {
        $categories = $this->categoryModel->getWithTopicCount();
        
        // Get recent APPROVED topics only
        $recentTopics = $this->topicModel->query(
            "SELECT ft.*, u.username, u.full_name, fc.name as category_name,
                    COUNT(fp.id) as post_count
             FROM forum_topics ft
             LEFT JOIN users u ON ft.user_id = u.id
             LEFT JOIN forum_categories fc ON ft.category_id = fc.id
             LEFT JOIN forum_posts fp ON ft.id = fp.topic_id
             WHERE ft.is_approved = 1
             GROUP BY ft.id
             ORDER BY ft.last_activity_at DESC
             LIMIT 10"
        )->fetchAll();
        
        $data = [
            'page_title' => 'Forum Diskusi',
            'categories' => $categories,
            'recent_topics' => $recentTopics
        ];
        
        $this->view('forum/index', $data);
    }
    
    /**
     * View topics in a category
     */
    public function category($id)
    {
        $category = $this->categoryModel->findById($id);
        
        if (!$category) {
            $this->setFlash('error', 'Kategori tidak ditemukan');
            $this->redirect('/forum');
        }
        
        // Get APPROVED topics only
        $topics = $this->topicModel->query(
            "SELECT ft.*, u.username, u.full_name,
                    COUNT(fp.id) as reply_count
             FROM forum_topics ft
             LEFT JOIN users u ON ft.user_id = u.id
             LEFT JOIN forum_posts fp ON ft.id = fp.topic_id
             WHERE ft.category_id = :cat_id AND ft.is_approved = 1
             GROUP BY ft.id
             ORDER BY ft.is_pinned DESC, ft.last_activity_at DESC",
            [':cat_id' => $id]
        )->fetchAll();
        
        $data = [
            'page_title' => $category['name'],
            'category' => $category,
            'topics' => $topics
        ];
        
        $this->view('forum/category', $data);
    }
    
    /**
     * View single topic with posts
     */
    public function topic($id)
    {
        $topic = $this->topicModel->query(
            "SELECT ft.*, u.username, u.full_name, u.profile_picture,
                    fc.name as category_name
             FROM forum_topics ft
             LEFT JOIN users u ON ft.user_id = u.id
             LEFT JOIN forum_categories fc ON ft.category_id = fc.id
             WHERE ft.id = :id",
            [':id' => $id]
        )->fetch();
        
        if (!$topic) {
            $this->setFlash('error', 'Topik tidak ditemukan');
            $this->redirect('/forum');
        }
        
        // Check if approved (unless user is the creator or admin)
        $canView = false;
        if ($topic['is_approved']) {
            $canView = true;
        } elseif (auth() && (user('id') == $topic['user_id'] || hasPermission('forum_moderation'))) {
            $canView = true;
        }
        
        if (!$canView) {
            $this->setFlash('error', 'Topik belum disetujui');
            $this->redirect('/forum');
        }
        
        // Increment view count
        $this->topicModel->incrementViews($id);
        
        // Get posts
        $posts = $this->postModel->query(
            "SELECT fp.*, u.username, u.full_name, u.profile_picture
             FROM forum_posts fp
             LEFT JOIN users u ON fp.user_id = u.id
             WHERE fp.topic_id = :topic_id
             ORDER BY fp.created_at ASC",
            [':topic_id' => $id]
        )->fetchAll();
        
        $data = [
            'page_title' => $topic['title'],
            'topic' => $topic,
            'posts' => $posts,
            'csrf_token' => $this->generateCsrf()
        ];
        
        $this->view('forum/topic', $data);
    }
    
    /**
     * Create new topic (REQUIRES LOGIN)
     */
    public function createTopic()
    {
        // ✅ REQUIRE AUTH - User harus login!
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->processCreateTopic();
        } else {
            $categories = $this->categoryModel->getAllOrdered();
            
            $data = [
                'page_title' => 'Buat Topik Baru',
                'categories' => $categories,
                'csrf_token' => $this->generateCsrf()
            ];
            
            $this->view('forum/create_topic', $data);
        }
    }
    
    /**
     * Process Create Topic with BLACKLIST CHECK & TURNSTILE
     */
    private function processCreateTopic()
    {
        // Verify Turnstile (anti-bot & spam prevention)
        if (!turnstile_verify()) {
            $this->setFlash('error', turnstile_error() ?? 'Security verification failed. Please try again.');
            $this->redirect('/forum/create-topic');
        }
        
        if (!$this->verifyCsrf()) {
            $this->setFlash('error', 'Invalid request');
            $this->redirect('/forum/create-topic');
        }
        
        $userId = $_SESSION['user_id'];
        $categoryId = $_POST['category_id'] ?? 0;
        $title = $_POST['title'] ?? '';
        $content = $_POST['content'] ?? '';
        
        // Validation
        if (empty($title) || empty($content) || empty($categoryId)) {
            $this->setFlash('error', 'Semua field harus diisi');
            flashOldInput();
            $this->redirect('/forum/create-topic');
        }
        
        // ============================================
        // 🔥 CRITICAL: BLACKLIST CHECK
        // ============================================
        $blacklistEnabled = $this->settingModel->getValue('blacklist_enabled', '1') === '1';
        
        if ($blacklistEnabled) {
            $checkResult = $this->blacklistModel->checkContent($title . ' ' . $content);
            
            if ($checkResult['has_blacklist']) {
                // Log detection
                $this->blacklistModel->logDetection(
                    $userId,
                    'forum_topic',
                    null,
                    $checkResult['detected_words'],
                    $title . ' | ' . $content,
                    $checkResult['action']
                );
                
                // Handle based on action
                if ($checkResult['action'] === 'auto_reject' || $checkResult['action'] === 'block') {
                    $words = array_column($checkResult['detected_words'], 'word');
                    
                    $this->setFlash('error', '⚠️ PERINGATAN: Konten Anda mengandung kata-kata yang tidak diperbolehkan: <strong>' . 
                        implode(', ', $words) . '</strong>. Silakan edit konten Anda.');
                    
                    flashOldInput();
                    $this->redirect('/forum/create-topic');
                }
                // If 'flag', continue but will be flagged for admin review
            }
        }
        
        // Create slug
        $slug = slug($title);
        
        // Check if slug exists
        $existingTopic = $this->topicModel->getBySlug($slug);
        if ($existingTopic) {
            $slug = $slug . '-' . time();
        }
        
        // Check if forum requires approval
        $requiresApproval = $this->settingModel->getValue('forum_requires_approval', '1') === '1';
        
        // Auto-approve for verified users if setting enabled
        $autoApproveVerified = $this->settingModel->getValue('forum_auto_approve_verified', '0') === '1';
        $userModel = $this->model('User');
        $user = $userModel->findById($userId);
        
        $isApproved = 0;
        if (!$requiresApproval || ($autoApproveVerified && $user['email_verified'])) {
            $isApproved = 1;
        }
        
        // Create topic
        $topicData = [
            'category_id' => $categoryId,
            'user_id' => $userId,
            'title' => $title,
            'slug' => $slug,
            'content' => $content,
            'is_approved' => $isApproved,
            'last_activity_at' => date('Y-m-d H:i:s')
        ];
        
        $topicId = $this->topicModel->insert($topicData);
        
        if ($topicId) {
            // Log activity
            log_audit($userId, 'create', 'forum_topics', $topicId);
            
            if ($isApproved) {
                $this->setFlash('success', 'Topik berhasil dibuat dan dipublikasikan!');
                $this->redirect('/forum/topic/' . $topicId);
            } else {
                // Notify admins for approval
                $this->notifyAdminsForApproval($topicId, $title);
                
                $this->setFlash('info', 'Topik berhasil dibuat dan menunggu persetujuan admin. Anda akan menerima notifikasi setelah topik disetujui.');
                $this->redirect('/dashboard');
            }
        } else {
            $this->setFlash('error', 'Gagal membuat topik');
            $this->redirect('/forum/create-topic');
        }
    }
    
    /**
     * Reply to Topic (REQUIRES LOGIN) with BLACKLIST CHECK
     */
    public function reply($topicId)
    {
        // ✅ REQUIRE AUTH - User harus login!
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/forum/topic/' . $topicId);
        }
        
        // Verify Turnstile (anti-bot & spam prevention)
        if (!turnstile_verify()) {
            $this->setFlash('error', turnstile_error() ?? 'Security verification failed. Please try again.');
            $this->redirect('/forum/topic/' . $topicId);
        }
        
        if (!$this->verifyCsrf()) {
            $this->setFlash('error', 'Invalid request');
            $this->redirect('/forum/topic/' . $topicId);
        }
        
        $topic = $this->topicModel->findById($topicId);
        
        if (!$topic) {
            $this->setFlash('error', 'Topik tidak ditemukan');
            $this->redirect('/forum');
        }
        
        // Check if topic is locked
        if ($topic['is_locked']) {
            $this->setFlash('error', 'Topik ini telah dikunci');
            $this->redirect('/forum/topic/' . $topicId);
        }
        
        $userId = $_SESSION['user_id'];
        $content = $_POST['content'] ?? '';
        
        if (empty($content)) {
            $this->setFlash('error', 'Konten balasan tidak boleh kosong');
            $this->redirect('/forum/topic/' . $topicId);
        }
        
        // ============================================
        // 🔥 CRITICAL: BLACKLIST CHECK for REPLY
        // ============================================
        $blacklistEnabled = $this->settingModel->getValue('blacklist_enabled', '1') === '1';
        
        if ($blacklistEnabled) {
            $checkResult = $this->blacklistModel->checkContent($content);
            
            if ($checkResult['has_blacklist']) {
                // Log detection
                $this->blacklistModel->logDetection(
                    $userId,
                    'forum_post',
                    null,
                    $checkResult['detected_words'],
                    $content,
                    $checkResult['action']
                );
                
                // Handle based on action
                if ($checkResult['action'] === 'auto_reject' || $checkResult['action'] === 'block') {
                    $words = array_column($checkResult['detected_words'], 'word');
                    
                    $this->setFlash('error', '⚠️ PERINGATAN: Balasan Anda mengandung kata-kata yang tidak diperbolehkan: <strong>' . 
                        implode(', ', $words) . '</strong>. Silakan edit balasan Anda.');
                    
                    flashOldInput();
                    $this->redirect('/forum/topic/' . $topicId);
                }
            }
        }
        
        // Create post
        $postData = [
            'topic_id' => $topicId,
            'user_id' => $userId,
            'content' => $content
        ];
        
        $postId = $this->postModel->insert($postData);
        
        if ($postId) {
            // Update topic last activity
            $this->topicModel->update($topicId, [
                'last_activity_at' => date('Y-m-d H:i:s'),
                'reply_count' => $topic['reply_count'] + 1
            ]);
            
            // Notify topic owner (if not replying to own topic)
            if ($userId != $topic['user_id']) {
                $this->notificationModel->createNotification([
                    'user_id' => $topic['user_id'],
                    'type' => 'forum_reply',
                    'title' => 'Balasan Baru di Topik Anda',
                    'message' => "Ada balasan baru di topik \"{$topic['title']}\"",
                    'link' => '/forum/topic/' . $topicId
                ]);
            }
            
            log_audit($userId, 'create', 'forum_posts', $postId);
            
            $this->setFlash('success', 'Balasan berhasil ditambahkan');
            $this->redirect('/forum/topic/' . $topicId . '#post-' . $postId);
        } else {
            $this->setFlash('error', 'Gagal menambahkan balasan');
            $this->redirect('/forum/topic/' . $topicId);
        }
    }
    
    /**
     * Edit post
     */
    public function editPost($id)
    {
        $this->requireAuth();
        
        $post = $this->postModel->findById($id);
        
        if (!$post || $post['user_id'] != $_SESSION['user_id']) {
            $this->setFlash('error', 'Anda tidak memiliki akses');
            $this->redirect('/forum');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!$this->verifyCsrf()) {
                $this->setFlash('error', 'Invalid request');
                $this->redirect('/forum/edit-post/' . $id);
            }
            
            $content = $_POST['content'] ?? '';
            
            if (empty($content)) {
                $this->setFlash('error', 'Konten tidak boleh kosong');
                $this->redirect('/forum/edit-post/' . $id);
            }
            
            // 🔥 BLACKLIST CHECK
            $blacklistEnabled = $this->settingModel->getValue('blacklist_enabled', '1') === '1';
            
            if ($blacklistEnabled) {
                $checkResult = $this->blacklistModel->checkContent($content);
                
                if ($checkResult['has_blacklist'] && 
                    ($checkResult['action'] === 'auto_reject' || $checkResult['action'] === 'block')) {
                    $words = array_column($checkResult['detected_words'], 'word');
                    
                    $this->setFlash('error', '⚠️ PERINGATAN: Konten mengandung kata terlarang: <strong>' . 
                        implode(', ', $words) . '</strong>');
                    flashOldInput();
                    $this->redirect('/forum/edit-post/' . $id);
                }
            }
            
            if ($this->postModel->update($id, ['content' => $content])) {
                log_audit($_SESSION['user_id'], 'update', 'forum_posts', $id);
                $this->setFlash('success', 'Post berhasil diperbarui');
                $this->redirect('/forum/topic/' . $post['topic_id']);
            }
        }
        
        $data = [
            'page_title' => 'Edit Post',
            'post' => $post,
            'csrf_token' => $this->generateCsrf()
        ];
        
        $this->view('forum/edit_post', $data);
    }
    
    /**
     * Delete post
     */
    public function deletePost($id)
    {
        $this->requireAuth();
        
        $post = $this->postModel->findById($id);
        
        if (!$post || $post['user_id'] != $_SESSION['user_id']) {
            $this->setFlash('error', 'Anda tidak memiliki akses');
            $this->redirect('/forum');
        }
        
        $topicId = $post['topic_id'];
        
        if ($this->postModel->delete($id)) {
            log_audit($_SESSION['user_id'], 'delete', 'forum_posts', $id);
            $this->setFlash('success', 'Post berhasil dihapus');
        }
        
        $this->redirect('/forum/topic/' . $topicId);
    }
    
    /**
     * Search forum
     */
    public function search()
    {
        $query = $_GET['q'] ?? '';
        
        if (empty($query)) {
            $this->redirect('/forum');
        }
        
        // Search in APPROVED topics only
        $results = $this->topicModel->query(
            "SELECT ft.*, u.username, fc.name as category_name,
                    COUNT(fp.id) as reply_count
             FROM forum_topics ft
             LEFT JOIN users u ON ft.user_id = u.id
             LEFT JOIN forum_categories fc ON ft.category_id = fc.id
             LEFT JOIN forum_posts fp ON ft.id = fp.topic_id
             WHERE ft.is_approved = 1 AND (ft.title LIKE :query OR ft.content LIKE :query)
             GROUP BY ft.id
             ORDER BY ft.last_activity_at DESC
             LIMIT 50",
            [':query' => "%{$query}%"]
        )->fetchAll();
        
        $data = [
            'page_title' => 'Hasil Pencarian: ' . $query,
            'query' => $query,
            'results' => $results
        ];
        
        $this->view('forum/search', $data);
    }
    
    /**
     * Notify admins for topic approval
     */
    private function notifyAdminsForApproval($topicId, $topicTitle)
    {
        $userModel = $this->model('User');
        
        // Get all admins and superadmins
        $admins = $userModel->query(
            "SELECT u.id FROM users u 
             JOIN roles r ON u.role_id = r.id 
             WHERE r.name IN ('superadmin', 'admin_konten') AND u.is_active = 1"
        )->fetchAll();
        
        // Create notification for each admin
        foreach ($admins as $admin) {
            $this->notificationModel->createNotification([
                'user_id' => $admin['id'],
                'type' => 'forum_approval',
                'title' => 'Topik Forum Baru Perlu Approval',
                'message' => "Topik \"{$topicTitle}\" menunggu persetujuan Anda.",
                'link' => '/admin/forum/view/' . $topicId
            ]);
        }
    }
}
