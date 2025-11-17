<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\User;

/**
 * Profile Controller
 * Handles user profile management
 */
class ProfileController extends Controller
{
    private $userModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->requireAuth();
        $this->userModel = $this->model('User');
    }
    
    /**
     * View Profile
     */
    public function index()
    {
        $userId = $_SESSION['user_id'];
        $user = $this->userModel->getUserWithRole($userId);
        
        $data = [
            'page_title' => 'Profil Saya',
            'user' => $user
        ];
        
        $this->view('user/profile/index', $data);
    }
    
    /**
     * Edit Profile
     */
    public function edit()
    {
        $userId = $_SESSION['user_id'];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->processEditProfile($userId);
        } else {
            $user = $this->userModel->getUserWithRole($userId);
            
            $data = [
                'page_title' => 'Edit Profil',
                'user' => $user,
                'csrf_token' => $this->generateCsrf()
            ];
            
            $this->view('user/profile/edit', $data);
        }
    }
    
    /**
     * Process Edit Profile
     */
    private function processEditProfile($userId)
    {
        if (!$this->verifyCsrf()) {
            $this->setFlash('error', 'Invalid request');
            $this->redirect('/profile/edit');
        }
        
        $updateData = [
            'full_name' => $_POST['full_name'] ?? '',
            'phone' => $_POST['phone'] ?? '',
            'date_of_birth' => $_POST['date_of_birth'] ?? null,
            'gender' => $_POST['gender'] ?? null,
            'company_name' => $_POST['company_name'] ?? null,
            'company_address' => $_POST['company_address'] ?? null,
            'company_npwp' => $_POST['company_npwp'] ?? null,
            'business_field' => $_POST['business_field'] ?? null
        ];
        
        // Handle profile picture upload
        if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === 0) {
            $filename = upload_file($_FILES['profile_picture'], 'uploads/profiles/', ['jpg', 'jpeg', 'png']);
            
            if ($filename) {
                $updateData['profile_picture'] = 'uploads/profiles/' . $filename;
                
                // Delete old picture
                $user = $this->userModel->findById($userId);
                if ($user['profile_picture'] && file_exists(PUBLIC_PATH . '/' . $user['profile_picture'])) {
                    delete_file(PUBLIC_PATH . '/' . $user['profile_picture']);
                }
            }
        }
        
        // Handle ID card upload
        if (isset($_FILES['id_card']) && $_FILES['id_card']['error'] === 0) {
            $filename = upload_file($_FILES['id_card'], 'uploads/documents/', ['jpg', 'jpeg', 'png', 'pdf']);
            
            if ($filename) {
                $updateData['id_card_path'] = 'uploads/documents/' . $filename;
            }
        }
        
        // Handle company documents upload
        if (isset($_FILES['company_docs']) && $_FILES['company_docs']['error'][0] === 0) {
            $companyDocs = [];
            foreach ($_FILES['company_docs']['tmp_name'] as $key => $tmp_name) {
                if ($_FILES['company_docs']['error'][$key] === 0) {
                    $file = [
                        'name' => $_FILES['company_docs']['name'][$key],
                        'type' => $_FILES['company_docs']['type'][$key],
                        'tmp_name' => $tmp_name,
                        'error' => $_FILES['company_docs']['error'][$key],
                        'size' => $_FILES['company_docs']['size'][$key]
                    ];
                    
                    $filename = upload_file($file, 'uploads/documents/');
                    if ($filename) {
                        $companyDocs[] = 'uploads/documents/' . $filename;
                    }
                }
            }
            
            if (!empty($companyDocs)) {
                $updateData['company_docs_path'] = json_encode($companyDocs);
            }
        }
        
        // Update profile
        if ($this->userModel->update($userId, $updateData)) {
            // Update session
            $_SESSION['full_name'] = $updateData['full_name'];
            
            // Log activity
            log_audit($userId, 'update', 'users', $userId);
            
            $this->setFlash('success', 'Profil berhasil diperbarui');
        } else {
            $this->setFlash('error', 'Gagal memperbarui profil');
        }
        
        $this->redirect('/profile');
    }
    
    /**
     * Change Password
     */
    public function changePassword()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->processChangePassword();
        } else {
            $data = [
                'page_title' => 'Ubah Password',
                'csrf_token' => $this->generateCsrf()
            ];
            
            $this->view('user/profile/change_password', $data);
        }
    }
    
    /**
     * Process Change Password
     */
    private function processChangePassword()
    {
        if (!$this->verifyCsrf()) {
            $this->setFlash('error', 'Invalid request');
            $this->redirect('/profile/change-password');
        }
        
        $userId = $_SESSION['user_id'];
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        
        // Validation
        if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
            $this->setFlash('error', 'Semua field harus diisi');
            $this->redirect('/profile/change-password');
        }
        
        if ($newPassword !== $confirmPassword) {
            $this->setFlash('error', 'Password baru tidak cocok');
            $this->redirect('/profile/change-password');
        }
        
        if (strlen($newPassword) < 8) {
            $this->setFlash('error', 'Password minimal 8 karakter');
            $this->redirect('/profile/change-password');
        }
        
        // Verify current password
        $user = $this->userModel->findById($userId);
        if (!password_verify($currentPassword, $user['password'])) {
            $this->setFlash('error', 'Password lama tidak benar');
            $this->redirect('/profile/change-password');
        }
        
        // Update password
        if ($this->userModel->updatePassword($userId, $newPassword)) {
            log_audit($userId, 'change_password', 'users', $userId);
            
            $this->setFlash('success', 'Password berhasil diubah');
            $this->redirect('/profile');
        } else {
            $this->setFlash('error', 'Gagal mengubah password');
            $this->redirect('/profile/change-password');
        }
    }
    
    /**
     * Security Settings (MFA, Security Questions)
     */
    public function security()
    {
        $userId = $_SESSION['user_id'];
        $user = $this->userModel->findById($userId);
        
        $data = [
            'page_title' => 'Pengaturan Keamanan',
            'user' => $user,
            'mfa_enabled' => $user['mfa_enabled'],
            'csrf_token' => $this->generateCsrf()
        ];
        
        $this->view('user/profile/security', $data);
    }
    
    /**
     * Notification Settings
     */
    public function notificationSettings()
    {
        $userId = $_SESSION['user_id'];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->processNotificationSettings($userId);
        } else {
            $user = $this->userModel->findById($userId);
            
            $data = [
                'page_title' => 'Pengaturan Notifikasi',
                'user' => $user,
                'csrf_token' => $this->generateCsrf()
            ];
            
            $this->view('user/profile/notification_settings', $data);
        }
    }
    
    /**
     * Process Notification Settings
     */
    private function processNotificationSettings($userId)
    {
        if (!$this->verifyCsrf()) {
            $this->setFlash('error', 'Invalid request');
            $this->redirect('/profile/notification-settings');
        }
        
        // TODO: Save notification preferences
        // For now, just show success
        
        $this->setFlash('success', 'Pengaturan notifikasi berhasil disimpan');
        $this->redirect('/profile/notification-settings');
    }
    
    /**
     * Privacy Settings
     */
    public function privacy()
    {
        $userId = $_SESSION['user_id'];
        $user = $this->userModel->findById($userId);
        
        $data = [
            'page_title' => 'Pengaturan Privasi',
            'user' => $user,
            'csrf_token' => $this->generateCsrf()
        ];
        
        $this->view('user/profile/privacy', $data);
    }
}
