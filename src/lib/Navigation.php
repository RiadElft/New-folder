<?php
/**
 * Navigation Helper
 * Provides URL generation and navigation utilities
 */

class Navigation {
    private static $router = null;

    /**
     * Set router instance
     */
    public static function setRouter($router) {
        self::$router = $router;
    }

    /**
     * Generate URL for a named route
     */
    public static function route($name, $params = []) {
        if (self::$router && method_exists(self::$router, 'url')) {
            return self::$router->url($name, $params);
        }
        return '#';
    }

    /**
     * Check if current route matches name
     */
    public static function isRoute($name) {
        if (!self::$router || !method_exists(self::$router, 'currentRouteName')) {
            return false;
        }
        return self::$router->currentRouteName() === $name;
    }

    /**
     * Check if current route matches any of the given names
     */
    public static function isAnyRoute($names) {
        if (!is_array($names)) {
            $names = [$names];
        }
        $current = self::$router && method_exists(self::$router, 'currentRouteName') 
            ? self::$router->currentRouteName() 
            : null;
        return $current && in_array($current, $names);
    }

    /**
     * Check if current path matches pattern
     */
    public static function isPath($pattern) {
        return isRoute($pattern);
    }

    /**
     * Get current path
     */
    public static function currentPath() {
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $base = rtrim(BASE_URL, '/');
        $path = str_replace($base, '', $path);
        return '/' . ltrim($path, '/');
    }

    /**
     * Get route parameter from current URL
     */
    public static function getRouteParam($paramName) {
        if (!self::$router || !method_exists(self::$router, 'getRouteParam')) {
            return null;
        }
        
        return self::$router->getRouteParam($paramName);
    }

    /**
     * Generate category URL
     */
    public static function category($slug) {
        return self::route('category.show', ['slug' => $slug]);
    }

    /**
     * Generate product URL
     */
    public static function product($slug) {
        return self::route('product.show', ['slug' => $slug]);
    }

    /**
     * Generate order detail URL
     */
    public static function order($id) {
        return self::route('account.order.detail', ['id' => $id]);
    }
}

