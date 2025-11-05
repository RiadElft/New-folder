<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/Cart.php';
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../lib/CSRF.php';

class CartController extends BaseController {
    public function index() {
        // Check if AJAX request
        if (isset($_GET['ajax']) && $_GET['ajax'] == '1') {
            $items = Cart::items();
            $subtotal = Cart::subtotal();
            $shipping = Cart::shipping();
            $total = Cart::total();
            
            $this->json([
                'items' => array_map(function($item) {
                    return [
                        'id' => $item['id'],
                        'name' => $item['name'],
                        'price' => number_format($item['price'], 2, ',', ' ') . ' €',
                        'qty' => $item['qty'],
                        'image' => assetUrl(ltrim($item['image'], '/')),
                    ];
                }, $items),
                'subtotal' => formatPrice($subtotal),
                'shipping' => formatPrice($shipping),
                'total' => formatPrice($total),
            ]);
            return;
        }
        
        $items = Cart::items();
        $subtotal = Cart::subtotal();
        $shipping = Cart::shipping();
        $total = Cart::total();
        
        $this->view('cart/index', [
            'title' => 'Panier',
            'items' => $items,
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'total' => $total,
        ]);
    }

    public function add() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !CSRF::validatePost()) {
            $this->json(['success' => false, 'error' => 'Invalid request'], 400);
            return;
        }
        
        $productId = $_POST['productId'] ?? null;
        $quantity = (int)($_POST['quantity'] ?? 1);
        
        if (!$productId) {
            $this->json(['success' => false, 'error' => 'Product ID required'], 400);
            return;
        }
        
        $product = Product::findById($productId);
        if (!$product) {
            $this->json(['success' => false, 'error' => 'Product not found'], 404);
            return;
        }
        
        Cart::add(
            $product['id'],
            $product['name'],
            $product['price'],
            $product['image'],
            $quantity
        );
        
        $this->json([
            'success' => true,
            'cartQuantity' => Cart::totalQuantity(),
            'cartSubtotal' => Cart::subtotal(),
            'productName' => $product['name'],
        ]);
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !CSRF::validatePost()) {
            $this->json(['success' => false, 'error' => 'Invalid request'], 400);
            return;
        }
        
        $productId = $_POST['productId'] ?? null;
        $quantity = (int)($_POST['quantity'] ?? 1);
        
        if (!$productId) {
            $this->json(['success' => false, 'error' => 'Product ID required'], 400);
            return;
        }
        
        Cart::update($productId, $quantity);
        
        $this->json([
            'success' => true,
            'cartQuantity' => Cart::totalQuantity(),
            'cartSubtotal' => Cart::subtotal(),
            'shipping' => Cart::shipping(),
            'total' => Cart::total(),
        ]);
    }

    public function remove() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !CSRF::validatePost()) {
            $this->json(['success' => false, 'error' => 'Invalid request'], 400);
            return;
        }
        
        $productId = $_POST['productId'] ?? null;
        if (!$productId) {
            $this->json(['success' => false, 'error' => 'Product ID required'], 400);
            return;
        }
        
        Cart::remove($productId);
        
        $this->json([
            'success' => true,
            'cartQuantity' => Cart::totalQuantity(),
            'cartSubtotal' => Cart::subtotal(),
        ]);
    }

    public function clear() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !CSRF::validatePost()) {
            $this->json(['success' => false, 'error' => 'Invalid request'], 400);
            return;
        }
        
        Cart::clear();
        
        $this->json(['success' => true]);
    }
}


