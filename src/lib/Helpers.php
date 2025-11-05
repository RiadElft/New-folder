<?php
/**
 * Helper Functions
 * Common utility functions used throughout the application
 */

/**
 * Generate a CUID-like ID
 */
function generateId($prefix = ''): string {
    return $prefix . bin2hex(random_bytes(12));
}

/**
 * Sanitize output for HTML
 */
function e($string): string {
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Format price with currency
 */
function formatPrice($price): string {
    return number_format($price, 2, ',', ' ') . ' €';
}

/**
 * Get base URL
 */
function baseUrl($path = ''): string {
    $base = rtrim(BASE_URL, '/');
    $path = ltrim($path, '/');
    return $base . ($path ? '/' . $path : '');
}

/**
 * Get asset URL
 */
function assetUrl($path): string {
    return baseUrl('images/' . ltrim($path, '/'));
}

/**
 * Redirect to URL
 */
function redirect($url, $statusCode = 302): void {
    header("Location: " . $url, true, $statusCode);
    exit;
}

/**
 * Get JSON from database TEXT field
 */
function getJsonField($value) {
    if (empty($value)) {
        return null;
    }
    $decoded = json_decode($value, true);
    return json_last_error() === JSON_ERROR_NONE ? $decoded : null;
}

/**
 * Set JSON field for database
 */
function setJsonField($value): ?string {
    if ($value === null || $value === '') {
        return null;
    }
    return json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
}

/**
 * Get flash message
 */
function getFlash($key) {
    if (isset($_SESSION['flash'][$key])) {
        $message = $_SESSION['flash'][$key];
        unset($_SESSION['flash'][$key]);
        return $message;
    }
    return null;
}

/**
 * Set flash message
 */
function setFlash($key, $message): void {
    if (!isset($_SESSION['flash'])) {
        $_SESSION['flash'] = [];
    }
    $_SESSION['flash'][$key] = $message;
}

/**
 * Check if current route matches pattern
 */
function isRoute($pattern): bool {
    $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $base = rtrim(BASE_URL, '/');
    $currentPath = str_replace($base, '', $currentPath);
    $currentPath = ltrim($currentPath, '/');
    
    $pattern = ltrim($pattern, '/');
    $pattern = str_replace('/', '\/', $pattern);
    $pattern = '/^' . str_replace('*', '.*', $pattern) . '$/';
    
    return preg_match($pattern, $currentPath) === 1;
}

/**
 * Generate order number
 */
function generateOrderNumber(): string {
    return 'ORD-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -8));
}

/**
 * Generate URL for a named route
 */
function route($name, $params = []) {
    return Navigation::route($name, $params);
}

/**
 * Check if current route matches name
 */
function isRouteName($name) {
    return Navigation::isRoute($name);
}

/**
 * Check if current route matches any of the given names
 */
function isAnyRouteName($names) {
    return Navigation::isAnyRoute($names);
}


