<?php

namespace App\Controllers\Admin;

use Core\Controller;
use App\Models\Material;
use App\Models\Category;

/**
 * Admin Material Controller
 * Manage Materi Moderasi-Toleransi content
 */
class MaterialController extends Controller
{
    private $materialModel;
    private $categoryModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->requireAuth();
        $this->requirePermission('content_management');
        
        $this->materialModel = $this->model('Material');
        $this->categoryModel = $this->model('Category');
    }
    
    /**
     * List all materials
     */
    public function index()
    {
        $categoryId = $_GET['category'] ?? '';
        $type = $_GET['type'] ?? 'all';
        $status = $_GET['status'] ?? 'all';
        $search = $_GET['search'] ?? '';
        
        $page = $_GET['page'] ?? 1;
        $perPage = 20;
        
        $conditions = [];
        
        if (!empty($categoryId)) {
            $conditions[] = "m.category_id = {$categoryId}";
        }
        
        if ($type !== 'all') {
            $conditions[] = "m.type = '{$type}'";
        }
        
        if ($status !== 'all') {
            $conditions[] = "m.is_published = " . ($status === 'published' ? 1 : 0);
        }
        
        if (!empty($search)) {
            $conditions[] = "(m.title LIKE '%{$search}%' OR m.content LIKE '%{$search}%')";
        }
        
        $where = !empty($conditions) ? 'WHERE ' . implode(' AND ', $conditions) : '';
        
        $materials = $this->materialModel->query(
            "SELECT m.*, c.name as category_name, u.full_name as author_name
             FROM materials m
             LEFT JOIN categories c ON m.category_id = c.id
             LEFT JOIN users u ON m.created_by = u.id
             {$where}
             ORDER BY m.created_at DESC
             LIMIT " . (($page - 1) * $perPage) . ", {$perPage}"
        )->fetchAll();
        
        $total = $this->materialModel->query(
            "SELECT COUNT(*) as total FROM materials m {$where}"
        )->fetch()['total'];
        
        $categories = $this->categoryModel->findBy(['type' => 'material']);
        
        $data = [
            'page_title' => 'Manajemen Materi',
            'materials' => $materials,
            'categories' => $categories,
            'pagination' => [
                'current_page' => $page,
                'total_pages' => ceil($total / $perPage),
                'total_items' => $total,
                'per_page' => $perPage
            ],
            'filters' => [
                'category' => $categoryId,
                'type' => $type,
                'status' => $status,
                'search' => $search
            ]
        ];
        
        $this->view('admin/material/index', $data);
    }
    
    /**
     * Create new material
     */
    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->processCreate();
        } else {
            $categories = $this->categoryModel->findBy(['type' => 'material']);
            
            $data = [
                'page_title' => 'Tambah Materi',
                'categories' => $categories,
                'csrf_token' => $this->generateCsrf()
            ];
            
            $this->view('admin/material/create', $data);
        }
    }
    
    /**
     * Process create material
     */
    private function processCreate()
    {
        if (!$this->verifyCsrf()) {
            $this->setFlash('error', 'Invalid request');
            $this->redirect('/admin/material/create');
        }
        
        $title = $_POST['title'] ?? '';
        $content = $_POST['content'] ?? '';
        $categoryId = $_POST['category_id'] ?? 0;
        $type = $_POST['type'] ?? 'text';
        $videoUrl = $_POST['video_url'] ?? null;
        $summary = $_POST['summary'] ?? '';
        $tags = $_POST['tags'] ?? '';
        $metaDescription = $_POST['meta_description'] ?? '';
        $isPublished = isset($_POST['is_published']) ? 1 : 0;
        
        // Validation
        if (empty($title) || empty($categoryId)) {
            $this->setFlash('error', 'Judul dan Kategori wajib diisi');
            flashOldInput();
            $this->redirect('/admin/material/create');
        }
        
        if ($type === 'video' && empty($videoUrl)) {
            $this->setFlash('error', 'URL video wajib diisi untuk tipe video');
            flashOldInput();
            $this->redirect('/admin/material/create');
        }
        
        // Generate slug
        $slug = $this->materialModel->generateUniqueSlug($title);
        
        // Handle thumbnail upload
        $thumbnailPath = null;
        if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
            $thumbnailPath = upload_file($_FILES['thumbnail'], 'materials/thumbnails/');
        }
        
        $materialId = $this->materialModel->insert([
            'category_id' => $categoryId,
            'title' => $title,
            'summary' => $summary,
            'content' => $content,
            'type' => $type,
            'video_url' => $videoUrl,
            'thumbnail' => $thumbnailPath,
            'slug' => $slug,
            'tags' => $tags,
            'meta_description' => $metaDescription,
            'is_published' => $isPublished,
            'created_by' => $_SESSION['user_id']
        ]);
        
        if ($materialId) {
            log_audit($_SESSION['user_id'], 'create', 'materials', $materialId);
            $this->setFlash('success', 'Materi berhasil ditambahkan');
            $this->redirect('/admin/material');
        } else {
            $this->setFlash('error', 'Gagal menambahkan materi');
            $this->redirect('/admin/material/create');
        }
    }
    
    /**
     * Edit material
     */
    public function edit($id)
    {
        $material = $this->materialModel->findById($id);
        
        if (!$material) {
            $this->setFlash('error', 'Materi tidak ditemukan');
            $this->redirect('/admin/material');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->processEdit($id);
        } else {
            $categories = $this->categoryModel->findBy(['type' => 'material']);
            
            $data = [
                'page_title' => 'Edit Materi',
                'material' => $material,
                'categories' => $categories,
                'csrf_token' => $this->generateCsrf()
            ];
            
            $this->view('admin/material/edit', $data);
        }
    }
    
    /**
     * Process edit material
     */
    private function processEdit($id)
    {
        if (!$this->verifyCsrf()) {
            $this->setFlash('error', 'Invalid request');
            $this->redirect('/admin/material/edit/' . $id);
        }
        
        $updateData = [
            'category_id' => $_POST['category_id'] ?? 0,
            'title' => $_POST['title'] ?? '',
            'summary' => $_POST['summary'] ?? '',
            'content' => $_POST['content'] ?? '',
            'type' => $_POST['type'] ?? 'text',
            'video_url' => $_POST['video_url'] ?? null,
            'tags' => $_POST['tags'] ?? '',
            'meta_description' => $_POST['meta_description'] ?? '',
            'is_published' => isset($_POST['is_published']) ? 1 : 0
        ];
        
        // Update slug if title changed
        $oldMaterial = $this->materialModel->findById($id);
        if ($updateData['title'] !== $oldMaterial['title']) {
            $updateData['slug'] = $this->materialModel->generateUniqueSlug($updateData['title']);
        }
        
        // Handle thumbnail upload
        if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
            // Delete old thumbnail
            if (!empty($oldMaterial['thumbnail'])) {
                delete_file($oldMaterial['thumbnail']);
            }
            $updateData['thumbnail'] = upload_file($_FILES['thumbnail'], 'materials/thumbnails/');
        }
        
        if ($this->materialModel->update($id, $updateData)) {
            log_audit($_SESSION['user_id'], 'update', 'materials', $id);
            $this->setFlash('success', 'Materi berhasil diperbarui');
            $this->redirect('/admin/material');
        } else {
            $this->setFlash('error', 'Gagal memperbarui materi');
            $this->redirect('/admin/material/edit/' . $id);
        }
    }
    
    /**
     * Delete material
     */
    public function delete($id)
    {
        $material = $this->materialModel->findById($id);
        
        if ($material && !empty($material['thumbnail'])) {
            delete_file($material['thumbnail']);
        }
        
        if ($this->materialModel->delete($id)) {
            log_audit($_SESSION['user_id'], 'delete', 'materials', $id);
            $this->setFlash('success', 'Materi berhasil dihapus');
        } else {
            $this->setFlash('error', 'Gagal menghapus materi');
        }
        
        $this->redirect('/admin/material');
    }
    
    /**
     * Toggle publish status
     */
    public function togglePublish($id)
    {
        $material = $this->materialModel->findById($id);
        
        if (!$material) {
            $this->json(['success' => false, 'message' => 'Not found'], 404);
        }
        
        $newStatus = $material['is_published'] ? 0 : 1;
        
        if ($this->materialModel->update($id, ['is_published' => $newStatus])) {
            log_audit($_SESSION['user_id'], 'toggle_publish', 'materials', $id);
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
