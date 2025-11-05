<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../models/Product.php';

class ApiController extends BaseController {
    public function categories() {
        $categories = Category::all();
        $this->json(['items' => $categories]);
    }

    public function products() {
        $page = (int)($_GET['page'] ?? 1);
        $filters = [
            'inStock' => $_GET['inStock'] ?? null,
            'badge' => $_GET['badge'] ?? null,
            'minPrice' => $_GET['minPrice'] ?? null,
            'maxPrice' => $_GET['maxPrice'] ?? null,
            'sortBy' => $_GET['sortBy'] ?? 'createdAt',
            'sortDirection' => $_GET['sortDirection'] ?? 'DESC',
        ];
        
        $result = Product::all($filters, $page, 16);
        $this->json($result);
    }

    public function search() {
        $query = $_GET['q'] ?? '';
        $page = (int)($_GET['page'] ?? 1);
        
        $filters = [
            'search' => $query,
            'sortBy' => $_GET['sortBy'] ?? 'createdAt',
            'sortDirection' => $_GET['sortDirection'] ?? 'DESC',
        ];
        
        $result = Product::all($filters, $page, 16);
        $this->json($result);
    }
}


