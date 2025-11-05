<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../models/Product.php';

class CategoryController extends BaseController {
    public function show($slug) {
        $category = Category::withChildren($slug);
        
        if (!$category) {
            http_response_code(404);
            require __DIR__ . '/../views/errors/404.php';
            return;
        }
        
        $page = (int)($_GET['page'] ?? 1);
        $filters = [
            'categoryId' => $category['id'],
            'sortBy' => $_GET['sortBy'] ?? 'createdAt',
            'sortDirection' => $_GET['sortDirection'] ?? 'DESC',
        ];
        
        $result = Product::all($filters, $page, 16);
        
        $this->view('categories/show', [
            'title' => $category['name'],
            'category' => $category,
            'products' => $result['items'],
            'pagination' => [
                'current' => $result['page'],
                'total' => $result['totalPages'],
            ],
        ]);
    }
}


