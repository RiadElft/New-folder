<?php
/**
 * Cart Model
 * Handles shopping cart operations using PHP sessions
 */

require_once __DIR__ . '/../lib/Auth.php';

class Cart {
    private static function init() {
        Auth::startSession();
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
    }

    /**
     * Get all cart items
     */
    public static function items() {
        self::init();
        return $_SESSION['cart'] ?? [];
    }

    /**
     * Add item to cart
     */
    public static function add($productId, $name, $price, $image, $quantity = 1, $format = null) {
        self::init();
        
        // Check if product already in cart
        $index = self::findIndex($productId);
        
        if ($index !== false) {
            // Update quantity
            $_SESSION['cart'][$index]['qty'] += $quantity;
        } else {
            // Add new item
            $_SESSION['cart'][] = [
                'id' => $productId,
                'name' => $name,
                'price' => (float)$price,
                'image' => $image,
                'qty' => $quantity,
                'format' => $format
            ];
        }
    }

    /**
     * Update item quantity
     */
    public static function update($productId, $quantity) {
        self::init();
        $index = self::findIndex($productId);
        
        if ($index !== false) {
            if ($quantity > 0) {
                $_SESSION['cart'][$index]['qty'] = (int)$quantity;
            } else {
                self::remove($productId);
            }
        }
    }

    /**
     * Remove item from cart
     */
    public static function remove($productId) {
        self::init();
        $index = self::findIndex($productId);
        
        if ($index !== false) {
            unset($_SESSION['cart'][$index]);
            $_SESSION['cart'] = array_values($_SESSION['cart']); // Reindex
        }
    }

    /**
     * Clear cart
     */
    public static function clear() {
        self::init();
        $_SESSION['cart'] = [];
    }

    /**
     * Get cart subtotal
     */
    public static function subtotal() {
        $items = self::items();
        $total = 0;
        foreach ($items as $item) {
            $total += $item['price'] * $item['qty'];
        }
        return $total;
    }

    /**
     * Get total quantity
     */
    public static function totalQuantity() {
        $items = self::items();
        $total = 0;
        foreach ($items as $item) {
            $total += $item['qty'];
        }
        return $total;
    }

    /**
     * Get shipping cost
     */
    public static function shipping() {
        $subtotal = self::subtotal();
        return $subtotal >= FREE_SHIPPING_THRESHOLD ? 0 : DEFAULT_SHIPPING_COST;
    }

    /**
     * Get total
     */
    public static function total() {
        return self::subtotal() + self::shipping();
    }

    /**
     * Find item index in cart
     */
    private static function findIndex($productId) {
        $items = self::items();
        foreach ($items as $index => $item) {
            if ($item['id'] === $productId) {
                return $index;
            }
        }
        return false;
    }
}


