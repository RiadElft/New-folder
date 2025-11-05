<?php
/**
 * Order Model
 * Handles order database operations
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../lib/Helpers.php';

class Order {
    /**
     * Create new order
     */
    public static function create($userId, $cartItems, $shippingAddressId, $billingAddressId, $paymentMethod, $subtotal, $shipping) {
        $db = db();
        
        try {
            $db->beginTransaction();

            $orderId = generateId('ord');
            $orderNumber = generateOrderNumber();
            $total = $subtotal + $shipping;

            // Create order
            $stmt = $db->prepare("INSERT INTO orders (id, userId, orderNumber, status, subtotal, shipping, total, paymentMethod, shippingAddressId, billingAddressId) 
                                 VALUES (?, ?, ?, 'PENDING', ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$orderId, $userId, $orderNumber, $subtotal, $shipping, $total, $paymentMethod, $shippingAddressId, $billingAddressId]);

            // Create order items
            $itemStmt = $db->prepare("INSERT INTO order_items (id, orderId, productId, quantity, price, name) VALUES (?, ?, ?, ?, ?, ?)");
            
            foreach ($cartItems as $item) {
                $itemId = generateId('itm');
                $itemStmt->execute([
                    $itemId,
                    $orderId,
                    $item['id'],
                    $item['qty'],
                    $item['price'],
                    $item['name']
                ]);
            }

            $db->commit();
            return ['success' => true, 'orderId' => $orderId, 'orderNumber' => $orderNumber];
        } catch (Exception $e) {
            $db->rollBack();
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Get order by ID
     */
    public static function findById($id) {
        $db = db();
        $stmt = $db->prepare("SELECT o.*, 
                             sa.firstName as shippingFirstName, sa.lastName as shippingLastName, sa.street as shippingStreet, 
                             sa.city as shippingCity, sa.postalCode as shippingPostalCode, sa.country as shippingCountry, sa.phone as shippingPhone,
                             ba.firstName as billingFirstName, ba.lastName as billingLastName, ba.street as billingStreet,
                             ba.city as billingCity, ba.postalCode as billingPostalCode, ba.country as billingCountry, ba.phone as billingPhone
                             FROM orders o
                             LEFT JOIN addresses sa ON o.shippingAddressId = sa.id
                             LEFT JOIN addresses ba ON o.billingAddressId = ba.id
                             WHERE o.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    /**
     * Get order by order number
     */
    public static function findByOrderNumber($orderNumber) {
        $db = db();
        $stmt = $db->prepare("SELECT * FROM orders WHERE orderNumber = ?");
        $stmt->execute([$orderNumber]);
        return $stmt->fetch() ?: null;
    }

    /**
     * Get order items
     */
    public static function items($orderId) {
        $db = db();
        $stmt = $db->prepare("SELECT oi.*, p.image FROM order_items oi LEFT JOIN products p ON oi.productId = p.id WHERE oi.orderId = ?");
        $stmt->execute([$orderId]);
        return $stmt->fetchAll();
    }

    /**
     * Get user orders
     */
    public static function userOrders($userId) {
        $db = db();
        $stmt = $db->prepare("SELECT * FROM orders WHERE userId = ? ORDER BY createdAt DESC");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    /**
     * Update order status
     */
    public static function updateStatus($id, $status) {
        $db = db();
        $stmt = $db->prepare("UPDATE orders SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }

    /**
     * Get all orders (admin)
     */
    public static function all($page = 1, $perPage = 20) {
        $db = db();
        $offset = ($page - 1) * $perPage;
        
        $stmt = $db->query("SELECT o.*, u.name as userName, u.email as userEmail 
                           FROM orders o 
                           LEFT JOIN users u ON o.userId = u.id 
                           ORDER BY o.createdAt DESC 
                           LIMIT $perPage OFFSET $offset");
        return $stmt->fetchAll();
    }
}


