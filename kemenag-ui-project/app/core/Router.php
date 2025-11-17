<?php

namespace Core;

/**
 * Router Class
 * Handles URL routing and dispatches requests to appropriate controllers
 */
class Router
{
    protected $routes = [];
    protected $params = [];
    protected $controller = 'HomeController';
    protected $method = 'index';
    
    /**
     * Add route to routes array
     * 
     * @param string $route
     * @param array $params
     * @return void
     */
    public function add($route, $params = [])
    {
        // Convert route to regex pattern
        $route = preg_replace('/\//', '\\/', $route);
        $route = preg_replace('/\{([a-z]+)\}/', '(?P<\1>[a-zA-Z0-9-]+)', $route);
        $route = '/^' . $route . '$/i';
        
        $this->routes[$route] = $params;
    }
    
    /**
     * Get all routes
     * 
     * @return array
     */
    public function getRoutes()
    {
        return $this->routes;
    }
    
    /**
     * Match URL to a route
     * 
     * @param string $url
     * @return bool
     */
    public function match($url)
    {
        foreach ($this->routes as $route => $params) {
            if (preg_match($route, $url, $matches)) {
                // Get named capture group values
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
     * Dispatch request to appropriate controller
     * 
     * @param string $url
     * @return void
     */
    public function dispatch($url)
    {
        // Remove query string
        $url = $this->removeQueryString($url);
        
        // Try to match route
        if ($this->match($url)) {
            // Check if controller exists
            $controller = $this->params['controller'];
            $controller = $this->convertToStudlyCaps($controller);
            
            // Check if it's an admin controller
            if (isset($this->params['namespace']) && $this->params['namespace'] === 'Admin') {
                $controller = "App\\Controllers\\Admin\\{$controller}";
            } else {
                $controller = "App\\Controllers\\{$controller}";
            }
            
            if (class_exists($controller)) {
                $controllerObject = new $controller($this->params);
                
                $action = $this->params['action'];
                $action = $this->convertToCamelCase($action);
                
                if (method_exists($controllerObject, $action)) {
                    $controllerObject->$action();
                } else {
                    $this->notFound("Method {$action} not found in controller {$controller}");
                }
            } else {
                $this->notFound("Controller {$controller} not found");
            }
        } else {
            // No route matched - try default routing
            $this->defaultDispatch($url);
        }
    }
    
    /**
     * Default dispatch when no route matches
     * 
     * @param string $url
     * @return void
     */
    protected function defaultDispatch($url)
    {
        $url = trim($url, '/');
        $url = filter_var($url, FILTER_SANITIZE_URL);
        
        if (empty($url)) {
            $url = 'home/index';
        }
        
        $parts = explode('/', $url);
        
        // Check if admin route
        if ($parts[0] === 'admin') {
            array_shift($parts);
            $this->controller = isset($parts[0]) ? $parts[0] . 'Controller' : 'DashboardController';
            $this->controller = "App\\Controllers\\Admin\\" . $this->convertToStudlyCaps($this->controller);
            
            if (isset($parts[1])) {
                $this->method = $this->convertToCamelCase($parts[1]);
                unset($parts[1]);
            }
            
            unset($parts[0]);
        } else {
            // Frontend route
            $this->controller = isset($parts[0]) ? $parts[0] . 'Controller' : 'HomeController';
            $this->controller = "App\\Controllers\\" . $this->convertToStudlyCaps($this->controller);
            
            if (isset($parts[1])) {
                $this->method = $this->convertToCamelCase($parts[1]);
                unset($parts[1]);
            }
            
            unset($parts[0]);
        }
        
        // Get remaining params
        $this->params = $parts ? array_values($parts) : [];
        
        // Check if controller exists
        if (class_exists($this->controller)) {
            $controllerObject = new $this->controller();
            
            // Check if method exists
            if (method_exists($controllerObject, $this->method)) {
                call_user_func_array([$controllerObject, $this->method], $this->params);
            } else {
                $this->notFound("Method {$this->method} not found");
            }
        } else {
            $this->notFound("Controller {$this->controller} not found");
        }
    }
    
    /**
     * Convert string to StudlyCaps format
     * 
     * @param string $string
     * @return string
     */
    protected function convertToStudlyCaps($string)
    {
        return str_replace(' ', '', ucwords(str_replace('-', ' ', $string)));
    }
    
    /**
     * Convert string to camelCase format
     * 
     * @param string $string
     * @return string
     */
    protected function convertToCamelCase($string)
    {
        return lcfirst($this->convertToStudlyCaps($string));
    }
    
    /**
     * Remove query string from URL
     * 
     * @param string $url
     * @return string
     */
    protected function removeQueryString($url)
    {
        if ($url != '') {
            $parts = explode('?', $url, 2);
            return $parts[0];
        }
        
        return $url;
    }
    
    /**
     * Display 404 error page
     * 
     * @param string $message
     * @return void
     */
    protected function notFound($message = 'Page not found')
    {
        header("HTTP/1.0 404 Not Found");
        
        if (APP_DEBUG) {
            echo "<h1>404 Not Found</h1>";
            echo "<p>{$message}</p>";
        } else {
            // Load 404 view
            if (file_exists(VIEW_PATH . '/errors/404.php')) {
                require VIEW_PATH . '/errors/404.php';
            } else {
                echo "<h1>404 Not Found</h1>";
            }
        }
        
        exit();
    }
}
