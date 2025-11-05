<?php
/**
 * Base Controller
 * Common functionality for all controllers
 */

require_once __DIR__ . '/../lib/Auth.php';
require_once __DIR__ . '/../lib/Helpers.php';

class BaseController {
    /**
     * Render a view
     */
    protected function view($viewPath, $data = []) {
        extract($data);
        
        // Start output buffering
        ob_start();
        
        // Include the view file
        $viewFile = __DIR__ . '/../views/' . $viewPath . '.php';
        if (!file_exists($viewFile)) {
            throw new Exception("View not found: $viewPath");
        }
        
        require $viewFile;
        
        // Get the content
        $content = ob_get_clean();
        
        // Render with layout
        $layout = $data['layout'] ?? 'main';
        $layoutFile = __DIR__ . '/../views/layouts/' . $layout . '.php';
        
        if (file_exists($layoutFile)) {
            $content = $content; // Make content available to layout
            require $layoutFile;
        } else {
            echo $content;
        }
    }

    /**
     * Return JSON response
     */
    protected function json($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }

    /**
     * Redirect
     */
    protected function redirect($url, $statusCode = 302) {
        redirect($url, $statusCode);
    }
}


