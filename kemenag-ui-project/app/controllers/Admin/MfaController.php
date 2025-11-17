<?php

namespace App\Controllers\Admin;

use Core\Controller;
use OTPHP\TOTP;

/**
 * Admin MFA Controller
 * Manage Multi-Factor Authentication
 */
class MfaController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->requireAuth();
        $this->requireAdmin();
    }
    
    /**
     * Setup MFA
     */
    public function setup()
    {
        $userModel = $this->model('User');
        $user = $userModel->findById($_SESSION['user_id']);
        
        if ($user['mfa_enabled']) {
            $this->redirect('/admin/dashboard');
            return;
        }
        
        // Generate TOTP secret
        $totp = TOTP::create();
        $secret = $totp->getSecret();
        
        // Store secret temporarily in session
        $_SESSION['mfa_temp_secret'] = $secret;
        
        // Generate QR code URL
        $totp->setLabel($user['email']);
        $totp->setIssuer('Kemenag Halal Certification');
        
        $qrCodeUrl = $totp->getQrCodeUri(
            'https://api.qrserver.com/v1/create-qr-code/',
            urlencode($totp->getProvisioningUri())
        );
        
        $data = [
            'page_title' => 'Setup Two-Factor Authentication',
            'secret' => $secret,
            'qr_code_url' => $qrCodeUrl,
            'user' => $user
        ];
        
        $this->view('admin/mfa/setup', $data);
    }
    
    /**
     * Enable MFA
     */
    public function enable()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/mfa/setup');
            return;
        }
        
        $code = $_POST['code'] ?? '';
        $secret = $_SESSION['mfa_temp_secret'] ?? '';
        
        if (!$secret) {
            $_SESSION['error'] = 'Invalid MFA setup. Please try again.';
            $this->redirect('/admin/mfa/setup');
            return;
        }
        
        // Verify TOTP code
        $totp = TOTP::create($secret);
        
        if (!$totp->verify($code)) {
            $_SESSION['error'] = 'Invalid verification code. Please try again.';
            $this->redirect('/admin/mfa/setup');
            return;
        }
        
        // Enable MFA for user
        $userModel = $this->model('User');
        $userModel->update($_SESSION['user_id'], [
            'mfa_enabled' => 1,
            'mfa_secret' => $secret
        ]);
        
        // Clear temp secret
        unset($_SESSION['mfa_temp_secret']);
        
        // Log activity
        $auditModel = $this->model('AuditLog');
        $auditModel->log([
            'user_id' => $_SESSION['user_id'],
            'action' => 'enable_mfa',
            'entity_type' => 'user',
            'entity_id' => $_SESSION['user_id'],
            'description' => 'Enabled two-factor authentication'
        ]);
        
        $_SESSION['success'] = 'Two-factor authentication enabled successfully!';
        $this->redirect('/admin/dashboard');
    }
    
    /**
     * Disable MFA
     */
    public function disable()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/dashboard');
            return;
        }
        
        // Verify password before disabling
        $password = $_POST['password'] ?? '';
        
        $userModel = $this->model('User');
        $user = $userModel->findById($_SESSION['user_id']);
        
        if (!password_verify($password, $user['password'])) {
            $_SESSION['error'] = 'Invalid password.';
            $this->redirect('/admin/profile/security');
            return;
        }
        
        // Disable MFA
        $userModel->update($_SESSION['user_id'], [
            'mfa_enabled' => 0,
            'mfa_secret' => null
        ]);
        
        // Log activity
        $auditModel = $this->model('AuditLog');
        $auditModel->log([
            'user_id' => $_SESSION['user_id'],
            'action' => 'disable_mfa',
            'entity_type' => 'user',
            'entity_id' => $_SESSION['user_id'],
            'description' => 'Disabled two-factor authentication'
        ]);
        
        $_SESSION['success'] = 'Two-factor authentication disabled.';
        $this->redirect('/admin/profile/security');
    }
}
