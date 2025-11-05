<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../lib/Auth.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Order.php';
require_once __DIR__ . '/../models/Address.php';

class AccountController extends BaseController {
    public function index() {
        Auth::requireAuth();
        $user = Auth::user();
        
        $this->view('account/index', [
            'title' => 'Mon Compte',
            'user' => $user,
        ]);
    }

    public function profile() {
        Auth::requireAuth();
        $user = Auth::user();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name'] ?? null,
                'phone' => $_POST['phone'] ?? null,
            ];
            
            if (!empty($_POST['password'])) {
                $data['password'] = $_POST['password'];
            }
            
            User::update(Auth::id(), $data);
            setFlash('success', 'Profil mis à jour');
            $this->redirect(baseUrl('compte/profil'));
            return;
        }
        
        $this->view('account/profile', [
            'title' => 'Mon Profil',
            'user' => $user,
        ]);
    }

    public function orders() {
        Auth::requireAuth();
        $orders = Order::userOrders(Auth::id());
        
        $this->view('account/orders', [
            'title' => 'Mes Commandes',
            'orders' => $orders,
        ]);
    }

    public function orderDetail($id) {
        Auth::requireAuth();
        $order = Order::findById($id);
        
        if (!$order || $order['userId'] !== Auth::id()) {
            http_response_code(404);
            require __DIR__ . '/../views/errors/404.php';
            return;
        }
        
        $items = Order::items($id);
        
        $this->view('account/order-detail', [
            'title' => 'Commande #' . $order['orderNumber'],
            'order' => $order,
            'items' => $items,
        ]);
    }

    public function addresses() {
        Auth::requireAuth();
        $addresses = Address::userAddresses(Auth::id());
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'firstName' => $_POST['firstName'],
                'lastName' => $_POST['lastName'],
                'street' => $_POST['street'],
                'city' => $_POST['city'],
                'postalCode' => $_POST['postalCode'],
                'country' => $_POST['country'] ?? 'France',
                'phone' => $_POST['phone'] ?? null,
                'type' => $_POST['type'] ?? 'BOTH',
            ];
            
            Address::create(Auth::id(), $data);
            setFlash('success', 'Adresse ajoutée');
            $this->redirect(baseUrl('compte/adresses'));
            return;
        }
        
        $this->view('account/addresses', [
            'title' => 'Mes Adresses',
            'addresses' => $addresses,
        ]);
    }

    public function deleteAddress($id) {
        Auth::requireAuth();
        $address = Address::findById($id);
        
        if ($address && $address['userId'] === Auth::id()) {
            $db = db();
            $stmt = $db->prepare("DELETE FROM addresses WHERE id = ?");
            $stmt->execute([$id]);
            setFlash('success', 'Adresse supprimée');
        }
        
        $this->redirect(baseUrl('compte/adresses'));
    }

    public function wishlist() {
        Auth::requireAuth();
        require_once __DIR__ . '/../models/Wishlist.php';
        $items = Wishlist::userWishlist(Auth::id());
        
        $this->view('account/wishlist', [
            'title' => 'Mes Favoris',
            'items' => $items,
        ]);
    }

    public function addToWishlist() {
        Auth::requireAuth();
        require_once __DIR__ . '/../models/Wishlist.php';
        
        $productId = $_POST['productId'] ?? null;
        if (!$productId) {
            $this->json(['success' => false, 'error' => 'Produit requis'], 400);
            return;
        }
        
        $result = Wishlist::add(Auth::id(), $productId);
        $this->json($result);
    }

    public function removeFromWishlist() {
        Auth::requireAuth();
        require_once __DIR__ . '/../models/Wishlist.php';
        
        $productId = $_POST['productId'] ?? null;
        if (!$productId) {
            $this->json(['success' => false, 'error' => 'Produit requis'], 400);
            return;
        }
        
        Wishlist::remove(Auth::id(), $productId);
        $this->json(['success' => true]);
    }
}


