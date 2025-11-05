<?php
/**
 * Application Configuration
 * Main configuration file for Sultan Library PHP application
 */

// Environment - Read from environment variables if set (for production)
define('APP_ENV', getenv('APP_ENV') ?: 'development');
define('APP_DEBUG', filter_var(getenv('APP_DEBUG') ?: 'true', FILTER_VALIDATE_BOOLEAN));

// Paths
define('ROOT_PATH', dirname(__DIR__, 2));
define('PUBLIC_PATH', ROOT_PATH . '/public');
define('SRC_PATH', ROOT_PATH . '/src');
define('VIEWS_PATH', SRC_PATH . '/views');

// URLs - Read from environment variables if set (for production)
define('BASE_URL', getenv('BASE_URL') ?: '/php-app/public/');
define('ASSETS_URL', getenv('ASSETS_URL') ?: (BASE_URL . 'images/'));
define('UPLOADS_URL', getenv('UPLOADS_URL') ?: (BASE_URL . 'uploads/'));

// Database Configuration
// Read from environment variables if set (for production on Render)
// Otherwise use local development defaults
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_NAME', getenv('DB_NAME') ?: 'shop');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');
define('DB_PORT', getenv('DB_PORT') ?: '');
define('DB_CHARSET', 'utf8mb4');

// Session Configuration
define('SESSION_LIFETIME', 2592000); // 30 days in seconds
define('SESSION_NAME', 'SULTAN_SESSION');

// Security
define('ADMIN_EMAIL', 'test@sultanlibrary.com'); // Admin email for admin access
define('CSRF_TOKEN_NAME', 'csrf_token');

// File Uploads
define('UPLOAD_DIR', PUBLIC_PATH . '/uploads');
define('UPLOAD_MAX_SIZE', 5242880); // 5MB
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/webp']);

// Cart
define('FREE_SHIPPING_THRESHOLD', 100); // Free shipping above €100
define('DEFAULT_SHIPPING_COST', 9.99);

// Pagination
define('PRODUCTS_PER_PAGE', 16);

// Timezone - Read from environment variable if set
date_default_timezone_set(getenv('TIMEZONE') ?: 'Europe/Paris');

// Error Reporting
if (APP_DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}


