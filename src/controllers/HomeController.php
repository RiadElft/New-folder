<?php
/**
 * Home Controller
 */

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../models/Hero.php';

class HomeController extends BaseController {
    public function index() {
        $featuredProducts = Product::featured(8);
        $heroIds = Hero::getProductIds();
        $heroProducts = [];
        if (!empty($heroIds)) {
            $db = db();
            // Preserve the ordering of $heroIds
            $placeholders = implode(',', array_fill(0, count($heroIds), '?'));
            $stmt = $db->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
            $stmt->execute($heroIds);
            $found = $stmt->fetchAll();
            $map = [];
            foreach ($found as $p) { $map[$p['id']] = $p; }
            foreach ($heroIds as $id) { if (isset($map[$id])) { $heroProducts[] = $map[$id]; } }
        }
        $topCategories = Category::topLevel();
        
        // Add product counts to categories
        $db = db();
        foreach ($topCategories as &$category) {
            $countStmt = $db->prepare("SELECT COUNT(*) as count FROM products WHERE categoryId = ? AND inStock = 1");
            $countStmt->execute([$category['id']]);
            $category['productCount'] = $countStmt->fetch()['count'] ?? 0;
        }
        
        // Get bestsellers
        $bestsellerProducts = Product::all(['badge' => 'bestseller'], 1, 8);
        
        // Get latest products (Nouveautés)
        $db = db();
        $latestProducts = [];
        try {
            $latestStmt = $db->query("SELECT * FROM products WHERE inStock = 1 ORDER BY createdAt DESC LIMIT 8");
            $latestProducts = $latestStmt->fetchAll();
            // Process JSON fields
            foreach ($latestProducts as &$product) {
                require_once __DIR__ . '/../lib/Helpers.php';
                $product['images'] = getJsonField($product['images']);
                $product['tags'] = getJsonField($product['tags']);
                $product['specifications'] = getJsonField($product['specifications']);
            }
        } catch (Exception $e) {
            $latestProducts = [];
        }
        
        // Emphasize perfumes on home page
        $db = db();
        $perfumeProducts = [];
        $perfumeUrl = route('products.index') . '?q=parfum';
        try {
            // Fetch up to 8 perfumes by type or by category slug/name containing 'parfum'
            $perfumeSql = "SELECT p.* FROM products p 
                           LEFT JOIN categories c ON p.categoryId = c.id
                           WHERE (LOWER(p.type) = 'parfum' OR LOWER(p.type) = 'perfume' 
                                  OR LOWER(c.slug) LIKE '%parfum%' OR LOWER(c.name) LIKE '%parfum%')
                           ORDER BY p.createdAt DESC LIMIT 8";
            $perfumeProducts = $db->query($perfumeSql)->fetchAll();
            // Try to find a dedicated perfume category for the CTA
            $cStmt = $db->query("SELECT slug FROM categories WHERE (LOWER(slug) LIKE '%parfum%' OR LOWER(name) LIKE '%parfum%') AND parentId IS NULL LIMIT 1");
            $row = $cStmt->fetch();
            if ($row && !empty($row['slug'])) {
                $perfumeUrl = route('category.show', ['slug' => $row['slug']]);
            }
        } catch (Exception $e) {
            $perfumeProducts = [];
        }
        
        $this->view('home/index', [
            'title' => 'Accueil',
            'featuredProducts' => $featuredProducts,
            'heroProducts' => $heroProducts,
            'topCategories' => $topCategories,
            'bestsellerProducts' => $bestsellerProducts['items'] ?? [],
            'latestProducts' => $latestProducts,
            'perfumeProducts' => $perfumeProducts,
            'perfumeUrl' => $perfumeUrl,
        ]);
    }
}


