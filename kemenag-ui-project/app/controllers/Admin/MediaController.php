<?php

namespace App\Controllers\Admin;

use Core\Controller;
use App\Models\Media;

/**
 * Admin Media Controller
 * Manage uploaded files and media
 */
class MediaController extends Controller
{
    private $mediaModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->requireAuth();
        $this->requirePermission('media_management');
        
        $this->mediaModel = $this->model('Media');
    }
    
    /**
     * List all media
     */
    public function index()
    {
        $type = $_GET['type'] ?? 'all';
        $search = $_GET['search'] ?? '';
        
        $page = $_GET['page'] ?? 1;
        $perPage = 30;
        
        $conditions = [];
        
        if ($type !== 'all') {
            $conditions[] = "media_type = '{$type}'";
        }
        
        if (!empty($search)) {
            $conditions[] = "(file_name LIKE '%{$search}%' OR title LIKE '%{$search}%')";
        }
        
        $where = !empty($conditions) ? 'WHERE ' . implode(' AND ', $conditions) : '';
        
        $media = $this->mediaModel->query(
            "SELECT m.*, u.full_name as uploader_name
             FROM media m
             LEFT JOIN users u ON m.uploaded_by = u.id
             {$where}
             ORDER BY m.uploaded_at DESC
             LIMIT " . (($page - 1) * $perPage) . ", {$perPage}"
        )->fetchAll();
        
        $total = $this->mediaModel->query(
            "SELECT COUNT(*) as total FROM media m {$where}"
        )->fetch()['total'];
        
        // Get storage statistics
        $stats = $this->mediaModel->query(
            "SELECT 
                COUNT(*) as total_files,
                SUM(file_size) as total_size,
                SUM(CASE WHEN media_type = 'image' THEN 1 ELSE 0 END) as total_images,
                SUM(CASE WHEN media_type = 'document' THEN 1 ELSE 0 END) as total_docs,
                SUM(CASE WHEN media_type = 'other' THEN 1 ELSE 0 END) as total_others
             FROM media"
        )->fetch();
        
        $data = [
            'page_title' => 'Manajemen Media',
            'media' => $media,
            'stats' => $stats,
            'pagination' => [
                'current_page' => $page,
                'total_pages' => ceil($total / $perPage),
                'total_items' => $total,
                'per_page' => $perPage
            ],
            'filters' => [
                'type' => $type,
                'search' => $search
            ]
        ];
        
        $this->view('admin/media/index', $data);
    }
    
    /**
     * Upload media
     */
    public function upload()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$this->verifyCsrf()) {
            $this->json(['success' => false, 'message' => 'Invalid request'], 400);
        }
        
        if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
            $this->json(['success' => false, 'message' => 'No file uploaded'], 400);
        }
        
        $file = $_FILES['file'];
        $title = $_POST['title'] ?? '';
        
        // Determine media type
        $mimeType = $file['type'];
        if (strpos($mimeType, 'image/') === 0) {
            $mediaType = 'image';
            $folder = 'media/images/';
        } elseif (strpos($mimeType, 'application/pdf') === 0 || 
                  strpos($mimeType, 'application/msword') === 0 ||
                  strpos($mimeType, 'application/vnd.') === 0) {
            $mediaType = 'document';
            $folder = 'media/documents/';
        } else {
            $mediaType = 'other';
            $folder = 'media/others/';
        }
        
        // Upload file
        $filePath = upload_file($file, $folder);
        
        if (!$filePath) {
            $this->json(['success' => false, 'message' => 'Failed to upload file'], 500);
        }
        
        // Save to database
        $mediaId = $this->mediaModel->insert([
            'file_name' => basename($filePath),
            'file_path' => $filePath,
            'file_size' => $file['size'],
            'mime_type' => $mimeType,
            'media_type' => $mediaType,
            'title' => $title ?: basename($file['name']),
            'uploaded_by' => $_SESSION['user_id']
        ]);
        
        if ($mediaId) {
            log_audit($_SESSION['user_id'], 'upload', 'media', $mediaId);
            
            $media = $this->mediaModel->findById($mediaId);
            
            $this->json([
                'success' => true, 
                'message' => 'File uploaded successfully',
                'media' => $media
            ]);
        } else {
            // Delete uploaded file if DB insert fails
            delete_file($filePath);
            $this->json(['success' => false, 'message' => 'Failed to save media info'], 500);
        }
    }
    
    /**
     * Edit media info
     */
    public function edit($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$this->verifyCsrf()) {
            $this->setFlash('error', 'Invalid request');
            $this->redirect('/admin/media');
        }
        
        $title = $_POST['title'] ?? '';
        $altText = $_POST['alt_text'] ?? '';
        
        if ($this->mediaModel->update($id, [
            'title' => $title,
            'alt_text' => $altText
        ])) {
            log_audit($_SESSION['user_id'], 'update', 'media', $id);
            $this->setFlash('success', 'Media info berhasil diperbarui');
        } else {
            $this->setFlash('error', 'Gagal memperbarui media info');
        }
        
        $this->redirect('/admin/media');
    }
    
    /**
     * Delete media
     */
    public function delete($id)
    {
        $media = $this->mediaModel->findById($id);
        
        if (!$media) {
            $this->json(['success' => false, 'message' => 'Media not found'], 404);
        }
        
        // Check if media is being used
        // TODO: Check in books, materials, etc.
        
        // Delete file
        delete_file($media['file_path']);
        
        // Delete from database
        if ($this->mediaModel->delete($id)) {
            log_audit($_SESSION['user_id'], 'delete', 'media', $id);
            $this->json(['success' => true, 'message' => 'Media deleted successfully']);
        } else {
            $this->json(['success' => false, 'message' => 'Failed to delete media'], 500);
        }
    }
    
    /**
     * Get media details
     */
    public function view($id)
    {
        $media = $this->mediaModel->query(
            "SELECT m.*, u.full_name as uploader_name
             FROM media m
             LEFT JOIN users u ON m.uploaded_by = u.id
             WHERE m.id = :id",
            [':id' => $id]
        )->fetch();
        
        if (!$media) {
            $this->json(['success' => false, 'message' => 'Not found'], 404);
        }
        
        $this->json(['success' => true, 'media' => $media]);
    }
}
