<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\User;
use App\Models\Role;
use App\Services\EmailService;
use App\Services\MFAService;

/**
 * Auth Controller
 * Handles authentication (login, register, forgot password, MFA)
 */
class AuthController extends Controller
{
    private $userModel;
    private $roleModel;
    private $emailService;
    private $mfaService;
    
    public function __construct()
    {
        parent::__construct();
        $this->userModel = $this->model('User');
        $this->roleModel = $this->model('Role');
        $this->emailService = new EmailService();
        $this->mfaService = new MFAService();
    }
    
    /**
     * Login page
     */
    public function login()
    {
        // Redirect if already logged in
        if ($this->isLoggedIn()) {
            $this->redirect('/dashboard');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->processLogin();
        } else {
            $this->view('frontend/auth/login', [
                'csrf_token' => $this->generateCsrf()
            ]);
        }
    }
    
    /**
     * Process login
     */
    private function processLogin()
    {
        // Verify Turnstile first (anti-bot & brute-force protection)
        if (!turnstile_verify()) {
            $this->setFlash('error', turnstile_error() ?? 'Security verification failed. Please try again.');
            $this->redirect('/auth/login');
        }
        
        // Verify CSRF
        if (!$this->verifyCsrf()) {
            $this->setFlash('error', 'Invalid request');
            $this->redirect('/auth/login');
        }
        
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        $remember = isset($_POST['remember']);
        
        // Rate limiting
        if (!check_rate_limit('login_' . $username, 5, 15)) {
            $this->setFlash('error', 'Too many login attempts. Please try again later.');
            $this->redirect('/auth/login');
        }
        
        // Verify credentials
        $user = $this->userModel->verifyLogin($username, $password);
        
        if (!$user) {
            $this->setFlash('error', 'Invalid username or password');
            $this->redirect('/auth/login');
        }
        
        // Check if email is verified
        if (!$user['email_verified']) {
            $this->setFlash('error', 'Please verify your email first');
            $this->redirect('/auth/login');
        }
        
        // Check if MFA is enabled
        if ($user['mfa_enabled']) {
            $_SESSION['mfa_user_id'] = $user['id'];
            $this->redirect('/auth/mfa');
            return;
        }
        
        // Set session
        $this->setUserSession($user);
        
        // Clear rate limit
        clear_rate_limit('login_' . $username);
        
        // Audit log
        log_audit($user['id'], 'login', 'users', $user['id']);
        
        // Redirect based on role
        if ($user['role_id'] == 1) { // Superadmin
            $this->redirect('/admin/dashboard');
        } else {
            $this->redirect('/dashboard');
        }
    }
    
    /**
     * MFA verification page
     */
    public function mfa()
    {
        if (!isset($_SESSION['mfa_user_id'])) {
            $this->redirect('/auth/login');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->processMFA();
        } else {
            $this->view('frontend/auth/mfa', [
                'csrf_token' => $this->generateCsrf()
            ]);
        }
    }
    
    /**
     * Process MFA verification
     */
    private function processMFA()
    {
        if (!$this->verifyCsrf()) {
            $this->setFlash('error', 'Invalid request');
            $this->redirect('/auth/mfa');
        }
        
        $userId = $_SESSION['mfa_user_id'];
        $code = $_POST['code'] ?? '';
        
        $user = $this->userModel->findById($userId);
        
        if (!$user || !$user['mfa_enabled']) {
            $this->redirect('/auth/login');
        }
        
        // Verify MFA code
        if ($this->mfaService->verifyCode($user['mfa_secret'], $code)) {
            unset($_SESSION['mfa_user_id']);
            $this->setUserSession($user);
            
            log_audit($user['id'], 'login_mfa', 'users', $user['id']);
            
            $this->redirect('/dashboard');
        } else {
            $this->setFlash('error', 'Invalid MFA code');
            $this->redirect('/auth/mfa');
        }
    }
    
    /**
     * Register page
     */
    public function register()
    {
        if ($this->isLoggedIn()) {
            $this->redirect('/dashboard');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->processRegister();
        } else {
            $this->view('frontend/auth/register', [
                'csrf_token' => $this->generateCsrf()
            ]);
        }
    }
    
    /**
     * Process registration
     */
    private function processRegister()
    {
        // Verify Turnstile (anti-bot)
        if (!turnstile_verify()) {
            $this->setFlash('error', turnstile_error() ?? 'Security verification failed. Please try again.');
            $this->redirect('/auth/register');
        }
        
        if (!$this->verifyCsrf()) {
            $this->setFlash('error', 'Invalid request');
            $this->redirect('/auth/register');
        }
        
        // Validate input
        $username = $_POST['username'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        $fullName = $_POST['full_name'] ?? '';
        
        // Validation
        if (empty($username) || empty($email) || empty($password)) {
            $this->setFlash('error', 'All fields are required');
            flashOldInput();
            $this->redirect('/auth/register');
        }
        
        if (!is_email($email)) {
            $this->setFlash('error', 'Invalid email address');
            flashOldInput();
            $this->redirect('/auth/register');
        }
        
        if ($password !== $confirmPassword) {
            $this->setFlash('error', 'Passwords do not match');
            flashOldInput();
            $this->redirect('/auth/register');
        }
        
        if (strlen($password) < 8) {
            $this->setFlash('error', 'Password must be at least 8 characters');
            flashOldInput();
            $this->redirect('/auth/register');
        }
        
        // Check if username exists
        if ($this->userModel->getUserByUsername($username)) {
            $this->setFlash('error', 'Username already exists');
            flashOldInput();
            $this->redirect('/auth/register');
        }
        
        // Check if email exists
        if ($this->userModel->getUserByEmail($email)) {
            $this->setFlash('error', 'Email already exists');
            flashOldInput();
            $this->redirect('/auth/register');
        }
        
        // Get user role ID
        $userRole = $this->roleModel->getByName('user');
        
        // Create user
        $userId = $this->userModel->createUser([
            'username' => $username,
            'email' => $email,
            'password' => $password,
            'full_name' => $fullName,
            'role_id' => $userRole['id'],
            'is_active' => 1,
            'email_verified' => 0
        ]);
        
        if ($userId) {
            // Send verification email
            $token = bin2hex(random_bytes(32));
            // Store token in database (you need to create password_resets table entry)
            
            $this->emailService->sendVerificationEmail($email, $token);
            
            log_audit($userId, 'register', 'users', $userId);
            
            $this->setFlash('success', 'Registration successful! Please check your email for verification link.');
            $this->redirect('/auth/login');
        } else {
            $this->setFlash('error', 'Registration failed. Please try again.');
            $this->redirect('/auth/register');
        }
    }
    
    /**
     * Forgot password page
     */
    public function forgotPassword()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->processForgotPassword();
        } else {
            $this->view('frontend/auth/forgot_password', [
                'csrf_token' => $this->generateCsrf()
            ]);
        }
    }
    
    /**
     * Process forgot password
     */
    private function processForgotPassword()
    {
        // Verify Turnstile (prevent brute-force on password reset)
        if (!turnstile_verify()) {
            $this->setFlash('error', turnstile_error() ?? 'Security verification failed. Please try again.');
            $this->redirect('/auth/forgot-password');
        }
        
        if (!$this->verifyCsrf()) {
            $this->setFlash('error', 'Invalid request');
            $this->redirect('/auth/forgot-password');
        }
        
        $email = $_POST['email'] ?? '';
        
        if (empty($email)) {
            $this->setFlash('error', 'Email is required');
            $this->redirect('/auth/forgot-password');
        }
        
        $user = $this->userModel->getUserByEmail($email);
        
        if ($user) {
            // Generate reset token
            $token = bin2hex(random_bytes(32));
            // Store token in database
            
            // Send reset email
            $this->emailService->sendPasswordResetEmail($email, $token);
            
            log_audit($user['id'], 'password_reset_request', 'users', $user['id']);
        }
        
        // Always show success message (security best practice)
        $this->setFlash('success', 'If your email exists, you will receive a password reset link.');
        $this->redirect('/auth/login');
    }
    
    /**
     * Logout
     */
    public function logout()
    {
        if ($this->isLoggedIn()) {
            log_audit($_SESSION['user_id'], 'logout', 'users', $_SESSION['user_id']);
        }
        
        session_destroy();
        $this->redirect('/');
    }
    
    /**
     * Set user session
     * 
     * @param array $user
     */
    private function setUserSession($user)
    {
        $userWithRole = $this->userModel->getUserWithRole($user['id']);
        
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['full_name'] = $user['full_name'];
        $_SESSION['role_id'] = $user['role_id'];
        $_SESSION['role_name'] = $userWithRole['role_name'];
        $_SESSION['permissions'] = $userWithRole['permissions'];
        $_SESSION['logged_in'] = true;
    }
}
