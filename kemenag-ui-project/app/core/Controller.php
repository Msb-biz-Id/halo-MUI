<?php

namespace Core;

/**
 * Base Controller Class
 * All controllers extend this class
 */
class Controller
{
    protected $view;
    protected $model;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->checkSession();
    }
    
    /**
     * Load model
     * 
     * @param string $model
     * @return object
     */
    public function model($model)
    {
        $modelClass = "App\\Models\\{$model}";
        
        if (class_exists($modelClass)) {
            return new $modelClass();
        }
        
        throw new \Exception("Model {$model} not found");
    }
    
    /**
     * Load view
     * 
     * @param string $view
     * @param array $data
     * @return void
     */
    public function view($view, $data = [])
    {
        $viewFile = VIEW_PATH . '/' . $view . '.php';
        
        if (file_exists($viewFile)) {
            extract($data);
            require_once $viewFile;
        } else {
            throw new \Exception("View {$view} not found");
        }
    }
    
    /**
     * Return JSON response
     * 
     * @param mixed $data
     * @param int $statusCode
     * @return void
     */
    public function json($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }
    
    /**
     * Redirect to URL
     * 
     * @param string $url
     * @return void
     */
    public function redirect($url)
    {
        header("Location: " . APP_URL . $url);
        exit();
    }
    
    /**
     * Check if user is logged in
     * 
     * @return bool
     */
    protected function isLoggedIn()
    {
        return isset($_SESSION['user_id']);
    }
    
    /**
     * Check if user has specific role
     * 
     * @param string $role
     * @return bool
     */
    protected function hasRole($role)
    {
        return isset($_SESSION['role_name']) && $_SESSION['role_name'] === $role;
    }
    
    /**
     * Check if user has specific permission
     * 
     * @param string $permission
     * @return bool
     */
    protected function hasPermission($permission)
    {
        if (!isset($_SESSION['permissions'])) {
            return false;
        }
        
        $permissions = json_decode($_SESSION['permissions'], true);
        
        // Check in features array
        if (isset($permissions['features']) && in_array($permission, $permissions['features'])) {
            return true;
        }
        
        // Check in specific permission groups
        foreach ($permissions as $group => $items) {
            if (is_array($items)) {
                if (isset($items[$permission]) && $items[$permission] === true) {
                    return true;
                }
            }
        }
        
        return false;
    }
    
    /**
     * Require authentication
     * 
     * @return void
     */
    protected function requireAuth()
    {
        if (!$this->isLoggedIn()) {
            $this->redirect('/auth/login');
        }
    }
    
    /**
     * Require specific role
     * 
     * @param string $role
     * @return void
     */
    protected function requireRole($role)
    {
        $this->requireAuth();
        
        if (!$this->hasRole($role)) {
            $this->unauthorized();
        }
    }
    
    /**
     * Require specific permission
     * 
     * @param string $permission
     * @return void
     */
    protected function requirePermission($permission)
    {
        $this->requireAuth();
        
        if (!$this->hasPermission($permission)) {
            $this->unauthorized();
        }
    }
    
    /**
     * Display unauthorized error
     * 
     * @return void
     */
    protected function unauthorized()
    {
        http_response_code(403);
        $this->view('errors/403');
        exit();
    }
    
    /**
     * Verify CSRF token
     * 
     * @return bool
     */
    protected function verifyCsrf()
    {
        $token = $_POST[CSRF_TOKEN_NAME] ?? '';
        return hash_equals($_SESSION[CSRF_TOKEN_NAME] ?? '', $token);
    }
    
    /**
     * Generate CSRF token
     * 
     * @return string
     */
    protected function generateCsrf()
    {
        if (!isset($_SESSION[CSRF_TOKEN_NAME])) {
            $_SESSION[CSRF_TOKEN_NAME] = bin2hex(random_bytes(32));
        }
        return $_SESSION[CSRF_TOKEN_NAME];
    }
    
    /**
     * Check session
     * 
     * @return void
     */
    private function checkSession()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    /**
     * Set flash message
     * 
     * @param string $key
     * @param string $message
     * @return void
     */
    protected function setFlash($key, $message)
    {
        $_SESSION['flash'][$key] = $message;
    }
    
    /**
     * Get flash message
     * 
     * @param string $key
     * @return string|null
     */
    protected function getFlash($key)
    {
        if (isset($_SESSION['flash'][$key])) {
            $message = $_SESSION['flash'][$key];
            unset($_SESSION['flash'][$key]);
            return $message;
        }
        return null;
    }
}
