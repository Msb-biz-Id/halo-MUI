<?php

namespace App\Controllers\Admin;

use Core\Controller;
use App\Models\WordBlacklist;

/**
 * Admin Word Blacklist Controller
 * Manage blacklisted words for content moderation
 * ONLY accessible by superadmin
 */
class WordBlacklistController extends Controller
{
    private $blacklistModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->requireAuth();
        $this->requireRole('superadmin'); // ONLY SUPERADMIN
        
        $this->blacklistModel = $this->model('WordBlacklist');
    }
    
    /**
     * List all blacklisted words
     */
    public function index()
    {
        $severity = $_GET['severity'] ?? 'all';
        $action = $_GET['action'] ?? 'all';
        $status = $_GET['status'] ?? 'all';
        $search = $_GET['search'] ?? '';
        
        $sql = "SELECT wb.*, u.full_name as creator_name
                FROM word_blacklist wb
                LEFT JOIN users u ON wb.created_by = u.id
                WHERE 1=1";
        
        $params = [];
        
        if ($severity !== 'all') {
            $sql .= " AND wb.severity = :severity";
            $params[':severity'] = $severity;
        }
        
        if ($action !== 'all') {
            $sql .= " AND wb.action = :action";
            $params[':action'] = $action;
        }
        
        if ($status === 'active') {
            $sql .= " AND wb.is_active = 1";
        } elseif ($status === 'inactive') {
            $sql .= " AND wb.is_active = 0";
        }
        
        if (!empty($search)) {
            $sql .= " AND (wb.word LIKE :search OR wb.description LIKE :search)";
            $params[':search'] = "%{$search}%";
        }
        
        $sql .= " ORDER BY 
                  CASE wb.severity 
                    WHEN 'critical' THEN 1
                    WHEN 'high' THEN 2
                    WHEN 'medium' THEN 3
                    WHEN 'low' THEN 4
                  END,
                  wb.word ASC";
        
        $words = $this->blacklistModel->query($sql, $params)->fetchAll();
        
        // Get statistics
        $stats = $this->blacklistModel->getDetectionStats();
        
        $data = [
            'page_title' => 'Manajemen Blacklist Kata',
            'words' => $words,
            'stats' => $stats,
            'filters' => [
                'severity' => $severity,
                'action' => $action,
                'status' => $status,
                'search' => $search
            ]
        ];
        
        $this->view('admin/blacklist/index', $data);
    }
    
    /**
     * Add new blacklisted word
     */
    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->processCreate();
        } else {
            $data = [
                'page_title' => 'Tambah Kata Blacklist',
                'csrf_token' => $this->generateCsrf()
            ];
            
            $this->view('admin/blacklist/create', $data);
        }
    }
    
    /**
     * Process create blacklisted word
     */
    private function processCreate()
    {
        if (!$this->verifyCsrf()) {
            $this->setFlash('error', 'Invalid request');
            $this->redirect('/admin/blacklist/create');
        }
        
        $word = trim($_POST['word'] ?? '');
        $type = $_POST['type'] ?? 'partial';
        $severity = $_POST['severity'] ?? 'medium';
        $action = $_POST['action'] ?? 'block';
        $description = $_POST['description'] ?? '';
        
        // Validation
        if (empty($word)) {
            $this->setFlash('error', 'Kata wajib diisi');
            flashOldInput();
            $this->redirect('/admin/blacklist/create');
        }
        
        // Check if word already exists
        $existing = $this->blacklistModel->findBy(['word' => $word]);
        if (!empty($existing)) {
            $this->setFlash('error', 'Kata sudah ada dalam blacklist');
            flashOldInput();
            $this->redirect('/admin/blacklist/create');
        }
        
        $wordId = $this->blacklistModel->addWord([
            'word' => $word,
            'type' => $type,
            'severity' => $severity,
            'action' => $action,
            'description' => $description,
            'created_by' => $_SESSION['user_id']
        ]);
        
        if ($wordId) {
            log_audit($_SESSION['user_id'], 'create', 'word_blacklist', $wordId);
            $this->setFlash('success', 'Kata berhasil ditambahkan ke blacklist');
            $this->redirect('/admin/blacklist');
        } else {
            $this->setFlash('error', 'Gagal menambahkan kata');
            $this->redirect('/admin/blacklist/create');
        }
    }
    
    /**
     * Edit blacklisted word
     */
    public function edit($id)
    {
        $word = $this->blacklistModel->findById($id);
        
        if (!$word) {
            $this->setFlash('error', 'Kata tidak ditemukan');
            $this->redirect('/admin/blacklist');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->processEdit($id);
        } else {
            $data = [
                'page_title' => 'Edit Kata Blacklist',
                'word' => $word,
                'csrf_token' => $this->generateCsrf()
            ];
            
            $this->view('admin/blacklist/edit', $data);
        }
    }
    
    /**
     * Process edit blacklisted word
     */
    private function processEdit($id)
    {
        if (!$this->verifyCsrf()) {
            $this->setFlash('error', 'Invalid request');
            $this->redirect('/admin/blacklist/edit/' . $id);
        }
        
        $word = trim($_POST['word'] ?? '');
        $type = $_POST['type'] ?? 'partial';
        $severity = $_POST['severity'] ?? 'medium';
        $action = $_POST['action'] ?? 'block';
        $description = $_POST['description'] ?? '';
        $isActive = isset($_POST['is_active']) ? 1 : 0;
        
        if (empty($word)) {
            $this->setFlash('error', 'Kata wajib diisi');
            $this->redirect('/admin/blacklist/edit/' . $id);
        }
        
        if ($this->blacklistModel->updateWord($id, [
            'word' => $word,
            'type' => $type,
            'severity' => $severity,
            'action' => $action,
            'description' => $description,
            'is_active' => $isActive
        ])) {
            log_audit($_SESSION['user_id'], 'update', 'word_blacklist', $id);
            $this->setFlash('success', 'Kata berhasil diperbarui');
            $this->redirect('/admin/blacklist');
        } else {
            $this->setFlash('error', 'Gagal memperbarui kata');
            $this->redirect('/admin/blacklist/edit/' . $id);
        }
    }
    
    /**
     * Delete blacklisted word
     */
    public function delete($id)
    {
        if ($this->blacklistModel->delete($id)) {
            log_audit($_SESSION['user_id'], 'delete', 'word_blacklist', $id);
            $this->setFlash('success', 'Kata berhasil dihapus');
        } else {
            $this->setFlash('error', 'Gagal menghapus kata');
        }
        
        $this->redirect('/admin/blacklist');
    }
    
    /**
     * Toggle active status
     */
    public function toggleActive($id)
    {
        if ($this->blacklistModel->toggleActive($id)) {
            log_audit($_SESSION['user_id'], 'toggle_active', 'word_blacklist', $id);
            $this->json(['success' => true, 'message' => 'Status berhasil diubah']);
        } else {
            $this->json(['success' => false, 'message' => 'Gagal mengubah status'], 500);
        }
    }
    
    /**
     * Bulk add words (from textarea, one per line)
     */
    public function bulkAdd()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->processBulkAdd();
        } else {
            $data = [
                'page_title' => 'Bulk Add Blacklist',
                'csrf_token' => $this->generateCsrf()
            ];
            
            $this->view('admin/blacklist/bulk_add', $data);
        }
    }
    
    /**
     * Process bulk add
     */
    private function processBulkAdd()
    {
        if (!$this->verifyCsrf()) {
            $this->setFlash('error', 'Invalid request');
            $this->redirect('/admin/blacklist/bulk-add');
        }
        
        $wordsText = $_POST['words'] ?? '';
        $type = $_POST['type'] ?? 'partial';
        $severity = $_POST['severity'] ?? 'medium';
        $action = $_POST['action'] ?? 'block';
        
        $lines = explode("\n", $wordsText);
        $added = 0;
        $skipped = 0;
        
        foreach ($lines as $line) {
            $word = trim($line);
            
            if (empty($word)) continue;
            
            // Check if exists
            $existing = $this->blacklistModel->findBy(['word' => $word]);
            if (!empty($existing)) {
                $skipped++;
                continue;
            }
            
            $wordId = $this->blacklistModel->addWord([
                'word' => $word,
                'type' => $type,
                'severity' => $severity,
                'action' => $action,
                'description' => "Bulk added",
                'created_by' => $_SESSION['user_id']
            ]);
            
            if ($wordId) {
                $added++;
                log_audit($_SESSION['user_id'], 'bulk_add', 'word_blacklist', $wordId);
            }
        }
        
        $this->setFlash('success', "{$added} kata berhasil ditambahkan, {$skipped} dilewati (sudah ada)");
        $this->redirect('/admin/blacklist');
    }
    
    /**
     * View detection logs
     */
    public function detectionLogs()
    {
        $filters = [
            'user_id' => $_GET['user_id'] ?? '',
            'content_type' => $_GET['content_type'] ?? '',
            'action_taken' => $_GET['action_taken'] ?? ''
        ];
        
        $logs = $this->blacklistModel->getDetectionLogs($filters);
        
        // Decode detected_words JSON
        foreach ($logs as &$log) {
            if (!empty($log['detected_words'])) {
                $log['detected_words_array'] = json_decode($log['detected_words'], true);
            }
        }
        
        $stats = $this->blacklistModel->getDetectionStats();
        
        $data = [
            'page_title' => 'Log Deteksi Blacklist',
            'logs' => $logs,
            'stats' => $stats,
            'filters' => $filters
        ];
        
        $this->view('admin/blacklist/logs', $data);
    }
    
    /**
     * Test blacklist checker
     */
    public function test()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $content = $_POST['content'] ?? '';
            
            if (!empty($content)) {
                $result = $this->blacklistModel->checkContent($content);
                
                $data = [
                    'page_title' => 'Test Blacklist Checker',
                    'content' => $content,
                    'result' => $result,
                    'csrf_token' => $this->generateCsrf()
                ];
                
                $this->view('admin/blacklist/test', $data);
                return;
            }
        }
        
        $data = [
            'page_title' => 'Test Blacklist Checker',
            'csrf_token' => $this->generateCsrf()
        ];
        
        $this->view('admin/blacklist/test', $data);
    }
}
