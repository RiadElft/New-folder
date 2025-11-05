<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/Category.php';

class SearchController extends BaseController {
    public function index() {
        $query = $_GET['query'] ?? '';
        $page = (int)($_GET['page'] ?? 1);
        
        $filters = [
            'search' => $query,
            'categoryId' => $_GET['category'] ?? null,
            'minPrice' => $_GET['minPrice'] ?? null,
            'maxPrice' => $_GET['maxPrice'] ?? null,
            'inStock' => isset($_GET['inStock']) && $_GET['inStock'] === '1' ? true : null,
            'sortBy' => $_GET['sortBy'] ?? 'createdAt',
            'sortDirection' => $_GET['sortDirection'] ?? 'DESC',
        ];
        
        $result = Product::all($filters, $page, 16);
        $categories = Category::topLevel();
        
        $this->view('search/index', [
            'title' => 'Résultats de recherche',
            'query' => $query,
            'products' => $result['items'],
            'categories' => $categories,
            'pagination' => [
                'current' => $result['page'],
                'total' => $result['totalPages'],
                'totalItems' => $result['total'],
            ],
            'filters' => $filters,
        ]);
    }
    
    public function autocomplete() {
        $query = $_GET['q'] ?? '';
        
        if (strlen($query) < 2) {
            $this->json(['items' => []]);
            return;
        }
        
        $db = db();
        $searchTerm = '%' . $query . '%';
        
        // Search products
        $stmt = $db->prepare("SELECT id, name, slug, image FROM products WHERE (name LIKE ? OR shortDescription LIKE ?) LIMIT 5");
        $stmt->execute([$searchTerm, $searchTerm]);
        $products = $stmt->fetchAll();
        
        // Search categories
        $catStmt = $db->prepare("SELECT id, name, slug FROM categories WHERE name LIKE ? AND isActive = 1 LIMIT 3");
        $catStmt->execute([$searchTerm]);
        $categories = $catStmt->fetchAll();
        
        $results = [];
        foreach ($products as $product) {
            $results[] = [
                'type' => 'product',
                'title' => $product['name'],
                'url' => baseUrl('produit/' . $product['slug']),
                'image' => baseUrl(ltrim($product['image'], '/')),
            ];
        }
        
        foreach ($categories as $category) {
            $results[] = [
                'type' => 'category',
                'title' => $category['name'],
                'url' => baseUrl('categorie/' . $category['slug']),
            ];
        }
        
        $this->json(['items' => $results]);
    }
}


