<?php
/**
 * Route Testing Tool
 * Use this file to debug routing issues in XAMPP
 * Access at: http://localhost/php-app/public/test-routes.php
 */

require_once __DIR__ . '/../src/config/config.php';
require_once __DIR__ . '/../src/config/database.php';
require_once __DIR__ . '/../src/lib/Helpers.php';
require_once __DIR__ . '/../src/lib/Navigation.php';

// Include Router class
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

    public function url($name, $params = []) {
        if (!isset($this->namedRoutes[$name])) {
            return '#';
        }
        
        $pattern = $this->namedRoutes[$name]['pattern'];
        
        foreach ($params as $key => $value) {
            $pattern = str_replace('{' . $key . '}', $value, $pattern);
        }
        
        return baseUrl(ltrim($pattern, '/'));
    }

    public function getNamedRoutes() {
        return $this->namedRoutes;
    }
}

$router = new Router();
require_once __DIR__ . '/../src/routes.php';
Navigation::setRouter($router);

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Route Testing Tool</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        h1 {
            color: #56474A;
            border-bottom: 2px solid #56474A;
            padding-bottom: 10px;
        }
        h2 {
            color: #56474A;
            margin-top: 0;
        }
        .info {
            background: #e8f4f8;
            padding: 15px;
            border-radius: 4px;
            margin: 10px 0;
        }
        .success {
            background: #d4edda;
            padding: 15px;
            border-radius: 4px;
            margin: 10px 0;
            color: #155724;
        }
        .error {
            background: #f8d7da;
            padding: 15px;
            border-radius: 4px;
            margin: 10px 0;
            color: #721c24;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background: #56474A;
            color: white;
        }
        tr:hover {
            background: #f5f5f5;
        }
        code {
            background: #f4f4f4;
            padding: 2px 6px;
            border-radius: 3px;
            font-family: monospace;
        }
        .test-link {
            display: inline-block;
            margin: 5px;
            padding: 8px 15px;
            background: #56474A;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }
        .test-link:hover {
            background: #E8CFC4;
            color: #56474A;
        }
    </style>
</head>
<body>
    <h1>🔍 Route Testing Tool</h1>
    
    <div class="container">
        <h2>Current URL Information</h2>
        <div class="info">
            <strong>REQUEST_URI:</strong> <code><?= htmlspecialchars($_SERVER['REQUEST_URI'] ?? 'N/A') ?></code><br>
            <strong>REQUEST_METHOD:</strong> <code><?= htmlspecialchars($_SERVER['REQUEST_METHOD'] ?? 'N/A') ?></code><br>
            <strong>BASE_URL:</strong> <code><?= BASE_URL ?></code><br>
            <strong>PHP Version:</strong> <code><?= PHP_VERSION ?></code><br>
        </div>
    </div>

    <div class="container">
        <h2>Route Helper Tests</h2>
        <div class="info">
            <strong>route('home'):</strong> <code><?= route('home') ?></code><br>
            <strong>route('products.index'):</strong> <code><?= route('products.index') ?></code><br>
            <strong>route('product.show', ['slug' => 'test']):</strong> <code><?= route('product.show', ['slug' => 'test']) ?></code><br>
            <strong>route('category.show', ['slug' => 'livres']):</strong> <code><?= route('category.show', ['slug' => 'livres']) ?></code><br>
            <strong>route('cart.index'):</strong> <code><?= route('cart.index') ?></code><br>
            <strong>route('auth.login'):</strong> <code><?= route('auth.login') ?></code><br>
        </div>
    </div>

    <div class="container">
        <h2>Quick Test Links</h2>
        <p>Click these links to test if routes are working:</p>
        <a href="<?= route('home') ?>" class="test-link">Home</a>
        <a href="<?= route('products.index') ?>" class="test-link">Products</a>
        <a href="<?= route('search.index') ?>?query=test" class="test-link">Search</a>
        <a href="<?= route('auth.login') ?>" class="test-link">Login</a>
        <a href="<?= route('cart.index') ?>" class="test-link">Cart</a>
        <a href="<?= route('page.faq') ?>" class="test-link">FAQ</a>
    </div>

    <div class="container">
        <h2>All Named Routes (<?= count($router->getNamedRoutes()) ?>)</h2>
        <table>
            <thead>
                <tr>
                    <th>Route Name</th>
                    <th>Method</th>
                    <th>Pattern</th>
                    <th>Generated URL</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $routes = $router->getNamedRoutes();
                ksort($routes);
                foreach ($routes as $name => $route): 
                    $exampleUrl = $router->url($name);
                    // Try to generate example with parameters if route has them
                    if (strpos($route['pattern'], '{') !== false) {
                        preg_match_all('/\{([^}]+)\}/', $route['pattern'], $matches);
                        $params = [];
                        foreach ($matches[1] as $param) {
                            $params[$param] = 'example-' . $param;
                        }
                        $exampleUrl = $router->url($name, $params);
                    }
                ?>
                <tr>
                    <td><code><?= htmlspecialchars($name) ?></code></td>
                    <td><code><?= htmlspecialchars($route['method']) ?></code></td>
                    <td><code><?= htmlspecialchars($route['pattern']) ?></code></td>
                    <td><a href="<?= $exampleUrl ?>" target="_blank"><?= htmlspecialchars($exampleUrl) ?></a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="container">
        <h2>System Checks</h2>
        <?php
        $checks = [];
        
        // Check mod_rewrite
        $checks['mod_rewrite'] = function_exists('apache_get_modules') 
            ? in_array('mod_rewrite', apache_get_modules())
            : 'Unknown (apache_get_modules not available)';
        
        // Check .htaccess file
        $checks['htaccess'] = file_exists(__DIR__ . '/.htaccess');
        
        // Check config.php
        $checks['config'] = defined('BASE_URL');
        
        // Check Navigation class
        $checks['navigation'] = class_exists('Navigation');
        
        // Check router
        $checks['router'] = class_exists('Router');
        
        foreach ($checks as $check => $result):
            $isOk = $result === true || $result === 'Enabled';
            $class = $isOk ? 'success' : 'error';
            $icon = $isOk ? '✓' : '✗';
        ?>
        <div class="<?= $class ?>">
            <strong><?= $icon ?> <?= ucfirst(str_replace('_', ' ', $check)) ?>:</strong> 
            <?= is_bool($result) ? ($result ? 'OK' : 'FAILED') : htmlspecialchars($result) ?>
        </div>
        <?php endforeach; ?>
    </div>

    <div class="container">
        <h2>Apache Configuration Checklist</h2>
        <p>Make sure you've completed these steps:</p>
        <ul>
            <li>✓ mod_rewrite is enabled in <code>httpd.conf</code></li>
            <li>✓ <code>AllowOverride All</code> is set for your directory</li>
            <li>✓ Apache has been restarted after changes</li>
            <li>✓ <code>.htaccess</code> file exists in <code>public/</code> folder</li>
            <li>✓ <code>BASE_URL</code> in config.php matches your setup</li>
        </ul>
    </div>
</body>
</html>

