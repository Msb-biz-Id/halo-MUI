<?php

namespace Core;

/**
 * Base Controller Class
 * All controllers extend from this base class
 */
class Controller
{
    protected $db;
    
    public function __construct()
    {
        global $db;
        $this->db = $db;
    }
    
    /**
     * Load a model
     */
    protected function model($model)
    {
        $modelClass = "App\\Models\\{$model}";
        
        if (class_exists($modelClass)) {
            return new $modelClass($this->db);
        }
        
        throw new \Exception("Model {$model} not found");
    }
    
    /**
     * Load a view
     */
    protected function view($view, $data = [])
    {
        extract($data);
        
        // Determine layout based on context
        $layout = 'main';
        
        if (isset($_SESSION['user_id'])) {
            if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
                $layout = 'admin';
            } else {
                $layout = 'user_dashboard';
            }
        }
        
        // Check if view is admin
        if (strpos($view, 'admin/') === 0) {
            $layout = 'admin';
        }
        
        // Check if view is user dashboard
        if (strpos($view, 'user/') === 0) {
            $layout = 'user_dashboard';
        }
        
        // Check if view is frontend
        if (strpos($view, 'frontend/') === 0 || strpos($view, 'forum/') === 0) {
            $layout = 'main';
        }
        
        $viewFile = __DIR__ . '/../app/views/' . $view . '.php';
        
        if (file_exists($viewFile)) {
            ob_start();
            include $viewFile;
            $content = ob_get_clean();
            
            include __DIR__ . '/../app/views/layouts/' . $layout . '.php';
        } else {
            throw new \Exception("View {$view} not found");
        }
    }
    
    /**
     * Return JSON response
     */
    protected function json($data, $status = 200)
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    /**
     * Redirect to URL
     */
    protected function redirect($url)
    {
        header('Location: ' . url($url));
        exit;
    }
    
    /**
     * Check if user is authenticated
     */
    protected function requireAuth()
    {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/auth/login');
        }
    }
    
    /**
     * Check if user has permission
     */
    protected function requirePermission($permission)
    {
        if (!isset($_SESSION['permissions']) || !in_array($permission, $_SESSION['permissions'])) {
            http_response_code(403);
            die('Access Denied');
        }
    }
    
    /**
     * Check if user is admin
     */
    protected function requireAdmin()
    {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            http_response_code(403);
            die('Admin Access Required');
        }
    }
}
