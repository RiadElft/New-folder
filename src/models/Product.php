<?php
/**
 * Product Model
 * Handles product database operations
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../lib/Helpers.php';

class Product {
    /**
     * Get all products with filters
     */
    public static function all($filters = [], $page = 1, $perPage = 16) {
        $db = db();
        $offset = ($page - 1) * $perPage;
        
        $where = [];
        $params = [];

        if (!empty($filters['categoryId'])) {
            $where[] = 'p.categoryId = ?';
            $params[] = $filters['categoryId'];
        }

        if (!empty($filters['subcategoryId'])) {
            $where[] = 'p.subcategoryId = ?';
            $params[] = $filters['subcategoryId'];
        }

        if (isset($filters['inStock']) && $filters['inStock'] === true) {
            $where[] = 'p.inStock = 1';
        }

        if (!empty($filters['badge'])) {
            $where[] = 'p.badge = ?';
            $params[] = $filters['badge'];
        }

        if (!empty($filters['minPrice'])) {
            $where[] = 'p.price >= ?';
            $params[] = $filters['minPrice'];
        }

        if (!empty($filters['maxPrice'])) {
            $where[] = 'p.price <= ?';
            $params[] = $filters['maxPrice'];
        }

        if (!empty($filters['search'])) {
            $where[] = '(p.name LIKE ? OR p.description LIKE ? OR p.shortDescription LIKE ?)';
            $searchTerm = '%' . $filters['search'] . '%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }

        $whereClause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';

        // Sorting
        $orderBy = 'p.createdAt DESC';
        if (!empty($filters['sortBy'])) {
            $sortDirection = $filters['sortDirection'] ?? 'ASC';
            switch ($filters['sortBy']) {
                case 'price':
                    $orderBy = "p.price $sortDirection";
                    break;
                case 'name':
                    $orderBy = "p.name $sortDirection";
                    break;
                case 'rating':
                    $orderBy = "p.rating $sortDirection";
                    break;
                case 'createdAt':
                    $orderBy = "p.createdAt $sortDirection";
                    break;
            }
        }

        // Count total
        $countSql = "SELECT COUNT(*) as total FROM products p $whereClause";
        $countStmt = $db->prepare($countSql);
        $countStmt->execute($params);
        $total = $countStmt->fetch()['total'];

        // Get products
        $sql = "SELECT p.*, c.name as categoryName, c.slug as categorySlug 
                FROM products p 
                LEFT JOIN categories c ON p.categoryId = c.id 
                $whereClause 
                ORDER BY $orderBy 
                LIMIT ? OFFSET ?";
        
        $params[] = $perPage;
        $params[] = $offset;
        
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $products = $stmt->fetchAll();

        // Process products
        foreach ($products as &$product) {
            $product['images'] = getJsonField($product['images']);
            $product['tags'] = getJsonField($product['tags']);
            $product['specifications'] = getJsonField($product['specifications']);
        }

        return [
            'items' => $products,
            'total' => $total,
            'page' => $page,
            'perPage' => $perPage,
            'totalPages' => ceil($total / $perPage)
        ];
    }

    /**
     * Get product by slug
     */
    public static function findBySlug($slug) {
        $db = db();
        $stmt = $db->prepare("SELECT p.*, c.name as categoryName, c.slug as categorySlug 
                             FROM products p 
                             LEFT JOIN categories c ON p.categoryId = c.id 
                             WHERE p.slug = ?");
        $stmt->execute([$slug]);
        $product = $stmt->fetch();

        if ($product) {
            $product['images'] = getJsonField($product['images']);
            $product['tags'] = getJsonField($product['tags']);
            $product['specifications'] = getJsonField($product['specifications']);
        }

        return $product ?: null;
    }

    /**
     * Get product by ID
     */
    public static function findById($id) {
        $db = db();
        $stmt = $db->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$id]);
        $product = $stmt->fetch();

        if ($product) {
            $product['images'] = getJsonField($product['images']);
            $product['tags'] = getJsonField($product['tags']);
            $product['specifications'] = getJsonField($product['specifications']);
        }

        return $product ?: null;
    }

    /**
     * Get featured products
     */
    public static function featured($limit = 8) {
        $db = db();
        $stmt = $db->prepare("SELECT * FROM products WHERE badge = 'bestseller' AND inStock = 1 ORDER BY rating DESC LIMIT ?");
        $stmt->execute([$limit]);
        $products = $stmt->fetchAll();

        foreach ($products as &$product) {
            $product['images'] = getJsonField($product['images']);
            $product['tags'] = getJsonField($product['tags']);
        }

        return $products;
    }

    /**
     * Get related products
     */
    public static function related($productId, $categoryId, $limit = 4) {
        $db = db();
        $stmt = $db->prepare("SELECT * FROM products WHERE categoryId = ? AND id != ? AND inStock = 1 ORDER BY RAND() LIMIT ?");
        $stmt->execute([$categoryId, $productId, $limit]);
        $products = $stmt->fetchAll();

        foreach ($products as &$product) {
            $product['images'] = getJsonField($product['images']);
        }

        return $products;
    }
}


