<?php

namespace App\Controllers\Admin;

use Core\Controller;
use App\Models\QuestionAnswer;
use App\Models\Category;

/**
 * Admin Q&A Controller
 * Manage Tanya Jawab Keagamaan content
 */
class QuestionAnswerController extends Controller
{
    private $qaModel;
    private $categoryModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->requireAuth();
        $this->requirePermission('content_management');
        
        $this->qaModel = $this->model('QuestionAnswer');
        $this->categoryModel = $this->model('Category');
    }
    
    /**
     * List all Q&A
     */
    public function index()
    {
        $categoryId = $_GET['category'] ?? '';
        $status = $_GET['status'] ?? 'all';
        $search = $_GET['search'] ?? '';
        
        $page = $_GET['page'] ?? 1;
        $perPage = 20;
        
        // Build query
        $conditions = [];
        
        if (!empty($categoryId)) {
            $conditions[] = "qa.category_id = {$categoryId}";
        }
        
        if ($status !== 'all') {
            $conditions[] = "qa.is_published = " . ($status === 'published' ? 1 : 0);
        }
        
        if (!empty($search)) {
            $conditions[] = "(qa.question LIKE '%{$search}%' OR qa.answer LIKE '%{$search}%' OR qa.tags LIKE '%{$search}%')";
        }
        
        $where = !empty($conditions) ? 'WHERE ' . implode(' AND ', $conditions) : '';
        
        $qas = $this->qaModel->query(
            "SELECT qa.*, c.name as category_name, u.full_name as author_name
             FROM question_answers qa
             LEFT JOIN categories c ON qa.category_id = c.id
             LEFT JOIN users u ON qa.created_by = u.id
             {$where}
             ORDER BY qa.created_at DESC
             LIMIT " . (($page - 1) * $perPage) . ", {$perPage}"
        )->fetchAll();
        
        // Get total for pagination
        $total = $this->qaModel->query(
            "SELECT COUNT(*) as total FROM question_answers qa {$where}"
        )->fetch()['total'];
        
        $categories = $this->categoryModel->findBy(['type' => 'qa']);
        
        $data = [
            'page_title' => 'Manajemen Tanya Jawab',
            'qas' => $qas,
            'categories' => $categories,
            'pagination' => [
                'current_page' => $page,
                'total_pages' => ceil($total / $perPage),
                'total_items' => $total,
                'per_page' => $perPage
            ],
            'filters' => [
                'category' => $categoryId,
                'status' => $status,
                'search' => $search
            ]
        ];
        
        $this->view('admin/qa/index', $data);
    }
    
    /**
     * Create new Q&A
     */
    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->processCreate();
        } else {
            $categories = $this->categoryModel->findBy(['type' => 'qa']);
            
            $data = [
                'page_title' => 'Tambah Tanya Jawab',
                'categories' => $categories,
                'csrf_token' => $this->generateCsrf()
            ];
            
            $this->view('admin/qa/create', $data);
        }
    }
    
    /**
     * Process create Q&A
     */
    private function processCreate()
    {
        if (!$this->verifyCsrf()) {
            $this->setFlash('error', 'Invalid request');
            $this->redirect('/admin/qa/create');
        }
        
        $question = $_POST['question'] ?? '';
        $answer = $_POST['answer'] ?? '';
        $categoryId = $_POST['category_id'] ?? 0;
        $tags = $_POST['tags'] ?? '';
        $metaDescription = $_POST['meta_description'] ?? '';
        $isPublished = isset($_POST['is_published']) ? 1 : 0;
        
        // Validation
        if (empty($question) || empty($answer) || empty($categoryId)) {
            $this->setFlash('error', 'Pertanyaan, Jawaban, dan Kategori wajib diisi');
            flashOldInput();
            $this->redirect('/admin/qa/create');
        }
        
        // Generate slug
        $slug = $this->qaModel->generateUniqueSlug($question);
        
        $qaId = $this->qaModel->insert([
            'category_id' => $categoryId,
            'question' => $question,
            'answer' => $answer,
            'slug' => $slug,
            'tags' => $tags,
            'meta_description' => $metaDescription,
            'is_published' => $isPublished,
            'created_by' => $_SESSION['user_id']
        ]);
        
        if ($qaId) {
            log_audit($_SESSION['user_id'], 'create', 'question_answers', $qaId);
            $this->setFlash('success', 'Tanya jawab berhasil ditambahkan');
            $this->redirect('/admin/qa');
        } else {
            $this->setFlash('error', 'Gagal menambahkan tanya jawab');
            $this->redirect('/admin/qa/create');
        }
    }
    
    /**
     * Edit Q&A
     */
    public function edit($id)
    {
        $qa = $this->qaModel->findById($id);
        
        if (!$qa) {
            $this->setFlash('error', 'Tanya jawab tidak ditemukan');
            $this->redirect('/admin/qa');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->processEdit($id);
        } else {
            $categories = $this->categoryModel->findBy(['type' => 'qa']);
            
            $data = [
                'page_title' => 'Edit Tanya Jawab',
                'qa' => $qa,
                'categories' => $categories,
                'csrf_token' => $this->generateCsrf()
            ];
            
            $this->view('admin/qa/edit', $data);
        }
    }
    
    /**
     * Process edit Q&A
     */
    private function processEdit($id)
    {
        if (!$this->verifyCsrf()) {
            $this->setFlash('error', 'Invalid request');
            $this->redirect('/admin/qa/edit/' . $id);
        }
        
        $updateData = [
            'category_id' => $_POST['category_id'] ?? 0,
            'question' => $_POST['question'] ?? '',
            'answer' => $_POST['answer'] ?? '',
            'tags' => $_POST['tags'] ?? '',
            'meta_description' => $_POST['meta_description'] ?? '',
            'is_published' => isset($_POST['is_published']) ? 1 : 0
        ];
        
        // Update slug if question changed
        $oldQa = $this->qaModel->findById($id);
        if ($updateData['question'] !== $oldQa['question']) {
            $updateData['slug'] = $this->qaModel->generateUniqueSlug($updateData['question']);
        }
        
        if ($this->qaModel->update($id, $updateData)) {
            log_audit($_SESSION['user_id'], 'update', 'question_answers', $id);
            $this->setFlash('success', 'Tanya jawab berhasil diperbarui');
            $this->redirect('/admin/qa');
        } else {
            $this->setFlash('error', 'Gagal memperbarui tanya jawab');
            $this->redirect('/admin/qa/edit/' . $id);
        }
    }
    
    /**
     * Delete Q&A
     */
    public function delete($id)
    {
        if ($this->qaModel->delete($id)) {
            log_audit($_SESSION['user_id'], 'delete', 'question_answers', $id);
            $this->setFlash('success', 'Tanya jawab berhasil dihapus');
        } else {
            $this->setFlash('error', 'Gagal menghapus tanya jawab');
        }
        
        $this->redirect('/admin/qa');
    }
    
    /**
     * Toggle publish status
     */
    public function togglePublish($id)
    {
        $qa = $this->qaModel->findById($id);
        
        if (!$qa) {
            $this->json(['success' => false, 'message' => 'Not found'], 404);
        }
        
        $newStatus = $qa['is_published'] ? 0 : 1;
        
        if ($this->qaModel->update($id, ['is_published' => $newStatus])) {
            log_audit($_SESSION['user_id'], 'toggle_publish', 'question_answers', $id);
            $this->json([
                'success' => true, 
                'is_published' => $newStatus,
                'message' => $newStatus ? 'Dipublish' : 'Disembunyikan'
            ]);
        } else {
            $this->json(['success' => false, 'message' => 'Failed'], 500);
        }
    }
}
