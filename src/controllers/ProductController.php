<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/Product.php';

class ProductController extends BaseController {
    public function index() {
        $page = (int)($_GET['page'] ?? 1);
        
        // Map sort parameter
        $sortBy = $_GET['sortBy'] ?? 'createdAt';
        $sortDirection = $_GET['sortDirection'] ?? 'DESC';
        
        // Handle legacy sort parameter
        if (isset($_GET['sort'])) {
            switch ($_GET['sort']) {
                case 'price-asc':
                    $sortBy = 'price';
                    $sortDirection = 'asc';
                    break;
                case 'price-desc':
                    $sortBy = 'price';
                    $sortDirection = 'desc';
                    break;
                case 'name':
                    $sortBy = 'name';
                    $sortDirection = 'asc';
                    break;
                case 'newest':
                    $sortBy = 'createdAt';
                    $sortDirection = 'desc';
                    break;
            }
        }
        
        $filters = [
            'inStock' => isset($_GET['inStock']) && $_GET['inStock'] === '1' ? true : null,
            'badge' => $_GET['badge'] ?? null,
            'minPrice' => $_GET['minPrice'] ?? null,
            'maxPrice' => $_GET['maxPrice'] ?? null,
            'sortBy' => $sortBy,
            'sortDirection' => $sortDirection,
        ];
        
        $result = Product::all($filters, $page, 16);
        
        $this->view('products/index', [
            'title' => 'Produits',
            'products' => $result['items'],
            'pagination' => [
                'current' => $result['page'],
                'total' => $result['totalPages'],
                'totalItems' => $result['total'],
            ],
            'filters' => $filters,
        ]);
    }

    public function show($slug) {
        $product = Product::findBySlug($slug);
        
        if (!$product) {
            http_response_code(404);
            require __DIR__ . '/../views/errors/404.php';
            return;
        }
        
        $relatedProducts = Product::related($product['id'], $product['categoryId'], 4);
        
        // Get reviews
        require_once __DIR__ . '/../models/Review.php';
        $reviews = Review::forProduct($product['id'], 1, 10);
        
        $this->view('products/show', [
            'title' => $product['name'],
            'product' => $product,
            'relatedProducts' => $relatedProducts,
            'reviews' => $reviews,
        ]);
    }
}


