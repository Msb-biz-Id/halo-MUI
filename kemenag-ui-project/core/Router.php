<?php

namespace Core;

/**
 * Router Class
 * Handle all routing logic
 */
class Router
{
    protected $routes = [];
    protected $params = [];
    
    /**
     * Add a route
     */
    public function add($route, $params = [])
    {
        // Convert route to regex
        $route = preg_replace('/\//', '\\/', $route);
        $route = preg_replace('/\{([a-z]+)\}/', '(?P<\1>[a-z0-9-]+)', $route);
        $route = preg_replace('/\{([a-z]+):([^\}]+)\}/', '(?P<\1>\2)', $route);
        
        $route = '/^' . $route . '$/i';
        
        $this->routes[$route] = $params;
    }
    
    /**
     * Match route
     */
    public function match($url)
    {
        // Remove query string
        $url = strtok($url, '?');
        
        // Remove leading slash
        $url = ltrim($url, '/');
        
        foreach ($this->routes as $route => $params) {
            if (preg_match($route, $url, $matches)) {
                // Get named capture groups
                foreach ($matches as $key => $match) {
                    if (is_string($key)) {
                        $params[$key] = $match;
                    }
                }
                
                $this->params = $params;
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Get parameters
     */
    public function getParams()
    {
        return $this->params;
    }
    
    /**
     * Dispatch request
     */
    public function dispatch($url)
    {
        $url = $this->removeQueryString($url);
        
        if ($this->match($url)) {
            $controller = $this->params['controller'];
            $controller = $this->convertToStudlyCaps($controller);
            
            $namespace = isset($this->params['namespace']) ? 'App\\Controllers\\' . $this->params['namespace'] . '\\' : 'App\\Controllers\\';
            
            $controller = $namespace . $controller;
            
            if (class_exists($controller)) {
                $controllerObject = new $controller();
                
                $action = $this->params['action'];
                $action = $this->convertToCamelCase($action);
                
                if (method_exists($controllerObject, $action)) {
                    // Pass URL parameters to action
                    $args = [];
                    foreach ($this->params as $key => $value) {
                        if (!in_array($key, ['controller', 'action', 'namespace'])) {
                            $args[] = $value;
                        }
                    }
                    
                    call_user_func_array([$controllerObject, $action], $args);
                } else {
                    throw new \Exception("Method {$action} not found in controller {$controller}");
                }
            } else {
                throw new \Exception("Controller class {$controller} not found");
            }
        } else {
            http_response_code(404);
            include __DIR__ . '/../app/views/errors/404.php';
        }
    }
    
    /**
     * Convert string to StudlyCaps
     */
    protected function convertToStudlyCaps($string)
    {
        return str_replace(' ', '', ucwords(str_replace('-', ' ', $string)));
    }
    
    /**
     * Convert string to camelCase
     */
    protected function convertToCamelCase($string)
    {
        return lcfirst($this->convertToStudlyCaps($string));
    }
    
    /**
     * Remove query string from URL
     */
    protected function removeQueryString($url)
    {
        if ($url != '') {
            $parts = explode('?', $url, 2);
            
            if (strpos($parts[0], '=') === false) {
                $url = $parts[0];
            } else {
                $url = '';
            }
        }
        
        return $url;
    }
}
