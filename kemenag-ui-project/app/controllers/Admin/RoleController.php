<?php

namespace App\Controllers\Admin;

use Core\Controller;
use App\Models\Role;

/**
 * Admin Role Controller
 * Manage roles and permissions
 */
class RoleController extends Controller
{
    private $roleModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->requireAuth();
        $this->requireRole('superadmin'); // Only superadmin can manage roles
        
        $this->roleModel = $this->model('Role');
    }
    
    /**
     * List all roles
     */
    public function index()
    {
        $roles = $this->roleModel->findAll();
        
        // Decode permissions for display
        foreach ($roles as &$role) {
            if (!empty($role['permissions'])) {
                $role['permissions_array'] = json_decode($role['permissions'], true);
            }
        }
        
        $data = [
            'page_title' => 'Manajemen Role',
            'roles' => $roles
        ];
        
        $this->view('admin/roles/index', $data);
    }
    
    /**
     * Create new role
     */
    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->processCreate();
        } else {
            $data = [
                'page_title' => 'Tambah Role',
                'csrf_token' => $this->generateCsrf(),
                'available_permissions' => $this->getAvailablePermissions()
            ];
            
            $this->view('admin/roles/create', $data);
        }
    }
    
    /**
     * Process create role
     */
    private function processCreate()
    {
        if (!$this->verifyCsrf()) {
            $this->setFlash('error', 'Invalid request');
            $this->redirect('/admin/roles/create');
        }
        
        $name = $_POST['name'] ?? '';
        $description = $_POST['description'] ?? '';
        $permissions = $_POST['permissions'] ?? [];
        
        if (empty($name)) {
            $this->setFlash('error', 'Nama role wajib diisi');
            $this->redirect('/admin/roles/create');
        }
        
        // Build permissions array
        $permissionsData = [
            'features' => $permissions['features'] ?? [],
            'certificate_permissions' => $permissions['certificate'] ?? [],
            'content_permissions' => $permissions['content'] ?? [],
            'forum_permissions' => $permissions['forum'] ?? []
        ];
        
        $roleId = $this->roleModel->createWithPermissions([
            'name' => $name,
            'description' => $description,
            'permissions' => $permissionsData
        ]);
        
        if ($roleId) {
            log_audit($_SESSION['user_id'], 'create', 'roles', $roleId);
            $this->setFlash('success', 'Role berhasil ditambahkan');
            $this->redirect('/admin/roles');
        } else {
            $this->setFlash('error', 'Gagal menambahkan role');
            $this->redirect('/admin/roles/create');
        }
    }
    
    /**
     * Edit role
     */
    public function edit($id)
    {
        $role = $this->roleModel->getRoleWithPermissions($id);
        
        if (!$role) {
            $this->setFlash('error', 'Role tidak ditemukan');
            $this->redirect('/admin/roles');
        }
        
        // Prevent editing superadmin role
        if ($role['name'] === 'superadmin') {
            $this->setFlash('error', 'Tidak dapat mengedit role superadmin');
            $this->redirect('/admin/roles');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->processEdit($id);
        } else {
            $data = [
                'page_title' => 'Edit Role',
                'role' => $role,
                'csrf_token' => $this->generateCsrf(),
                'available_permissions' => $this->getAvailablePermissions()
            ];
            
            $this->view('admin/roles/edit', $data);
        }
    }
    
    /**
     * Process edit role
     */
    private function processEdit($id)
    {
        if (!$this->verifyCsrf()) {
            $this->setFlash('error', 'Invalid request');
            $this->redirect('/admin/roles/edit/' . $id);
        }
        
        $description = $_POST['description'] ?? '';
        $permissions = $_POST['permissions'] ?? [];
        
        $permissionsData = [
            'features' => $permissions['features'] ?? [],
            'certificate_permissions' => $permissions['certificate'] ?? [],
            'content_permissions' => $permissions['content'] ?? [],
            'forum_permissions' => $permissions['forum'] ?? []
        ];
        
        if ($this->roleModel->updateWithPermissions($id, [
            'description' => $description,
            'permissions' => $permissionsData
        ])) {
            log_audit($_SESSION['user_id'], 'update', 'roles', $id);
            $this->setFlash('success', 'Role berhasil diperbarui');
            $this->redirect('/admin/roles');
        } else {
            $this->setFlash('error', 'Gagal memperbarui role');
            $this->redirect('/admin/roles/edit/' . $id);
        }
    }
    
    /**
     * Delete role
     */
    public function delete($id)
    {
        $role = $this->roleModel->findById($id);
        
        if (!$role) {
            $this->setFlash('error', 'Role tidak ditemukan');
            $this->redirect('/admin/roles');
        }
        
        // Prevent deleting default roles
        $protectedRoles = ['superadmin', 'admin_konten', 'admin_sertifikat', 'user'];
        if (in_array($role['name'], $protectedRoles)) {
            $this->setFlash('error', 'Tidak dapat menghapus role default');
            $this->redirect('/admin/roles');
        }
        
        // Check if role is being used
        $userModel = $this->model('User');
        $usersWithRole = $userModel->getUsersByRole($id);
        
        if (!empty($usersWithRole)) {
            $this->setFlash('error', 'Tidak dapat menghapus role yang sedang digunakan');
            $this->redirect('/admin/roles');
        }
        
        if ($this->roleModel->delete($id)) {
            log_audit($_SESSION['user_id'], 'delete', 'roles', $id);
            $this->setFlash('success', 'Role berhasil dihapus');
        } else {
            $this->setFlash('error', 'Gagal menghapus role');
        }
        
        $this->redirect('/admin/roles');
    }
    
    /**
     * Get available permissions
     */
    private function getAvailablePermissions()
    {
        return [
            'features' => [
                'help_desk_certificates' => 'Help Desk Sertifikat',
                'forum_access' => 'Akses Forum',
                'internal_messaging' => 'Pesan Internal',
                'notifications' => 'Notifikasi',
                'content_access' => 'Akses Konten',
                'user_management' => 'Manajemen User',
                'content_management' => 'Manajemen Konten',
                'certificate_management' => 'Manajemen Sertifikat',
                'media_management' => 'Manajemen Media',
                'system_settings' => 'Pengaturan Sistem'
            ],
            'certificate' => [
                'create_application' => 'Buat Pengajuan',
                'view_own_applications' => 'Lihat Pengajuan Sendiri',
                'view_all_applications' => 'Lihat Semua Pengajuan',
                'assign_applications' => 'Assign Pengajuan',
                'approve_applications' => 'Approve Pengajuan',
                'reject_applications' => 'Reject Pengajuan',
                'generate_certificate' => 'Generate Sertifikat',
                'download_certificates' => 'Download Sertifikat'
            ],
            'content' => [
                'create' => 'Buat Konten',
                'read' => 'Baca Konten',
                'update' => 'Update Konten',
                'delete' => 'Hapus Konten',
                'publish' => 'Publish Konten'
            ],
            'forum' => [
                'create_topics' => 'Buat Topik',
                'create_posts' => 'Buat Post',
                'moderate_own_content' => 'Moderasi Konten Sendiri',
                'moderate_all_content' => 'Moderasi Semua Konten',
                'delete_any_post' => 'Hapus Post Apapun',
                'lock_topics' => 'Lock Topik'
            ]
        ];
    }
}
