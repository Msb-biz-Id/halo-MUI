<?php

namespace App\Controllers\Admin;

use Core\Controller;
use App\Models\Fatwa;
use App\Models\Category;

/**
 * Admin Fatwa Controller
 * Manage Informasi Fatwa content
 */
class FatwaController extends Controller
{
    private $fatwaModel;
    private $categoryModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->requireAuth();
        $this->requirePermission('content_management');
        
        $this->fatwaModel = $this->model('Fatwa');
        $this->categoryModel = $this->model('Category');
    }
    
    /**
     * List all fatwas
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
            $conditions[] = "f.category_id = {$categoryId}";
        }
        
        if ($status !== 'all') {
            $conditions[] = "f.is_published = " . ($status === 'published' ? 1 : 0);
        }
        
        if (!empty($search)) {
            $conditions[] = "(f.title LIKE '%{$search}%' OR f.content LIKE '%{$search}%' OR f.fatwa_number LIKE '%{$search}%')";
        }
        
        $where = !empty($conditions) ? 'WHERE ' . implode(' AND ', $conditions) : '';
        
        $fatwas = $this->fatwaModel->query(
            "SELECT f.*, c.name as category_name, u.full_name as author_name
             FROM fatwas f
             LEFT JOIN categories c ON f.category_id = c.id
             LEFT JOIN users u ON f.created_by = u.id
             {$where}
             ORDER BY f.created_at DESC
             LIMIT " . (($page - 1) * $perPage) . ", {$perPage}"
        )->fetchAll();
        
        $total = $this->fatwaModel->query(
            "SELECT COUNT(*) as total FROM fatwas f {$where}"
        )->fetch()['total'];
        
        $categories = $this->categoryModel->findBy(['type' => 'fatwa']);
        
        $data = [
            'page_title' => 'Manajemen Fatwa',
            'fatwas' => $fatwas,
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
        
        $this->view('admin/fatwa/index', $data);
    }
    
    /**
     * Create new fatwa
     */
    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->processCreate();
        } else {
            $categories = $this->categoryModel->findBy(['type' => 'fatwa']);
            
            $data = [
                'page_title' => 'Tambah Fatwa',
                'categories' => $categories,
                'csrf_token' => $this->generateCsrf()
            ];
            
            $this->view('admin/fatwa/create', $data);
        }
    }
    
    /**
     * Process create fatwa
     */
    private function processCreate()
    {
        if (!$this->verifyCsrf()) {
            $this->setFlash('error', 'Invalid request');
            $this->redirect('/admin/fatwa/create');
        }
        
        $title = $_POST['title'] ?? '';
        $content = $_POST['content'] ?? '';
        $categoryId = $_POST['category_id'] ?? 0;
        $fatwaNumber = $_POST['fatwa_number'] ?? '';
        $summary = $_POST['summary'] ?? '';
        $tags = $_POST['tags'] ?? '';
        $metaDescription = $_POST['meta_description'] ?? '';
        $isPublished = isset($_POST['is_published']) ? 1 : 0;
        
        // Validation
        if (empty($title) || empty($content) || empty($categoryId)) {
            $this->setFlash('error', 'Judul, Konten, dan Kategori wajib diisi');
            flashOldInput();
            $this->redirect('/admin/fatwa/create');
        }
        
        // Generate slug
        $slug = $this->fatwaModel->generateUniqueSlug($title);
        
        $fatwaId = $this->fatwaModel->insert([
            'category_id' => $categoryId,
            'fatwa_number' => $fatwaNumber,
            'title' => $title,
            'summary' => $summary,
            'content' => $content,
            'slug' => $slug,
            'tags' => $tags,
            'meta_description' => $metaDescription,
            'is_published' => $isPublished,
            'created_by' => $_SESSION['user_id']
        ]);
        
        if ($fatwaId) {
            log_audit($_SESSION['user_id'], 'create', 'fatwas', $fatwaId);
            $this->setFlash('success', 'Fatwa berhasil ditambahkan');
            $this->redirect('/admin/fatwa');
        } else {
            $this->setFlash('error', 'Gagal menambahkan fatwa');
            $this->redirect('/admin/fatwa/create');
        }
    }
    
    /**
     * Edit fatwa
     */
    public function edit($id)
    {
        $fatwa = $this->fatwaModel->findById($id);
        
        if (!$fatwa) {
            $this->setFlash('error', 'Fatwa tidak ditemukan');
            $this->redirect('/admin/fatwa');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->processEdit($id);
        } else {
            $categories = $this->categoryModel->findBy(['type' => 'fatwa']);
            
            $data = [
                'page_title' => 'Edit Fatwa',
                'fatwa' => $fatwa,
                'categories' => $categories,
                'csrf_token' => $this->generateCsrf()
            ];
            
            $this->view('admin/fatwa/edit', $data);
        }
    }
    
    /**
     * Process edit fatwa
     */
    private function processEdit($id)
    {
        if (!$this->verifyCsrf()) {
            $this->setFlash('error', 'Invalid request');
            $this->redirect('/admin/fatwa/edit/' . $id);
        }
        
        $updateData = [
            'category_id' => $_POST['category_id'] ?? 0,
            'fatwa_number' => $_POST['fatwa_number'] ?? '',
            'title' => $_POST['title'] ?? '',
            'summary' => $_POST['summary'] ?? '',
            'content' => $_POST['content'] ?? '',
            'tags' => $_POST['tags'] ?? '',
            'meta_description' => $_POST['meta_description'] ?? '',
            'is_published' => isset($_POST['is_published']) ? 1 : 0
        ];
        
        // Update slug if title changed
        $oldFatwa = $this->fatwaModel->findById($id);
        if ($updateData['title'] !== $oldFatwa['title']) {
            $updateData['slug'] = $this->fatwaModel->generateUniqueSlug($updateData['title']);
        }
        
        if ($this->fatwaModel->update($id, $updateData)) {
            log_audit($_SESSION['user_id'], 'update', 'fatwas', $id);
            $this->setFlash('success', 'Fatwa berhasil diperbarui');
            $this->redirect('/admin/fatwa');
        } else {
            $this->setFlash('error', 'Gagal memperbarui fatwa');
            $this->redirect('/admin/fatwa/edit/' . $id);
        }
    }
    
    /**
     * Delete fatwa
     */
    public function delete($id)
    {
        if ($this->fatwaModel->delete($id)) {
            log_audit($_SESSION['user_id'], 'delete', 'fatwas', $id);
            $this->setFlash('success', 'Fatwa berhasil dihapus');
        } else {
            $this->setFlash('error', 'Gagal menghapus fatwa');
        }
        
        $this->redirect('/admin/fatwa');
    }
    
    /**
     * Toggle publish status
     */
    public function togglePublish($id)
    {
        $fatwa = $this->fatwaModel->findById($id);
        
        if (!$fatwa) {
            $this->json(['success' => false, 'message' => 'Not found'], 404);
        }
        
        $newStatus = $fatwa['is_published'] ? 0 : 1;
        
        if ($this->fatwaModel->update($id, ['is_published' => $newStatus])) {
            log_audit($_SESSION['user_id'], 'toggle_publish', 'fatwas', $id);
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
