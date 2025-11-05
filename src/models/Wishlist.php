<?php
/**
 * Wishlist Model
 * Handles wishlist database operations
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../lib/Helpers.php';

class Wishlist {
    /**
     * Get user wishlist
     */
    public static function userWishlist($userId) {
        $db = db();
        $stmt = $db->prepare("SELECT w.*, p.*, c.name as categoryName, c.slug as categorySlug 
                             FROM wishlist w 
                             JOIN products p ON w.productId = p.id 
                             LEFT JOIN categories c ON p.categoryId = c.id
                             WHERE w.userId = ? 
                             ORDER BY w.createdAt DESC");
        $stmt->execute([$userId]);
        $items = $stmt->fetchAll();
        
        foreach ($items as &$item) {
            $item['images'] = getJsonField($item['images']);
            $item['tags'] = getJsonField($item['tags']);
            $item['specifications'] = getJsonField($item['specifications']);
        }
        
        return $items;
    }

    /**
     * Add product to wishlist
     */
    public static function add($userId, $productId) {
        $db = db();
        
        // Check if already exists
        $checkStmt = $db->prepare("SELECT id FROM wishlist WHERE userId = ? AND productId = ?");
        $checkStmt->execute([$userId, $productId]);
        if ($checkStmt->fetch()) {
            return ['success' => false, 'error' => 'Produit déjà dans les favoris'];
        }
        
        $id = generateId('wsh');
        $stmt = $db->prepare("INSERT INTO wishlist (id, userId, productId) VALUES (?, ?, ?)");
        $stmt->execute([$id, $userId, $productId]);
        
        return ['success' => true];
    }

    /**
     * Remove product from wishlist
     */
    public static function remove($userId, $productId) {
        $db = db();
        $stmt = $db->prepare("DELETE FROM wishlist WHERE userId = ? AND productId = ?");
        return $stmt->execute([$userId, $productId]);
    }

    /**
     * Check if product is in wishlist
     */
    public static function has($userId, $productId) {
        $db = db();
        $stmt = $db->prepare("SELECT id FROM wishlist WHERE userId = ? AND productId = ?");
        $stmt->execute([$userId, $productId]);
        return $stmt->fetch() !== false;
    }

    /**
     * Get wishlist count for user
     */
    public static function count($userId) {
        $db = db();
        $stmt = $db->prepare("SELECT COUNT(*) as count FROM wishlist WHERE userId = ?");
        $stmt->execute([$userId]);
        $result = $stmt->fetch();
        return $result['count'] ?? 0;
    }
}

