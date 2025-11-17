<?php

namespace App\Controllers\Admin;

use Core\Controller;
use App\Models\Book;
use App\Models\Category;

/**
 * Admin Book Controller
 * Manage Perpustakaan Digital content
 */
class BookController extends Controller
{
    private $bookModel;
    private $categoryModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->requireAuth();
        $this->requirePermission('content_management');
        
        $this->bookModel = $this->model('Book');
        $this->categoryModel = $this->model('Category');
    }
    
    /**
     * List all books
     */
    public function index()
    {
        $categoryId = $_GET['category'] ?? '';
        $status = $_GET['status'] ?? 'all';
        $search = $_GET['search'] ?? '';
        
        $page = $_GET['page'] ?? 1;
        $perPage = 20;
        
        $conditions = [];
        
        if (!empty($categoryId)) {
            $conditions[] = "b.category_id = {$categoryId}";
        }
        
        if ($status !== 'all') {
            $conditions[] = "b.is_published = " . ($status === 'published' ? 1 : 0);
        }
        
        if (!empty($search)) {
            $conditions[] = "(b.title LIKE '%{$search}%' OR b.author LIKE '%{$search}%' OR b.publisher LIKE '%{$search}%')";
        }
        
        $where = !empty($conditions) ? 'WHERE ' . implode(' AND ', $conditions) : '';
        
        $books = $this->bookModel->query(
            "SELECT b.*, c.name as category_name, u.full_name as uploader_name
             FROM books b
             LEFT JOIN categories c ON b.category_id = c.id
             LEFT JOIN users u ON b.created_by = u.id
             {$where}
             ORDER BY b.created_at DESC
             LIMIT " . (($page - 1) * $perPage) . ", {$perPage}"
        )->fetchAll();
        
        $total = $this->bookModel->query(
            "SELECT COUNT(*) as total FROM books b {$where}"
        )->fetch()['total'];
        
        $categories = $this->categoryModel->findBy(['type' => 'book']);
        
        $data = [
            'page_title' => 'Manajemen Buku',
            'books' => $books,
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
        
        $this->view('admin/books/index', $data);
    }
    
    /**
     * Create new book
     */
    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->processCreate();
        } else {
            $categories = $this->categoryModel->findBy(['type' => 'book']);
            
            $data = [
                'page_title' => 'Tambah Buku',
                'categories' => $categories,
                'csrf_token' => $this->generateCsrf()
            ];
            
            $this->view('admin/books/create', $data);
        }
    }
    
    /**
     * Process create book
     */
    private function processCreate()
    {
        if (!$this->verifyCsrf()) {
            $this->setFlash('error', 'Invalid request');
            $this->redirect('/admin/books/create');
        }
        
        $title = $_POST['title'] ?? '';
        $author = $_POST['author'] ?? '';
        $publisher = $_POST['publisher'] ?? '';
        $publishYear = $_POST['publish_year'] ?? null;
        $isbn = $_POST['isbn'] ?? null;
        $categoryId = $_POST['category_id'] ?? 0;
        $description = $_POST['description'] ?? '';
        $tags = $_POST['tags'] ?? '';
        $metaDescription = $_POST['meta_description'] ?? '';
        $isPublished = isset($_POST['is_published']) ? 1 : 0;
        
        // Validation
        if (empty($title) || empty($author) || empty($categoryId)) {
            $this->setFlash('error', 'Judul, Penulis, dan Kategori wajib diisi');
            flashOldInput();
            $this->redirect('/admin/books/create');
        }
        
        // Generate slug
        $slug = $this->bookModel->generateUniqueSlug($title);
        
        // Handle cover upload
        $coverPath = null;
        if (isset($_FILES['cover']) && $_FILES['cover']['error'] === UPLOAD_ERR_OK) {
            $coverPath = upload_file($_FILES['cover'], 'books/covers/');
        }
        
        // Handle PDF upload
        $pdfPath = null;
        if (isset($_FILES['pdf_file']) && $_FILES['pdf_file']['error'] === UPLOAD_ERR_OK) {
            // Validate PDF
            if ($_FILES['pdf_file']['type'] !== 'application/pdf') {
                $this->setFlash('error', 'File harus berformat PDF');
                $this->redirect('/admin/books/create');
            }
            $pdfPath = upload_file($_FILES['pdf_file'], 'books/pdfs/');
        }
        
        if (!$pdfPath) {
            $this->setFlash('error', 'File PDF wajib diupload');
            $this->redirect('/admin/books/create');
        }
        
        // Get file size in MB
        $fileSize = filesize(STORAGE_PATH . $pdfPath) / 1024 / 1024;
        
        $bookId = $this->bookModel->insert([
            'category_id' => $categoryId,
            'title' => $title,
            'author' => $author,
            'publisher' => $publisher,
            'publish_year' => $publishYear,
            'isbn' => $isbn,
            'description' => $description,
            'cover_image' => $coverPath,
            'pdf_file' => $pdfPath,
            'file_size' => $fileSize,
            'slug' => $slug,
            'tags' => $tags,
            'meta_description' => $metaDescription,
            'is_published' => $isPublished,
            'created_by' => $_SESSION['user_id']
        ]);
        
        if ($bookId) {
            log_audit($_SESSION['user_id'], 'create', 'books', $bookId);
            $this->setFlash('success', 'Buku berhasil ditambahkan');
            $this->redirect('/admin/books');
        } else {
            $this->setFlash('error', 'Gagal menambahkan buku');
            $this->redirect('/admin/books/create');
        }
    }
    
    /**
     * Edit book
     */
    public function edit($id)
    {
        $book = $this->bookModel->findById($id);
        
        if (!$book) {
            $this->setFlash('error', 'Buku tidak ditemukan');
            $this->redirect('/admin/books');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->processEdit($id);
        } else {
            $categories = $this->categoryModel->findBy(['type' => 'book']);
            
            $data = [
                'page_title' => 'Edit Buku',
                'book' => $book,
                'categories' => $categories,
                'csrf_token' => $this->generateCsrf()
            ];
            
            $this->view('admin/books/edit', $data);
        }
    }
    
    /**
     * Process edit book
     */
    private function processEdit($id)
    {
        if (!$this->verifyCsrf()) {
            $this->setFlash('error', 'Invalid request');
            $this->redirect('/admin/books/edit/' . $id);
        }
        
        $updateData = [
            'category_id' => $_POST['category_id'] ?? 0,
            'title' => $_POST['title'] ?? '',
            'author' => $_POST['author'] ?? '',
            'publisher' => $_POST['publisher'] ?? '',
            'publish_year' => $_POST['publish_year'] ?? null,
            'isbn' => $_POST['isbn'] ?? null,
            'description' => $_POST['description'] ?? '',
            'tags' => $_POST['tags'] ?? '',
            'meta_description' => $_POST['meta_description'] ?? '',
            'is_published' => isset($_POST['is_published']) ? 1 : 0
        ];
        
        $oldBook = $this->bookModel->findById($id);
        
        // Update slug if title changed
        if ($updateData['title'] !== $oldBook['title']) {
            $updateData['slug'] = $this->bookModel->generateUniqueSlug($updateData['title']);
        }
        
        // Handle cover upload
        if (isset($_FILES['cover']) && $_FILES['cover']['error'] === UPLOAD_ERR_OK) {
            if (!empty($oldBook['cover_image'])) {
                delete_file($oldBook['cover_image']);
            }
            $updateData['cover_image'] = upload_file($_FILES['cover'], 'books/covers/');
        }
        
        // Handle PDF upload
        if (isset($_FILES['pdf_file']) && $_FILES['pdf_file']['error'] === UPLOAD_ERR_OK) {
            if ($_FILES['pdf_file']['type'] !== 'application/pdf') {
                $this->setFlash('error', 'File harus berformat PDF');
                $this->redirect('/admin/books/edit/' . $id);
            }
            
            if (!empty($oldBook['pdf_file'])) {
                delete_file($oldBook['pdf_file']);
            }
            
            $pdfPath = upload_file($_FILES['pdf_file'], 'books/pdfs/');
            $updateData['pdf_file'] = $pdfPath;
            $updateData['file_size'] = filesize(STORAGE_PATH . $pdfPath) / 1024 / 1024;
        }
        
        if ($this->bookModel->update($id, $updateData)) {
            log_audit($_SESSION['user_id'], 'update', 'books', $id);
            $this->setFlash('success', 'Buku berhasil diperbarui');
            $this->redirect('/admin/books');
        } else {
            $this->setFlash('error', 'Gagal memperbarui buku');
            $this->redirect('/admin/books/edit/' . $id);
        }
    }
    
    /**
     * Delete book
     */
    public function delete($id)
    {
        $book = $this->bookModel->findById($id);
        
        if ($book) {
            // Delete files
            if (!empty($book['cover_image'])) {
                delete_file($book['cover_image']);
            }
            if (!empty($book['pdf_file'])) {
                delete_file($book['pdf_file']);
            }
        }
        
        if ($this->bookModel->delete($id)) {
            log_audit($_SESSION['user_id'], 'delete', 'books', $id);
            $this->setFlash('success', 'Buku berhasil dihapus');
        } else {
            $this->setFlash('error', 'Gagal menghapus buku');
        }
        
        $this->redirect('/admin/books');
    }
    
    /**
     * Toggle publish status
     */
    public function togglePublish($id)
    {
        $book = $this->bookModel->findById($id);
        
        if (!$book) {
            $this->json(['success' => false, 'message' => 'Not found'], 404);
        }
        
        $newStatus = $book['is_published'] ? 0 : 1;
        
        if ($this->bookModel->update($id, ['is_published' => $newStatus])) {
            log_audit($_SESSION['user_id'], 'toggle_publish', 'books', $id);
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
