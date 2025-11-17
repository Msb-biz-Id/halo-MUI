<?php

namespace Core;

/**
 * View Class
 * Handles view rendering with layouts
 */
class View
{
    protected $data = [];
    protected $layout = null;
    
    /**
     * Set layout
     * 
     * @param string $layout
     * @return $this
     */
    public function setLayout($layout)
    {
        $this->layout = $layout;
        return $this;
    }
    
    /**
     * Set view data
     * 
     * @param array $data
     * @return $this
     */
    public function setData($data)
    {
        $this->data = array_merge($this->data, $data);
        return $this;
    }
    
    /**
     * Render view
     * 
     * @param string $view
     * @param array $data
     * @return void
     */
    public function render($view, $data = [])
    {
        $data = array_merge($this->data, $data);
        extract($data);
        
        $viewFile = VIEW_PATH . '/' . $view . '.php';
        
        if (!file_exists($viewFile)) {
            throw new \Exception("View file not found: {$viewFile}");
        }
        
        if ($this->layout) {
            $layoutFile = VIEW_PATH . '/layouts/' . $this->layout . '.php';
            
            if (!file_exists($layoutFile)) {
                throw new \Exception("Layout file not found: {$layoutFile}");
            }
            
            // Start output buffering for content
            ob_start();
            require $viewFile;
            $content = ob_get_clean();
            
            // Render layout with content
            require $layoutFile;
        } else {
            require $viewFile;
        }
    }
    
    /**
     * Render partial view
     * 
     * @param string $partial
     * @param array $data
     * @return void
     */
    public function partial($partial, $data = [])
    {
        extract($data);
        
        $partialFile = VIEW_PATH . '/partials/' . $partial . '.php';
        
        if (file_exists($partialFile)) {
            require $partialFile;
        } else {
            throw new \Exception("Partial view not found: {$partialFile}");
        }
    }
    
    /**
     * Include component
     * 
     * @param string $component
     * @param array $data
     * @return void
     */
    public function component($component, $data = [])
    {
        extract($data);
        
        $componentFile = VIEW_PATH . '/components/' . $component . '.php';
        
        if (file_exists($componentFile)) {
            require $componentFile;
        } else {
            throw new \Exception("Component not found: {$componentFile}");
        }
    }
    
    /**
     * Escape HTML
     * 
     * @param string $string
     * @return string
     */
    public function escape($string)
    {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }
    
    /**
     * Get asset URL
     * 
     * @param string $path
     * @return string
     */
    public function asset($path)
    {
        return APP_URL . '/assets/' . ltrim($path, '/');
    }
    
    /**
     * Get URL
     * 
     * @param string $path
     * @return string
     */
    public function url($path = '')
    {
        return APP_URL . '/' . ltrim($path, '/');
    }
}
