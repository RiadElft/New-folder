<?php
/**
 * CSRF Protection
 * Generates and validates CSRF tokens
 */

class CSRF {
    /**
     * Generate CSRF token
     */
    public static function token(): string {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    /**
     * Generate hidden input field
     */
    public static function field(): string {
        return '<input type="hidden" name="csrf_token" value="' . e(self::token()) . '">';
    }

    /**
     * Validate CSRF token
     */
    public static function validate($token): bool {
        if (!isset($_SESSION['csrf_token'])) {
            return false;
        }
        return hash_equals($_SESSION['csrf_token'], $token);
    }

    /**
     * Validate token from POST request
     */
    public static function validatePost(): bool {
        $token = $_POST['csrf_token'] ?? '';
        return self::validate($token);
    }
}


