<?php
/**
 * Review Model
 * Handles review database operations
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../lib/Helpers.php';

class Review {
    /**
     * Get reviews for a product
     */
    public static function forProduct($productId, $page = 1, $perPage = 10) {
        $db = db();
        $offset = ($page - 1) * $perPage;
        
        $stmt = $db->prepare("SELECT r.*, u.name as userName, u.email as userEmail 
                             FROM reviews r 
                             JOIN users u ON r.userId = u.id 
                             WHERE r.productId = ? 
                             ORDER BY r.createdAt DESC 
                             LIMIT ? OFFSET ?");
        $stmt->execute([$productId, $perPage, $offset]);
        $reviews = $stmt->fetchAll();
        
        // Count total
        $countStmt = $db->prepare("SELECT COUNT(*) as total FROM reviews WHERE productId = ?");
        $countStmt->execute([$productId]);
        $total = $countStmt->fetch()['total'];
        
        return [
            'items' => $reviews,
            'total' => $total,
            'page' => $page,
            'perPage' => $perPage,
            'totalPages' => ceil($total / $perPage)
        ];
    }
    
    /**
     * Create a review
     */
    public static function create($userId, $productId, $rating, $comment = null) {
        $db = db();
        
        // Check if user already reviewed this product
        $checkStmt = $db->prepare("SELECT id FROM reviews WHERE userId = ? AND productId = ?");
        $checkStmt->execute([$userId, $productId]);
        if ($checkStmt->fetch()) {
            return ['success' => false, 'error' => 'Vous avez déjà laissé un avis pour ce produit'];
        }
        
        $id = generateId('rev');
        $stmt = $db->prepare("INSERT INTO reviews (id, userId, productId, rating, comment) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$id, $userId, $productId, $rating, $comment]);
        
        // Update product rating
        self::updateProductRating($productId);
        
        return ['success' => true, 'id' => $id];
    }
    
    /**
     * Update product rating based on reviews
     */
    private static function updateProductRating($productId) {
        $db = db();
        
        $stmt = $db->prepare("SELECT AVG(rating) as avgRating, COUNT(*) as count FROM reviews WHERE productId = ?");
        $stmt->execute([$productId]);
        $result = $stmt->fetch();
        
        $avgRating = round($result['avgRating'], 2);
        $reviewCount = $result['count'];
        
        $updateStmt = $db->prepare("UPDATE products SET rating = ?, reviewCount = ? WHERE id = ?");
        $updateStmt->execute([$avgRating, $reviewCount, $productId]);
    }
    
    /**
     * Delete a review
     */
    public static function delete($reviewId, $userId) {
        $db = db();
        
        // Verify ownership
        $checkStmt = $db->prepare("SELECT productId FROM reviews WHERE id = ? AND userId = ?");
        $checkStmt->execute([$reviewId, $userId]);
        $review = $checkStmt->fetch();
        
        if (!$review) {
            return false;
        }
        
        $stmt = $db->prepare("DELETE FROM reviews WHERE id = ?");
        $stmt->execute([$reviewId]);
        
        // Update product rating
        self::updateProductRating($review['productId']);
        
        return true;
    }
}

