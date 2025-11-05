<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../lib/Auth.php';
require_once __DIR__ . '/../models/Cart.php';
require_once __DIR__ . '/../models/Order.php';
require_once __DIR__ . '/../models/Address.php';

class CheckoutController extends BaseController {
    public function index() {
        Auth::requireAuth();
        
        $cartItems = Cart::items();
        if (empty($cartItems)) {
            $this->redirect(baseUrl('panier'));
            return;
        }
        
        $step = $_GET['step'] ?? 'address';
        
        $this->view('checkout/index', [
            'title' => 'Commander',
            'cartItems' => $cartItems,
            'subtotal' => Cart::subtotal(),
            'shipping' => Cart::shipping(),
            'total' => Cart::total(),
            'step' => $step,
        ]);
    }

    public function process() {
        Auth::requireAuth();
        
        $cartItems = Cart::items();
        if (empty($cartItems)) {
            $this->redirect(baseUrl('panier'));
            return;
        }
        
        // Create address
        require_once __DIR__ . '/../models/Address.php';
        $shippingAddressId = Address::create(Auth::id(), [
            'firstName' => $_POST['firstName'] ?? '',
            'lastName' => $_POST['lastName'] ?? '',
            'street' => $_POST['street'] ?? '',
            'city' => $_POST['city'] ?? '',
            'postalCode' => $_POST['postalCode'] ?? '',
            'country' => $_POST['country'] ?? 'France',
            'phone' => $_POST['phone'] ?? null,
            'type' => 'BOTH',
        ]);
        
        // Process payment
        require_once __DIR__ . '/../lib/Payment.php';
        $paymentResult = Payment::process(
            Cart::total(),
            'EUR',
            $_POST['paymentMethod'] ?? 'card',
            $_POST['paymentData'] ?? []
        );
        
        if (!$paymentResult['success']) {
            setFlash('error', 'Erreur de paiement: ' . ($paymentResult['error'] ?? 'Erreur inconnue'));
            $this->redirect(baseUrl('commander'));
            return;
        }
        
        // Create order
        require_once __DIR__ . '/../models/Order.php';
        $result = Order::create(
            Auth::id(),
            $cartItems,
            $shippingAddressId,
            $shippingAddressId, // Same as billing for now
            $_POST['paymentMethod'] ?? 'card',
            Cart::subtotal(),
            Cart::shipping()
        );
        
        if ($result['success']) {
            // Send confirmation email
            require_once __DIR__ . '/../lib/Email.php';
            $user = Auth::user();
            Email::sendOrderConfirmation(
                $user['email'],
                $result['orderNumber'],
                [
                    'total' => Cart::total(),
                    'status' => 'PENDING'
                ]
            );
            
            Cart::clear();
            $this->redirect(baseUrl('commande/succes'));
        } else {
            setFlash('error', 'Erreur lors de la création de la commande');
            $this->redirect(baseUrl('commander'));
        }
    }

    public function success() {
        Auth::requireAuth();
        
        $this->view('checkout/success', [
            'title' => 'Commande confirmée',
        ]);
    }
}


