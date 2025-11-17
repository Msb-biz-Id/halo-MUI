<?php

namespace App\Controllers\Admin;

use Core\Controller;

/**
 * Redirect Manager Controller
 * Manage URL redirects for moved/deleted content
 */
class RedirectController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->requireAdmin();
    }
    
    /**
     * List all redirects
     */
    public function index()
    {
        $page = $_GET['page'] ?? 1;
        $perPage = 20;
        $offset = ($page - 1) * $perPage;
        
        $sql = "SELECT * FROM redirects ORDER BY created_at DESC LIMIT ? OFFSET ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$perPage, $offset]);
        $redirects = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        $countSql = "SELECT COUNT(*) as total FROM redirects";
        $total = $this->db->query($countSql)->fetch(\PDO::FETCH_ASSOC)['total'];
        
        $this->view('admin/redirect/index', [
            'title' => 'Redirect Manager',
            'redirects' => $redirects,
            'total' => $total,
            'page' => $page,
            'pages' => ceil($total / $perPage)
        ]);
    }
    
    /**
     * Create redirect form
     */
    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->store();
            return;
        }
        
        $this->view('admin/redirect/create', [
            'title' => 'Create Redirect'
        ]);
    }
    
    /**
     * Store new redirect
     */
    private function store()
    {
        $fromUrl = trim($_POST['from_url'] ?? '');
        $toUrl = trim($_POST['to_url'] ?? '');
        $type = $_POST['type'] ?? '301';
        $status = $_POST['status'] ?? 'active';
        
        // Validate
        if (empty($fromUrl) || empty($toUrl)) {
            $_SESSION['error'] = 'From URL and To URL are required';
            redirect('admin/redirect/create');
            return;
        }
        
        // Clean URLs
        $fromUrl = ltrim($fromUrl, '/');
        $toUrl = filter_var($toUrl, FILTER_VALIDATE_URL) ? $toUrl : '/' . ltrim($toUrl, '/');
        
        $sql = "INSERT INTO redirects (from_url, to_url, type, status, hits, created_at) 
                VALUES (?, ?, ?, ?, 0, NOW())";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$fromUrl, $toUrl, $type, $status]);
        
        $_SESSION['success'] = 'Redirect created successfully';
        redirect('admin/redirect');
    }
    
    /**
     * Edit redirect
     */
    public function edit($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->update($id);
            return;
        }
        
        $sql = "SELECT * FROM redirects WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $redirect = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        if (!$redirect) {
            $_SESSION['error'] = 'Redirect not found';
            redirect('admin/redirect');
            return;
        }
        
        $this->view('admin/redirect/edit', [
            'title' => 'Edit Redirect',
            'redirect' => $redirect
        ]);
    }
    
    /**
     * Update redirect
     */
    private function update($id)
    {
        $fromUrl = trim($_POST['from_url'] ?? '');
        $toUrl = trim($_POST['to_url'] ?? '');
        $type = $_POST['type'] ?? '301';
        $status = $_POST['status'] ?? 'active';
        
        $fromUrl = ltrim($fromUrl, '/');
        $toUrl = filter_var($toUrl, FILTER_VALIDATE_URL) ? $toUrl : '/' . ltrim($toUrl, '/');
        
        $sql = "UPDATE redirects SET from_url = ?, to_url = ?, type = ?, status = ?, updated_at = NOW() 
                WHERE id = ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$fromUrl, $toUrl, $type, $status, $id]);
        
        $_SESSION['success'] = 'Redirect updated successfully';
        redirect('admin/redirect');
    }
    
    /**
     * Delete redirect
     */
    public function delete($id)
    {
        $sql = "DELETE FROM redirects WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        
        $_SESSION['success'] = 'Redirect deleted successfully';
        redirect('admin/redirect');
    }
    
    /**
     * Toggle redirect status
     */
    public function toggle($id)
    {
        $sql = "UPDATE redirects SET status = CASE WHEN status = 'active' THEN 'inactive' ELSE 'active' END 
                WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        
        $this->json(['success' => true]);
    }
    
    /**
     * Bulk import redirects
     */
    public function import()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->view('admin/redirect/import', ['title' => 'Import Redirects']);
            return;
        }
        
        if (!isset($_FILES['file'])) {
            $_SESSION['error'] = 'No file uploaded';
            redirect('admin/redirect/import');
            return;
        }
        
        $file = $_FILES['file'];
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        
        if ($ext === 'csv') {
            $imported = $this->importFromCSV($file['tmp_name']);
        } else {
            $_SESSION['error'] = 'Only CSV files are supported';
            redirect('admin/redirect/import');
            return;
        }
        
        $_SESSION['success'] = "{$imported} redirects imported successfully";
        redirect('admin/redirect');
    }
    
    /**
     * Import from CSV
     */
    private function importFromCSV(string $filepath): int
    {
        $count = 0;
        $handle = fopen($filepath, 'r');
        
        // Skip header
        fgetcsv($handle);
        
        while (($data = fgetcsv($handle)) !== false) {
            if (count($data) < 2) continue;
            
            $fromUrl = ltrim(trim($data[0]), '/');
            $toUrl = trim($data[1]);
            $type = $data[2] ?? '301';
            
            if (empty($fromUrl) || empty($toUrl)) continue;
            
            $sql = "INSERT INTO redirects (from_url, to_url, type, status, hits, created_at) 
                    VALUES (?, ?, ?, 'active', 0, NOW())
                    ON DUPLICATE KEY UPDATE to_url = ?, type = ?";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$fromUrl, $toUrl, $type, $toUrl, $type]);
            
            $count++;
        }
        
        fclose($handle);
        return $count;
    }
}
