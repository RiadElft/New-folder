<?php
/**
 * User Model
 * Handles user database operations
 */

require_once __DIR__ . '/../config/database.php';

class User {
    /**
     * Get user by ID
     */
    public static function findById($id) {
        $db = db();
        $stmt = $db->prepare("SELECT id, email, name, phone, role, active, createdAt FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    /**
     * Get user by email
     */
    public static function findByEmail($email) {
        $db = db();
        $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch() ?: null;
    }

    /**
     * Update user profile
     */
    public static function update($id, $data) {
        $db = db();
        $fields = [];
        $params = [];

        if (isset($data['name'])) {
            $fields[] = 'name = ?';
            $params[] = $data['name'];
        }

        if (isset($data['phone'])) {
            $fields[] = 'phone = ?';
            $params[] = $data['phone'];
        }

        if (isset($data['password'])) {
            $fields[] = 'password = ?';
            $params[] = password_hash($data['password'], PASSWORD_BCRYPT, ['cost' => 10]);
        }

        if (isset($data['role'])) {
            $fields[] = 'role = ?';
            $params[] = $data['role'];
        }

        if (isset($data['active'])) {
            $fields[] = 'active = ?';
            $params[] = $data['active'] ? 1 : 0;
        }

        if (empty($fields)) {
            return false;
        }

        $params[] = $id;
        $sql = "UPDATE users SET " . implode(', ', $fields) . " WHERE id = ?";
        $stmt = $db->prepare($sql);
        return $stmt->execute($params);
    }

    /**
     * Get all users (admin)
     */
    public static function all($page = 1, $perPage = 20) {
        $db = db();
        $offset = ($page - 1) * $perPage;
        $stmt = $db->query("SELECT id, email, name, phone, role, active, createdAt FROM users ORDER BY createdAt DESC LIMIT $perPage OFFSET $offset");
        return $stmt->fetchAll();
    }
}


