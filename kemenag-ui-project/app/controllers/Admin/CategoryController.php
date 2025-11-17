<?php

namespace App\Controllers\Admin;

use Core\Controller;
use App\Models\Category;

/**
 * Admin Category Controller
 * Manage content categories
 */
class CategoryController extends Controller
{
    private $categoryModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->requireAuth();
        $this->requirePermission('content_management');
        
        $this->categoryModel = $this->model('Category');
    }
    
    /**
     * List all categories
     */
    public function index()
    {
        $type = $_GET['type'] ?? 'all';
        
        if ($type !== 'all') {
            $categories = $this->categoryModel->findBy(['type' => $type]);
        } else {
            $categories = $this->categoryModel->findAll();
        }
        
        // Get counts for each type
        $counts = $this->categoryModel->query(
            "SELECT type, COUNT(*) as count FROM categories GROUP BY type"
        )->fetchAll();
        
        $typeCounts = [];
        foreach ($counts as $count) {
            $typeCounts[$count['type']] = $count['count'];
        }
        
        $data = [
            'page_title' => 'Manajemen Kategori',
            'categories' => $categories,
            'type_counts' => $typeCounts,
            'current_type' => $type
        ];
        
        $this->view('admin/categories/index', $data);
    }
    
    /**
     * Create new category
     */
    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->processCreate();
        } else {
            $data = [
                'page_title' => 'Tambah Kategori',
                'csrf_token' => $this->generateCsrf()
            ];
            
            $this->view('admin/categories/create', $data);
        }
    }
    
    /**
     * Process create category
     */
    private function processCreate()
    {
        if (!$this->verifyCsrf()) {
            $this->setFlash('error', 'Invalid request');
            $this->redirect('/admin/categories/create');
        }
        
        $name = $_POST['name'] ?? '';
        $type = $_POST['type'] ?? '';
        $description = $_POST['description'] ?? '';
        
        // Validation
        if (empty($name) || empty($type)) {
            $this->setFlash('error', 'Nama dan Tipe kategori wajib diisi');
            flashOldInput();
            $this->redirect('/admin/categories/create');
        }
        
        // Generate slug
        $slug = $this->categoryModel->generateUniqueSlug($name, $type);
        
        $categoryId = $this->categoryModel->insert([
            'name' => $name,
            'slug' => $slug,
            'type' => $type,
            'description' => $description
        ]);
        
        if ($categoryId) {
            log_audit($_SESSION['user_id'], 'create', 'categories', $categoryId);
            $this->setFlash('success', 'Kategori berhasil ditambahkan');
            $this->redirect('/admin/categories?type=' . $type);
        } else {
            $this->setFlash('error', 'Gagal menambahkan kategori');
            $this->redirect('/admin/categories/create');
        }
    }
    
    /**
     * Edit category
     */
    public function edit($id)
    {
        $category = $this->categoryModel->findById($id);
        
        if (!$category) {
            $this->setFlash('error', 'Kategori tidak ditemukan');
            $this->redirect('/admin/categories');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->processEdit($id);
        } else {
            $data = [
                'page_title' => 'Edit Kategori',
                'category' => $category,
                'csrf_token' => $this->generateCsrf()
            ];
            
            $this->view('admin/categories/edit', $data);
        }
    }
    
    /**
     * Process edit category
     */
    private function processEdit($id)
    {
        if (!$this->verifyCsrf()) {
            $this->setFlash('error', 'Invalid request');
            $this->redirect('/admin/categories/edit/' . $id);
        }
        
        $name = $_POST['name'] ?? '';
        $description = $_POST['description'] ?? '';
        
        if (empty($name)) {
            $this->setFlash('error', 'Nama kategori wajib diisi');
            $this->redirect('/admin/categories/edit/' . $id);
        }
        
        $oldCategory = $this->categoryModel->findById($id);
        
        $updateData = [
            'name' => $name,
            'description' => $description
        ];
        
        // Update slug if name changed
        if ($name !== $oldCategory['name']) {
            $updateData['slug'] = $this->categoryModel->generateUniqueSlug($name, $oldCategory['type']);
        }
        
        if ($this->categoryModel->update($id, $updateData)) {
            log_audit($_SESSION['user_id'], 'update', 'categories', $id);
            $this->setFlash('success', 'Kategori berhasil diperbarui');
            $this->redirect('/admin/categories?type=' . $oldCategory['type']);
        } else {
            $this->setFlash('error', 'Gagal memperbarui kategori');
            $this->redirect('/admin/categories/edit/' . $id);
        }
    }
    
    /**
     * Delete category
     */
    public function delete($id)
    {
        $category = $this->categoryModel->findById($id);
        
        if (!$category) {
            $this->setFlash('error', 'Kategori tidak ditemukan');
            $this->redirect('/admin/categories');
        }
        
        // Check if category is being used
        $isUsed = false;
        
        switch ($category['type']) {
            case 'qa':
                $qaModel = $this->model('QuestionAnswer');
                $count = $qaModel->query(
                    "SELECT COUNT(*) as count FROM question_answers WHERE category_id = :id",
                    [':id' => $id]
                )->fetch()['count'];
                $isUsed = $count > 0;
                break;
                
            case 'fatwa':
                $fatwaModel = $this->model('Fatwa');
                $count = $fatwaModel->query(
                    "SELECT COUNT(*) as count FROM fatwas WHERE category_id = :id",
                    [':id' => $id]
                )->fetch()['count'];
                $isUsed = $count > 0;
                break;
                
            case 'material':
                $materialModel = $this->model('Material');
                $count = $materialModel->query(
                    "SELECT COUNT(*) as count FROM materials WHERE category_id = :id",
                    [':id' => $id]
                )->fetch()['count'];
                $isUsed = $count > 0;
                break;
                
            case 'book':
                $bookModel = $this->model('Book');
                $count = $bookModel->query(
                    "SELECT COUNT(*) as count FROM books WHERE category_id = :id",
                    [':id' => $id]
                )->fetch()['count'];
                $isUsed = $count > 0;
                break;
                
            case 'forum':
                $forumCatModel = $this->model('ForumCategory');
                $count = $forumCatModel->query(
                    "SELECT COUNT(*) as count FROM forum_categories WHERE category_id = :id",
                    [':id' => $id]
                )->fetch()['count'];
                $isUsed = $count > 0;
                break;
        }
        
        if ($isUsed) {
            $this->setFlash('error', 'Tidak dapat menghapus kategori yang sedang digunakan');
            $this->redirect('/admin/categories?type=' . $category['type']);
        }
        
        if ($this->categoryModel->delete($id)) {
            log_audit($_SESSION['user_id'], 'delete', 'categories', $id);
            $this->setFlash('success', 'Kategori berhasil dihapus');
        } else {
            $this->setFlash('error', 'Gagal menghapus kategori');
        }
        
        $this->redirect('/admin/categories?type=' . $category['type']);
    }
    
    /**
     * Get categories by type (AJAX)
     */
    public function getByType($type)
    {
        $categories = $this->categoryModel->findBy(['type' => $type]);
        $this->json(['success' => true, 'categories' => $categories]);
    }
}
