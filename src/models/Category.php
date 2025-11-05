<?php
/**
 * Category Model
 * Handles category database operations
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../lib/Helpers.php';

class Category {
    /**
     * Get all categories
     */
    public static function all($includeInactive = false) {
        $db = db();
        $where = $includeInactive ? '' : 'WHERE isActive = 1';
        $stmt = $db->query("SELECT * FROM categories $where ORDER BY sortOrder ASC, name ASC");
        return $stmt->fetchAll();
    }

    /**
     * Get category by slug
     */
    public static function findBySlug($slug) {
        $db = db();
        $stmt = $db->prepare("SELECT * FROM categories WHERE slug = ?");
        $stmt->execute([$slug]);
        return $stmt->fetch() ?: null;
    }

    /**
     * Get top-level categories (no parent)
     */
    public static function topLevel() {
        $db = db();
        $stmt = $db->query("SELECT * FROM categories WHERE parentId IS NULL AND isActive = 1 ORDER BY sortOrder ASC");
        return $stmt->fetchAll();
    }

    /**
     * Get subcategories for a parent
     */
    public static function children($parentId) {
        $db = db();
        $stmt = $db->prepare("SELECT * FROM categories WHERE parentId = ? AND isActive = 1 ORDER BY sortOrder ASC");
        $stmt->execute([$parentId]);
        return $stmt->fetchAll();
    }

    /**
     * Get category with children
     */
    public static function withChildren($slug) {
        $category = self::findBySlug($slug);
        if ($category) {
            $category['children'] = self::children($category['id']);
        }
        return $category;
    }
}


