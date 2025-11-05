<?php
/**
 * Database Connection
 * PDO-based database connection with error handling
 */

require_once __DIR__ . '/config.php';

class Database {
    private static $instance = null;
    private $connection;

    private function __construct() {
        try {
            // Detect database type: PostgreSQL if host contains "postgres" or port is 5432
            $dbPort = getenv('DB_PORT') ?: (defined('DB_PORT') && DB_PORT ? DB_PORT : '');
            $isPostgres = (
                stripos(DB_HOST, 'postgres') !== false || 
                $dbPort == '5432' ||
                getenv('DB_TYPE') === 'postgresql'
            );
            
            if ($isPostgres) {
                // PostgreSQL connection
                $port = $dbPort ?: '5432';
                $dsn = "pgsql:host=" . DB_HOST . ";port=" . $port . ";dbname=" . DB_NAME;
            } else {
                // MySQL connection (default)
                $port = $dbPort ?: '3306';
                $dsn = "mysql:host=" . DB_HOST . ";port=" . $port . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            }
            
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];

            $this->connection = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            if (APP_DEBUG) {
                die("Database connection failed: " . $e->getMessage());
            } else {
                die("Database connection failed. Please contact the administrator.");
            }
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->connection;
    }

    // Prevent cloning
    private function __clone() {}

    // Prevent unserialization
    public function __wakeup() {
        throw new Exception("Cannot unserialize singleton");
    }
}

// Global function to get database connection
function db() {
    return Database::getInstance()->getConnection();
}


