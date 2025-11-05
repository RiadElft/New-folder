<?php
/**
 * Authentication Library
 * Handles user authentication, sessions, and authorization
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

class Auth {
    /**
     * Start session if not already started
     */
    public static function startSession(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_name(SESSION_NAME);
            session_set_cookie_params([
                'lifetime' => SESSION_LIFETIME,
                'path' => '/',
                'domain' => '',
                'secure' => isset($_SERVER['HTTPS']),
                'httponly' => true,
                'samesite' => 'Lax'
            ]);
            session_start();
        }
    }

    /**
     * Login user
     */
    public static function login($email, $password): bool {
        self::startSession();
        
        $db = db();
        $stmt = $db->prepare("SELECT * FROM users WHERE email = ? AND active = 1");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if (!$user) {
            return false;
        }

        if (!password_verify($password, $user['password'])) {
            return false;
        }

        // Set session data
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['logged_in'] = true;
        $_SESSION['login_time'] = time();

        return true;
    }

    /**
     * Logout user
     */
    public static function logout(): void {
        self::startSession();
        $_SESSION = [];
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600, '/');
        }
        session_destroy();
    }

    /**
     * Check if user is logged in
     */
    public static function check(): bool {
        self::startSession();
        
        if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
            return false;
        }

        // Check session expiry
        if (isset($_SESSION['login_time']) && (time() - $_SESSION['login_time']) > SESSION_LIFETIME) {
            self::logout();
            return false;
        }

        return true;
    }

    /**
     * Get current user ID
     */
    public static function id(): ?string {
        self::startSession();
        return $_SESSION['user_id'] ?? null;
    }

    /**
     * Get current user
     */
    public static function user(): ?array {
        if (!self::check()) {
            return null;
        }

        $db = db();
        $stmt = $db->prepare("SELECT id, email, name, phone, role FROM users WHERE id = ?");
        $stmt->execute([self::id()]);
        return $stmt->fetch() ?: null;
    }

    /**
     * Check if user is admin
     * Checks role first, then falls back to email for backward compatibility
     */
    public static function isAdmin(): bool {
        if (!self::check()) {
            return false;
        }

        // Check role from session (preferred method)
        $role = $_SESSION['user_role'] ?? '';
        if ($role === 'admin') {
            return true;
        }
        
        // Fallback: check email for backward compatibility
        $email = $_SESSION['user_email'] ?? '';
        return strtolower($email) === strtolower(ADMIN_EMAIL);
    }

    /**
     * Require authentication (redirect if not logged in)
     */
    public static function requireAuth(): void {
        if (!self::check()) {
            redirect(baseUrl('auth/login'));
        }
    }

    /**
     * Require admin (redirect if not admin)
     */
    public static function requireAdmin(): void {
        self::requireAuth();
        if (!self::isAdmin()) {
            redirect(baseUrl('')); // Redirect to home if not admin
        }
    }

    /**
     * Register new user
     */
    public static function register($email, $password, $name = null, $phone = null): array {
        $db = db();
        
        // Check if email already exists
        $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            return ['success' => false, 'error' => 'Email already registered'];
        }

        // Create user
        $userId = generateId('usr');
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT, ['cost' => 10]);
        
        $stmt = $db->prepare("INSERT INTO users (id, email, password, name, phone, role, active) VALUES (?, ?, ?, ?, ?, 'user', 1)");
        $stmt->execute([$userId, $email, $hashedPassword, $name, $phone]);

        // Auto-login
        self::login($email, $password);

        return ['success' => true, 'user_id' => $userId];
    }
}

// Start session on include
Auth::startSession();


