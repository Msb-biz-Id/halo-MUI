<?php

namespace App\Controllers\Admin;

use Core\Controller;
use App\Models\User;
use App\Models\Role;

/**
 * Admin User Controller
 * Manage all users in the system
 */
class UserController extends Controller
{
    private $userModel;
    private $roleModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->requireAuth();
        $this->requirePermission('user_management');
        
        $this->userModel = $this->model('User');
        $this->roleModel = $this->model('Role');
    }
    
    /**
     * List all users
     */
    public function index()
    {
        $search = $_GET['search'] ?? '';
        $roleFilter = $_GET['role'] ?? '';
        
        if (!empty($search)) {
            $users = $this->userModel->searchUsers($search);
        } elseif (!empty($roleFilter)) {
            $users = $this->userModel->getUsersByRole($roleFilter);
        } else {
            $users = $this->userModel->getAllWithRoles();
        }
        
        $roles = $this->roleModel->findAll();
        
        $data = [
            'page_title' => 'Manajemen Pengguna',
            'users' => $users,
            'roles' => $roles,
            'search' => $search,
            'role_filter' => $roleFilter
        ];
        
        $this->view('admin/users/index', $data);
    }
    
    /**
     * Create new user
     */
    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->processCreate();
        } else {
            $roles = $this->roleModel->findAll();
            
            $data = [
                'page_title' => 'Tambah Pengguna',
                'roles' => $roles,
                'csrf_token' => $this->generateCsrf()
            ];
            
            $this->view('admin/users/create', $data);
        }
    }
    
    /**
     * Process create user
     */
    private function processCreate()
    {
        if (!$this->verifyCsrf()) {
            $this->setFlash('error', 'Invalid request');
            $this->redirect('/admin/users/create');
        }
        
        $username = $_POST['username'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $fullName = $_POST['full_name'] ?? '';
        $roleId = $_POST['role_id'] ?? 0;
        $isActive = isset($_POST['is_active']) ? 1 : 0;
        
        // Validation
        if (empty($username) || empty($email) || empty($password) || empty($roleId)) {
            $this->setFlash('error', 'Semua field wajib diisi');
            flashOldInput();
            $this->redirect('/admin/users/create');
        }
        
        // Check existing
        if ($this->userModel->getUserByUsername($username)) {
            $this->setFlash('error', 'Username sudah digunakan');
            flashOldInput();
            $this->redirect('/admin/users/create');
        }
        
        if ($this->userModel->getUserByEmail($email)) {
            $this->setFlash('error', 'Email sudah digunakan');
            flashOldInput();
            $this->redirect('/admin/users/create');
        }
        
        // Create user
        $userId = $this->userModel->createUser([
            'username' => $username,
            'email' => $email,
            'password' => $password,
            'full_name' => $fullName,
            'role_id' => $roleId,
            'is_active' => $isActive,
            'email_verified' => 1 // Admin-created users are auto-verified
        ]);
        
        if ($userId) {
            log_audit($_SESSION['user_id'], 'create', 'users', $userId);
            $this->setFlash('success', 'Pengguna berhasil ditambahkan');
            $this->redirect('/admin/users');
        } else {
            $this->setFlash('error', 'Gagal menambahkan pengguna');
            $this->redirect('/admin/users/create');
        }
    }
    
    /**
     * Edit user
     */
    public function edit($id)
    {
        $user = $this->userModel->getUserWithRole($id);
        
        if (!$user) {
            $this->setFlash('error', 'Pengguna tidak ditemukan');
            $this->redirect('/admin/users');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->processEdit($id);
        } else {
            $roles = $this->roleModel->findAll();
            
            $data = [
                'page_title' => 'Edit Pengguna',
                'user' => $user,
                'roles' => $roles,
                'csrf_token' => $this->generateCsrf()
            ];
            
            $this->view('admin/users/edit', $data);
        }
    }
    
    /**
     * Process edit user
     */
    private function processEdit($id)
    {
        if (!$this->verifyCsrf()) {
            $this->setFlash('error', 'Invalid request');
            $this->redirect('/admin/users/edit/' . $id);
        }
        
        $updateData = [
            'email' => $_POST['email'] ?? '',
            'full_name' => $_POST['full_name'] ?? '',
            'role_id' => $_POST['role_id'] ?? 0,
            'is_active' => isset($_POST['is_active']) ? 1 : 0,
            'phone' => $_POST['phone'] ?? null
        ];
        
        // Update password if provided
        if (!empty($_POST['password'])) {
            $updateData['password'] = password_hash($_POST['password'], PASSWORD_BCRYPT);
        }
        
        if ($this->userModel->update($id, $updateData)) {
            log_audit($_SESSION['user_id'], 'update', 'users', $id);
            $this->setFlash('success', 'Pengguna berhasil diperbarui');
            $this->redirect('/admin/users');
        } else {
            $this->setFlash('error', 'Gagal memperbarui pengguna');
            $this->redirect('/admin/users/edit/' . $id);
        }
    }
    
    /**
     * Delete user
     */
    public function delete($id)
    {
        // Prevent deleting self
        if ($id == $_SESSION['user_id']) {
            $this->setFlash('error', 'Tidak dapat menghapus akun sendiri');
            $this->redirect('/admin/users');
        }
        
        // Prevent deleting superadmin
        $user = $this->userModel->findById($id);
        if ($user && $user['role_id'] == 1) {
            $this->setFlash('error', 'Tidak dapat menghapus superadmin');
            $this->redirect('/admin/users');
        }
        
        if ($this->userModel->delete($id)) {
            log_audit($_SESSION['user_id'], 'delete', 'users', $id);
            $this->setFlash('success', 'Pengguna berhasil dihapus');
        } else {
            $this->setFlash('error', 'Gagal menghapus pengguna');
        }
        
        $this->redirect('/admin/users');
    }
    
    /**
     * Reset user password
     */
    public function resetPassword($id)
    {
        $newPassword = bin2hex(random_bytes(8));
        
        if ($this->userModel->updatePassword($id, $newPassword)) {
            // TODO: Send email with new password
            
            log_audit($_SESSION['user_id'], 'reset_password', 'users', $id);
            $this->setFlash('success', 'Password berhasil direset: ' . $newPassword);
        } else {
            $this->setFlash('error', 'Gagal mereset password');
        }
        
        $this->redirect('/admin/users');
    }
}
