<?php
/**
 * Front Controller
 * Routes all requests to appropriate controllers
 */

// Bootstrap
require_once __DIR__ . '/../src/config/config.php';
require_once __DIR__ . '/../src/config/database.php';
require_once __DIR__ . '/../src/lib/Helpers.php';
require_once __DIR__ . '/../src/lib/Auth.php';
require_once __DIR__ . '/../src/lib/CSRF.php';
require_once __DIR__ . '/../src/lib/Navigation.php';

// Start session
Auth::startSession();

/**
 * Simple Router with Named Routes
 */
class Router {
    private $routes = [];
    private $namedRoutes = [];

    public function add($method, $pattern, $handler, $name = null) {
        $route = [
            'method' => $method,
            'pattern' => $pattern,
            'handler' => $handler
        ];
        $this->routes[] = $route;
        
        if ($name) {
            $this->namedRoutes[$name] = $route;
        }
    }

    public function get($pattern, $handler, $name = null) {
        $this->add('GET', $pattern, $handler, $name);
    }

    public function post($pattern, $handler, $name = null) {
        $this->add('POST', $pattern, $handler, $name);
    }

    /**
     * Get URL for a named route
     */
    public function url($name, $params = []) {
        if (!isset($this->namedRoutes[$name])) {
            return '#';
        }
        
        $pattern = $this->namedRoutes[$name]['pattern'];
        
        // Replace route parameters
        foreach ($params as $key => $value) {
            $pattern = str_replace('{' . $key . '}', $value, $pattern);
        }
        
        return baseUrl(ltrim($pattern, '/'));
    }

    /**
     * Get current route name
     */
    public function currentRouteName() {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $base = rtrim(BASE_URL, '/');
        $path = str_replace($base, '', $path);
        $path = '/' . ltrim($path, '/');

        foreach ($this->namedRoutes as $name => $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            $pattern = '#^' . preg_replace('/\{([^}]+)\}/', '([^/]+)', $route['pattern']) . '$#';
            
            if (preg_match($pattern, $path)) {
                return $name;
            }
        }
        
        return null;
    }

    /**
     * Get route parameter from current URL
     */
    public function getRouteParam($paramName) {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $base = rtrim(BASE_URL, '/');
        $path = str_replace($base, '', $path);
        $path = '/' . ltrim($path, '/');

        foreach ($this->namedRoutes as $name => $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            $pattern = '#^' . preg_replace('/\{([^}]+)\}/', '([^/]+)', $route['pattern']) . '$#';
            
            if (preg_match($pattern, $path, $matches)) {
                // Find parameter position in pattern
                preg_match_all('/\{([^}]+)\}/', $route['pattern'], $paramNames);
                $paramNames = $paramNames[1];
                
                $paramIndex = array_search($paramName, $paramNames);
                if ($paramIndex !== false && isset($matches[$paramIndex + 1])) {
                    return $matches[$paramIndex + 1];
                }
            }
        }
        
        return null;
    }

    public function dispatch() {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $base = rtrim(BASE_URL, '/');
        $path = str_replace($base, '', $path);
        $path = '/' . ltrim($path, '/');

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            $pattern = '#^' . preg_replace('/\{([^}]+)\}/', '([^/]+)', $route['pattern']) . '$#';
            
            if (preg_match($pattern, $path, $matches)) {
                array_shift($matches); // Remove full match
                
                if (is_string($route['handler'])) {
                    // Format: "Controller@method"
                    if (strpos($route['handler'], '@') !== false) {
                        list($controller, $method) = explode('@', $route['handler']);
                        $controllerFile = __DIR__ . '/../src/controllers/' . $controller . '.php';
                        
                        if (file_exists($controllerFile)) {
                            require_once $controllerFile;
                            $controllerClass = $controller;
                            if (class_exists($controllerClass) && method_exists($controllerClass, $method)) {
                                $controllerInstance = new $controllerClass();
                                call_user_func_array([$controllerInstance, $method], $matches);
                                return;
                            }
                        }
                    }
                } elseif (is_callable($route['handler'])) {
                    call_user_func_array($route['handler'], $matches);
                    return;
                }
            }
        }

        // 404 Not Found
        http_response_code(404);
        require_once __DIR__ . '/../src/views/errors/404.php';
    }
}

// Initialize router
$router = new Router();

// Load route definitions
require_once __DIR__ . '/../src/routes.php';

// Set router for Navigation helper
Navigation::setRouter($router);

// Dispatch
$router->dispatch();

