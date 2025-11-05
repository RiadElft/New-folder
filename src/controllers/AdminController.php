<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../lib/Auth.php';
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../models/Hero.php';
require_once __DIR__ . '/../models/Order.php';
require_once __DIR__ . '/../models/User.php';

class AdminController extends BaseController {
    public function __construct() {
        Auth::requireAdmin();
    }

    public function dashboard() {
        require_once __DIR__ . '/../models/Order.php';
        
        // Get stats
        $db = db();
        $stats = [
            'totalProducts' => $db->query("SELECT COUNT(*) FROM products")->fetchColumn(),
            'totalOrders' => $db->query("SELECT COUNT(*) FROM orders")->fetchColumn(),
            'totalUsers' => $db->query("SELECT COUNT(*) FROM users")->fetchColumn(),
            'revenue' => $db->query("SELECT SUM(total) FROM orders WHERE status != 'CANCELLED'")->fetchColumn() ?? 0,
            'pendingOrders' => $db->query("SELECT COUNT(*) FROM orders WHERE status = 'PENDING'")->fetchColumn(),
            'lowStockProducts' => $db->query("SELECT COUNT(*) FROM products WHERE inStock = 1 AND stockQuantity > 0 AND stockQuantity < 10")->fetchColumn(),
            'revenueThisMonth' => $db->query("SELECT SUM(total) FROM orders WHERE status != 'CANCELLED' AND MONTH(createdAt) = MONTH(CURRENT_DATE()) AND YEAR(createdAt) = YEAR(CURRENT_DATE())")->fetchColumn() ?? 0,
        ];
        
        // Get recent orders
        $recentOrders = Order::all(1, 5);
        
        // Get low stock products
        $lowStockProducts = $db->query("SELECT id, name, slug, stockQuantity, image FROM products WHERE inStock = 1 AND stockQuantity > 0 AND stockQuantity < 10 ORDER BY stockQuantity ASC LIMIT 5")->fetchAll();
        
        // Get recent products
        $recentProducts = $db->query("SELECT id, name, slug, price, image, createdAt FROM products ORDER BY createdAt DESC LIMIT 5")->fetchAll();
        
        // Get order status breakdown
        $orderStatuses = $db->query("SELECT status, COUNT(*) as count FROM orders GROUP BY status")->fetchAll();
        $statusBreakdown = [];
        foreach ($orderStatuses as $status) {
            $statusBreakdown[$status['status']] = $status['count'];
        }
        
        $this->view('admin/dashboard', [
            'title' => 'Tableau de bord',
            'layout' => 'admin',
            'stats' => $stats,
            'recentOrders' => $recentOrders,
            'lowStockProducts' => $lowStockProducts,
            'recentProducts' => $recentProducts,
            'statusBreakdown' => $statusBreakdown,
        ]);
    }

    public function products() {
        $products = Product::all([], 1, 50);
        $this->view('admin/products/index', [
            'title' => 'Gérer les produits',
            'layout' => 'admin',
            'products' => $products['items'],
        ]);
    }

    public function productCreate() {
        $categories = Category::all();
        $this->view('admin/products/create', [
            'title' => 'Nouveau produit',
            'layout' => 'admin',
            'categories' => $categories,
        ]);
    }

    public function heroIndex() {
        $selectedIds = Hero::getProductIds();
        $products = Product::all([], 1, 200);
        $this->view('admin/hero/index', [
            'title' => 'Hero Products',
            'layout' => 'admin',
            'products' => $products['items'],
            'selectedIds' => $selectedIds,
        ]);
    }

    public function heroSave() {
        require_once __DIR__ . '/../lib/CSRF.php';
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !CSRF::validatePost()) {
            setFlash('error', 'Requête invalide');
            $this->redirect(baseUrl('admin/hero'));
            return;
        }
        $ordered = [];
        if (!empty($_POST['ordered'])) {
            $ordered = array_filter(explode(',', $_POST['ordered']));
        } elseif (!empty($_POST['heroIds']) && is_array($_POST['heroIds'])) {
            $ordered = array_values(array_filter($_POST['heroIds']));
        }
        Hero::setProductIds($ordered);
        setFlash('success', 'Hero mis à jour');
        $this->redirect(baseUrl('admin/hero'));
    }

    public function productStore() {
        require_once __DIR__ . '/../lib/Helpers.php';
        require_once __DIR__ . '/../lib/CSRF.php';
        require_once __DIR__ . '/../lib/FileUpload.php';
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !CSRF::validatePost()) {
            setFlash('error', 'Requête invalide');
            $this->redirect(route('admin.product.create'));
            return;
        }
        
        $db = db();
        $id = generateId('prd');
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $_POST['name'] ?? '')));
        
        // Ensure unique slug
        $slugBase = $slug;
        $counter = 1;
        $checkStmt = $db->prepare("SELECT id FROM products WHERE slug = ?");
        while ($checkStmt->execute([$slug]) && $checkStmt->fetch()) {
            $slug = $slugBase . '-' . $counter++;
        }
        
        // Handle image upload
        $imagePath = $_POST['image'] ?? '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadResult = FileUpload::uploadImage($_FILES['image'], 'products');
            if ($uploadResult['success']) {
                $imagePath = $uploadResult['path'];
            }
        }
        
        // Handle multiple images
        $images = [];
        if (!empty($_POST['images'])) {
            $images = json_decode($_POST['images'], true) ?? [];
        }
        if (isset($_FILES['images']) && is_array($_FILES['images']['name'])) {
            foreach ($_FILES['images']['name'] as $key => $name) {
                if ($_FILES['images']['error'][$key] === UPLOAD_ERR_OK) {
                    $file = [
                        'name' => $name,
                        'type' => $_FILES['images']['type'][$key],
                        'tmp_name' => $_FILES['images']['tmp_name'][$key],
                        'error' => $_FILES['images']['error'][$key],
                        'size' => $_FILES['images']['size'][$key]
                    ];
                    $uploadResult = FileUpload::uploadImage($file, 'products');
                    if ($uploadResult['success']) {
                        $images[] = $uploadResult['path'];
                    }
                }
            }
        }
        
        $stmt = $db->prepare("INSERT INTO products (id, name, slug, description, shortDescription, price, originalPrice, image, images, categoryId, subcategoryId, tags, inStock, stockQuantity, badge, author, publisher, isbn, pages, language, format, specifications) 
                             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        $stmt->execute([
            $id,
            $_POST['name'] ?? '',
            $slug,
            $_POST['description'] ?? null,
            $_POST['shortDescription'] ?? null,
            $_POST['price'] ?? 0,
            !empty($_POST['originalPrice']) ? $_POST['originalPrice'] : null,
            $imagePath,
            setJsonField(!empty($images) ? $images : null),
            $_POST['categoryId'] ?? null,
            !empty($_POST['subcategoryId']) ? $_POST['subcategoryId'] : null,
            setJsonField(!empty($_POST['tags']) ? (is_array($_POST['tags']) ? $_POST['tags'] : explode(',', $_POST['tags'])) : null),
            isset($_POST['inStock']) ? 1 : 0,
            $_POST['stockQuantity'] ?? 0,
            $_POST['badge'] ?? null,
            $_POST['author'] ?? null,
            $_POST['publisher'] ?? null,
            $_POST['isbn'] ?? null,
            !empty($_POST['pages']) ? $_POST['pages'] : null,
            $_POST['language'] ?? null,
            $_POST['format'] ?? null,
            setJsonField(!empty($_POST['specifications']) ? json_decode($_POST['specifications'], true) : null)
        ]);
        
        setFlash('success', 'Produit créé avec succès');
        $this->redirect(baseUrl('admin/produits'));
    }

    public function productEdit($id) {
        $product = Product::findById($id);
        $categories = Category::all();
        $this->view('admin/products/edit', [
            'title' => 'Modifier le produit',
            'layout' => 'admin',
            'product' => $product,
            'categories' => $categories,
        ]);
    }

    public function productUpdate($id) {
        require_once __DIR__ . '/../lib/Helpers.php';
        require_once __DIR__ . '/../lib/CSRF.php';
        require_once __DIR__ . '/../lib/FileUpload.php';
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !CSRF::validatePost()) {
            setFlash('error', 'Requête invalide');
            $this->redirect(baseUrl('admin/produits'));
            return;
        }
        
        $product = Product::findById($id);
        if (!$product) {
            $this->redirect(baseUrl('admin/produits'));
            return;
        }
        
        $db = db();
        $slug = $product['slug'];
        if (!empty($_POST['name']) && $_POST['name'] !== $product['name']) {
            $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $_POST['name'])));
            // Ensure unique slug
            $slugBase = $slug;
            $counter = 1;
            $checkStmt = $db->prepare("SELECT id FROM products WHERE slug = ? AND id != ?");
            while ($checkStmt->execute([$slug, $id]) && $checkStmt->fetch()) {
                $slug = $slugBase . '-' . $counter++;
            }
        }
        
        // Handle image upload
        $imagePath = $product['image'];
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadResult = FileUpload::uploadImage($_FILES['image'], 'products');
            if ($uploadResult['success']) {
                // Delete old image if exists
                if ($product['image'] && strpos($product['image'], 'uploads/') === 0) {
                    FileUpload::deleteFile($product['image']);
                }
                $imagePath = $uploadResult['path'];
            }
        }
        
        // Handle multiple images
        $images = $product['images'] ?? [];
        if (isset($_FILES['images']) && is_array($_FILES['images']['name'])) {
            foreach ($_FILES['images']['name'] as $key => $name) {
                if ($_FILES['images']['error'][$key] === UPLOAD_ERR_OK) {
                    $file = [
                        'name' => $name,
                        'type' => $_FILES['images']['type'][$key],
                        'tmp_name' => $_FILES['images']['tmp_name'][$key],
                        'error' => $_FILES['images']['error'][$key],
                        'size' => $_FILES['images']['size'][$key]
                    ];
                    $uploadResult = FileUpload::uploadImage($file, 'products');
                    if ($uploadResult['success']) {
                        $images[] = $uploadResult['path'];
                    }
                }
            }
        }
        
        $stmt = $db->prepare("UPDATE products SET name = ?, slug = ?, description = ?, shortDescription = ?, price = ?, originalPrice = ?, image = ?, images = ?, categoryId = ?, subcategoryId = ?, tags = ?, inStock = ?, stockQuantity = ?, badge = ?, author = ?, publisher = ?, isbn = ?, pages = ?, language = ?, format = ?, specifications = ? WHERE id = ?");
        
        $stmt->execute([
            $_POST['name'] ?? $product['name'],
            $slug,
            $_POST['description'] ?? $product['description'],
            $_POST['shortDescription'] ?? $product['shortDescription'],
            $_POST['price'] ?? $product['price'],
            !empty($_POST['originalPrice']) ? $_POST['originalPrice'] : null,
            $imagePath,
            setJsonField(!empty($images) ? $images : null),
            $_POST['categoryId'] ?? $product['categoryId'],
            !empty($_POST['subcategoryId']) ? $_POST['subcategoryId'] : null,
            setJsonField(!empty($_POST['tags']) ? (is_array($_POST['tags']) ? $_POST['tags'] : explode(',', $_POST['tags'])) : $product['tags']),
            isset($_POST['inStock']) ? 1 : 0,
            $_POST['stockQuantity'] ?? $product['stockQuantity'],
            $_POST['badge'] ?? $product['badge'],
            $_POST['author'] ?? $product['author'],
            $_POST['publisher'] ?? $product['publisher'],
            $_POST['isbn'] ?? $product['isbn'],
            !empty($_POST['pages']) ? $_POST['pages'] : null,
            $_POST['language'] ?? $product['language'],
            $_POST['format'] ?? $product['format'],
            setJsonField(!empty($_POST['specifications']) ? json_decode($_POST['specifications'], true) : $product['specifications']),
            $id
        ]);
        
        setFlash('success', 'Produit mis à jour avec succès');
        $this->redirect(baseUrl('admin/produits'));
    }

    public function productDelete($id) {
        require_once __DIR__ . '/../lib/CSRF.php';
        CSRF::validate();
        
        $db = db();
        // Hard delete product (products table doesn't have isActive column)
        $stmt = $db->prepare("DELETE FROM products WHERE id = ?");
        $stmt->execute([$id]);
        
        setFlash('success', 'Produit supprimé');
        $this->redirect(baseUrl('admin/produits'));
    }

    public function categories() {
        $categories = Category::all(true);
        $this->view('admin/categories/index', [
            'title' => 'Gérer les catégories',
            'layout' => 'admin',
            'categories' => $categories,
        ]);
    }

    public function categoryStore() {
        require_once __DIR__ . '/../lib/Helpers.php';
        require_once __DIR__ . '/../lib/CSRF.php';
        CSRF::validate();
        
        $db = db();
        $id = generateId('cat');
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $_POST['name'] ?? '')));
        
        // Ensure unique slug
        $slugBase = $slug;
        $counter = 1;
        $checkStmt = $db->prepare("SELECT id FROM categories WHERE slug = ?");
        while ($checkStmt->execute([$slug]) && $checkStmt->fetch()) {
            $slug = $slugBase . '-' . $counter++;
        }
        
        $stmt = $db->prepare("INSERT INTO categories (id, name, slug, description, image, sortOrder, isActive, parentId) 
                             VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        
        $stmt->execute([
            $id,
            $_POST['name'] ?? '',
            $slug,
            $_POST['description'] ?? null,
            $_POST['image'] ?? null,
            $_POST['sortOrder'] ?? 0,
            isset($_POST['isActive']) ? 1 : 1,
            !empty($_POST['parentId']) ? $_POST['parentId'] : null
        ]);
        
        setFlash('success', 'Catégorie créée avec succès');
        $this->redirect(baseUrl('admin/categories'));
    }

    public function categoryDelete($id) {
        require_once __DIR__ . '/../lib/CSRF.php';
        CSRF::validate();
        
        $db = db();
        // Check if category has products
        $checkStmt = $db->prepare("SELECT COUNT(*) FROM products WHERE categoryId = ? OR subcategoryId = ?");
        $checkStmt->execute([$id, $id]);
        $productCount = $checkStmt->fetchColumn();
        
        if ($productCount > 0) {
            setFlash('error', 'Impossible de supprimer une catégorie qui contient des produits');
            $this->redirect(baseUrl('admin/categories'));
            return;
        }
        
        // Check if category has children
        $checkStmt = $db->prepare("SELECT COUNT(*) FROM categories WHERE parentId = ?");
        $checkStmt->execute([$id]);
        $childrenCount = $checkStmt->fetchColumn();
        
        if ($childrenCount > 0) {
            setFlash('error', 'Impossible de supprimer une catégorie qui contient des sous-catégories');
            $this->redirect(baseUrl('admin/categories'));
            return;
        }
        
        // Soft delete
        $stmt = $db->prepare("UPDATE categories SET isActive = 0 WHERE id = ?");
        $stmt->execute([$id]);
        
        setFlash('success', 'Catégorie supprimée');
        $this->redirect(baseUrl('admin/categories'));
    }

    public function orders() {
        $page = (int)($_GET['page'] ?? 1);
        $status = $_GET['status'] ?? null;
        $search = $_GET['search'] ?? null;
        
        $db = db();
        $where = [];
        $params = [];
        
        if ($status) {
            $where[] = 'o.status = ?';
            $params[] = $status;
        }
        
        if ($search) {
            $where[] = '(o.orderNumber LIKE ? OR u.email LIKE ? OR u.name LIKE ?)';
            $searchTerm = '%' . $search . '%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }
        
        $whereClause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';
        
        $perPage = 20;
        $offset = ($page - 1) * $perPage;
        
        // Count total
        $countSql = "SELECT COUNT(*) as total FROM orders o LEFT JOIN users u ON o.userId = u.id $whereClause";
        $countStmt = $db->prepare($countSql);
        $countStmt->execute($params);
        $total = $countStmt->fetch()['total'];
        
        // Get orders
        $sql = "SELECT o.*, u.name as userName, u.email as userEmail 
                FROM orders o 
                LEFT JOIN users u ON o.userId = u.id 
                $whereClause
                ORDER BY o.createdAt DESC 
                LIMIT ? OFFSET ?";
        
        $params[] = $perPage;
        $params[] = $offset;
        
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $orders = $stmt->fetchAll();
        
        $this->view('admin/orders/index', [
            'title' => 'Gérer les commandes',
            'layout' => 'admin',
            'orders' => $orders,
            'pagination' => [
                'current' => $page,
                'total' => ceil($total / $perPage),
                'totalItems' => $total,
            ],
            'filters' => [
                'status' => $status,
                'search' => $search,
            ],
        ]);
    }

    public function orderDetail($id) {
        $order = Order::findById($id);
        $items = Order::items($id);
        $this->view('admin/orders/detail', [
            'title' => 'Commande #' . $order['orderNumber'],
            'layout' => 'admin',
            'order' => $order,
            'items' => $items,
        ]);
    }

    public function orderUpdateStatus($id) {
        $status = $_POST['status'] ?? null;
        if ($status) {
            Order::updateStatus($id, $status);
        }
        $this->redirect(baseUrl('admin/commandes/' . $id));
    }

    public function users() {
        $users = User::all();
        $this->view('admin/users/index', [
            'title' => 'Gérer les utilisateurs',
            'layout' => 'admin',
            'users' => $users,
        ]);
    }

    public function userUpdate($id) {
        require_once __DIR__ . '/../lib/CSRF.php';
        CSRF::validate();
        
        // Prevent modifying yourself (unless changing role)
        if ($id === Auth::id() && isset($_POST['role']) && $_POST['role'] !== 'admin') {
            setFlash('error', 'Vous ne pouvez pas retirer votre propre statut d\'administrateur');
            $this->redirect(route('admin.users'));
            return;
        }
        
        $data = [];
        if (isset($_POST['role'])) {
            $data['role'] = $_POST['role'];
        }
        if (isset($_POST['active'])) {
            $data['active'] = $_POST['active'] === '1';
        }
        
        if (!empty($data)) {
            User::update($id, $data);
            setFlash('success', 'Utilisateur mis à jour');
        }
        
        $this->redirect(route('admin.users'));
    }

    public function userDelete($id) {
        require_once __DIR__ . '/../lib/CSRF.php';
        CSRF::validate();
        
        // Prevent deleting yourself
        if ($id === Auth::id()) {
            setFlash('error', 'Vous ne pouvez pas supprimer votre propre compte');
            $this->redirect(route('admin.users'));
            return;
        }
        
        $db = db();
        // Soft delete
        $stmt = $db->prepare("UPDATE users SET active = 0 WHERE id = ?");
        $stmt->execute([$id]);
        
        setFlash('success', 'Utilisateur désactivé');
        $this->redirect(route('admin.users'));
    }
}


