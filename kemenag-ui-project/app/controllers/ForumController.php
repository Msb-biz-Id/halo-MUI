<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\ForumCategory;
use App\Models\ForumTopic;
use App\Models\ForumPost;
use App\Models\Notification;

/**
 * Forum Controller
 * Handles forum discussion system
 */
class ForumController extends Controller
{
    private $categoryModel;
    private $topicModel;
    private $postModel;
    private $notificationModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->categoryModel = $this->model('ForumCategory');
        $this->topicModel = $this->model('ForumTopic');
        $this->postModel = $this->model('ForumPost');
        $this->notificationModel = $this->model('Notification');
    }
    
    /**
     * Forum Index - List all categories
     */
    public function index()
    {
        $categories = $this->categoryModel->getWithTopicCount();
        
        // Get recent topics across all categories
        $recentTopics = $this->topicModel->query(
            "SELECT ft.*, u.username, u.full_name, fc.name as category_name,
                    COUNT(fp.id) as post_count
             FROM forum_topics ft
             LEFT JOIN users u ON ft.user_id = u.id
             LEFT JOIN forum_categories fc ON ft.category_id = fc.id
             LEFT JOIN forum_posts fp ON ft.id = fp.topic_id
             GROUP BY ft.id
             ORDER BY ft.last_post_at DESC
             LIMIT 10"
        )->fetchAll();
        
        $data = [
            'page_title' => 'Forum Diskusi',
            'categories' => $categories,
            'recent_topics' => $recentTopics
        ];
        
        $this->view('frontend/forum/index', $data);
    }
    
    /**
     * View Category Topics
     */
    public function category($id)
    {
        $category = $this->categoryModel->findById($id);
        
        if (!$category) {
            $this->setFlash('error', 'Kategori tidak ditemukan');
            $this->redirect('/forum');
        }
        
        // Get topics in this category
        $topics = $this->topicModel->getByCategory($id);
        
        // Pagination
        $page = $_GET['page'] ?? 1;
        $perPage = ITEMS_PER_PAGE;
        
        $data = [
            'page_title' => 'Forum: ' . $category['name'],
            'category' => $category,
            'topics' => $topics,
            'page' => $page
        ];
        
        $this->view('frontend/forum/category', $data);
    }
    
    /**
     * View Topic and Posts
     */
    public function topic($id)
    {
        $topic = $this->topicModel->getBySlug($id);
        
        // Try by ID if slug not found
        if (!$topic) {
            $topic = $this->topicModel->findById($id);
        }
        
        if (!$topic) {
            $this->setFlash('error', 'Topik tidak ditemukan');
            $this->redirect('/forum');
        }
        
        // Increment view count
        $this->topicModel->incrementViews($topic['id']);
        
        // Get posts in this topic
        $posts = $this->postModel->getByTopic($topic['id']);
        
        // Check if topic is locked
        $canReply = !$topic['is_locked'];
        if ($canReply && !$this->isLoggedIn()) {
            $canReply = false;
        }
        
        $data = [
            'page_title' => $topic['title'],
            'topic' => $topic,
            'posts' => $posts,
            'can_reply' => $canReply,
            'csrf_token' => $this->generateCsrf()
        ];
        
        $this->view('frontend/forum/topic', $data);
    }
    
    /**
     * Create New Topic
     */
    public function createTopic()
    {
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
            
            $this->view('frontend/forum/create_topic', $data);
        }
    }
    
    /**
     * Process Create Topic
     */
    private function processCreateTopic()
    {
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
        
        // Create slug
        $slug = slug($title);
        
        // Check if slug exists
        $existingTopic = $this->topicModel->getBySlug($slug);
        if ($existingTopic) {
            $slug = $slug . '-' . time();
        }
        
        // Create topic
        $topicData = [
            'category_id' => $categoryId,
            'user_id' => $userId,
            'title' => $title,
            'slug' => $slug,
            'content' => $content,
            'last_post_at' => date('Y-m-d H:i:s'),
            'last_post_by' => $userId
        ];
        
        $topicId = $this->topicModel->insert($topicData);
        
        if ($topicId) {
            // Log activity
            log_audit($userId, 'create', 'forum_topics', $topicId);
            
            $this->setFlash('success', 'Topik berhasil dibuat');
            $this->redirect('/forum/topic/' . $slug);
        } else {
            $this->setFlash('error', 'Gagal membuat topik');
            $this->redirect('/forum/create-topic');
        }
    }
    
    /**
     * Reply to Topic
     */
    public function reply($topicId)
    {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
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
            $this->redirect('/forum/topic/' . $topic['slug']);
        }
        
        $userId = $_SESSION['user_id'];
        $content = $_POST['content'] ?? '';
        
        if (empty($content)) {
            $this->setFlash('error', 'Konten balasan tidak boleh kosong');
            $this->redirect('/forum/topic/' . $topic['slug']);
        }
        
        // Create post
        $postData = [
            'topic_id' => $topicId,
            'user_id' => $userId,
            'content' => $content,
            'is_approved' => 1 // Auto-approve for now
        ];
        
        $postId = $this->postModel->createPost($postData);
        
        if ($postId) {
            // Update topic last post
            $this->topicModel->updateLastPost($topicId, $userId);
            
            // Notify topic owner if not self
            if ($topic['user_id'] != $userId) {
                $this->notificationModel->createNotification([
                    'user_id' => $topic['user_id'],
                    'type' => 'forum_reply',
                    'title' => 'Balasan Baru di Topik Anda',
                    'message' => $_SESSION['username'] . ' membalas topik "' . $topic['title'] . '"',
                    'link' => '/forum/topic/' . $topic['slug'] . '#post-' . $postId
                ]);
            }
            
            // Log activity
            log_audit($userId, 'create', 'forum_posts', $postId);
            
            $this->setFlash('success', 'Balasan berhasil ditambahkan');
        } else {
            $this->setFlash('error', 'Gagal menambahkan balasan');
        }
        
        $this->redirect('/forum/topic/' . $topic['slug']);
    }
    
    /**
     * Edit Post
     */
    public function editPost($postId)
    {
        $this->requireAuth();
        
        $post = $this->postModel->findById($postId);
        
        if (!$post) {
            $this->setFlash('error', 'Post tidak ditemukan');
            $this->redirect('/forum');
        }
        
        // Check ownership
        $userId = $_SESSION['user_id'];
        if ($post['user_id'] != $userId && !$this->hasRole('superadmin')) {
            $this->unauthorized();
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->processEditPost($postId);
        } else {
            $topic = $this->topicModel->findById($post['topic_id']);
            
            $data = [
                'page_title' => 'Edit Post',
                'post' => $post,
                'topic' => $topic,
                'csrf_token' => $this->generateCsrf()
            ];
            
            $this->view('frontend/forum/edit_post', $data);
        }
    }
    
    /**
     * Process Edit Post
     */
    private function processEditPost($postId)
    {
        if (!$this->verifyCsrf()) {
            $this->setFlash('error', 'Invalid request');
            $this->redirect('/forum');
        }
        
        $post = $this->postModel->findById($postId);
        $content = $_POST['content'] ?? '';
        
        if (empty($content)) {
            $this->setFlash('error', 'Konten tidak boleh kosong');
            $this->redirect('/forum/edit-post/' . $postId);
        }
        
        if ($this->postModel->update($postId, ['content' => $content])) {
            $userId = $_SESSION['user_id'];
            log_audit($userId, 'update', 'forum_posts', $postId);
            
            $topic = $this->topicModel->findById($post['topic_id']);
            $this->setFlash('success', 'Post berhasil diperbarui');
            $this->redirect('/forum/topic/' . $topic['id'] . '#post-' . $postId);
        } else {
            $this->setFlash('error', 'Gagal memperbarui post');
            $this->redirect('/forum/edit-post/' . $postId);
        }
    }
    
    /**
     * Delete Post
     */
    public function deletePost($postId)
    {
        $this->requireAuth();
        
        $post = $this->postModel->findById($postId);
        
        if (!$post) {
            $this->json(['success' => false, 'message' => 'Post tidak ditemukan'], 404);
        }
        
        // Check ownership or admin
        $userId = $_SESSION['user_id'];
        if ($post['user_id'] != $userId && !$this->hasRole('superadmin')) {
            $this->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }
        
        $topic = $this->topicModel->findById($post['topic_id']);
        
        if ($this->postModel->delete($postId)) {
            log_audit($userId, 'delete', 'forum_posts', $postId);
            $this->setFlash('success', 'Post berhasil dihapus');
        } else {
            $this->setFlash('error', 'Gagal menghapus post');
        }
        
        $this->redirect('/forum/topic/' . $topic['id']);
    }
    
    /**
     * Search Forum
     */
    public function search()
    {
        $query = $_GET['q'] ?? '';
        
        if (empty($query)) {
            $this->redirect('/forum');
        }
        
        $results = $this->topicModel->query(
            "SELECT ft.*, u.username, fc.name as category_name,
                    COUNT(fp.id) as post_count
             FROM forum_topics ft
             LEFT JOIN users u ON ft.user_id = u.id
             LEFT JOIN forum_categories fc ON ft.category_id = fc.id
             LEFT JOIN forum_posts fp ON ft.id = fp.topic_id
             WHERE MATCH(ft.title, ft.content) AGAINST(:query IN NATURAL LANGUAGE MODE)
             GROUP BY ft.id
             ORDER BY ft.updated_at DESC",
            [':query' => $query]
        )->fetchAll();
        
        $data = [
            'page_title' => 'Hasil Pencarian: ' . $query,
            'query' => $query,
            'results' => $results
        ];
        
        $this->view('frontend/forum/search', $data);
    }
}
