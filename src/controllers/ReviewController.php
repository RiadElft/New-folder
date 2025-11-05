<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../lib/Auth.php';
require_once __DIR__ . '/../models/Review.php';

class ReviewController extends BaseController {
    public function create() {
        Auth::requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'error' => 'Invalid request'], 400);
            return;
        }
        
        require_once __DIR__ . '/../lib/CSRF.php';
        CSRF::validate();
        
        $productId = $_POST['productId'] ?? null;
        $rating = (int)($_POST['rating'] ?? 0);
        $comment = $_POST['comment'] ?? null;
        
        if (!$productId || $rating < 1 || $rating > 5) {
            $this->json(['success' => false, 'error' => 'Invalid data'], 400);
            return;
        }
        
        $result = Review::create(Auth::id(), $productId, $rating, $comment);
        $this->json($result);
    }
    
    public function delete($id) {
        Auth::requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'error' => 'Invalid request'], 400);
            return;
        }
        
        require_once __DIR__ . '/../lib/CSRF.php';
        CSRF::validate();
        
        $success = Review::delete($id, Auth::id());
        $this->json(['success' => $success]);
    }
}

