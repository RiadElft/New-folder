<?php
require_once __DIR__ . '/../../models/Cart.php';
require_once __DIR__ . '/../../models/Category.php';
require_once __DIR__ . '/../../lib/Auth.php';

$cartQuantity = Cart::totalQuantity();
$user = Auth::user();
$topCategories = Category::topLevel();

// Define desired order for navigation menu
$menuOrder = [
    'Parfums',
    'Parfum',
    'Livres',
    'Coffrets & Personnalisation',
    'Coffrets & personnalisation',
    'Jeux interactifs',
    'Calendriers',
    'Qamis'
];

// Reorder categories according to specified menu order
$orderedCategories = [];
$unorderedCategories = [];

// First, add categories in the specified order
foreach ($menuOrder as $orderName) {
    foreach ($topCategories as $key => $category) {
        if (stripos($category['name'], $orderName) !== false || stripos($orderName, $category['name']) !== false) {
            $orderedCategories[] = $category;
            unset($topCategories[$key]);
            break;
        }
    }
}

// Add remaining categories that weren't in the order list
$unorderedCategories = array_values($topCategories);
$navItems = array_merge($orderedCategories, $unorderedCategories);
$navItems = array_slice($navItems, 0, 8);
?>

<header class="bg-white sticky top-0 z-50 border-b border-neutral-light">
    <!-- Top Bar -->
    <div class="bg-primary text-white text-center py-2 text-sm">
        Découvrez notre nouvelle collection de livres et d'objets culturels
    </div>

    <!-- Header Main -->
    <div class="max-w-7xl mx-auto px-4 py-4">
        <div class="flex items-center justify-between gap-8">
            <!-- Search -->
            <div class="flex items-center gap-4 flex-1 max-w-md">
                <div class="relative flex-1">
                    <form action="<?= route('search.index') ?>" method="GET" class="relative w-full">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <input
                            type="text"
                            name="query"
                            id="search-input"
                            placeholder="Rechercher un livre, un parfum..."
                            autocomplete="off"
                            class="w-full pl-10 pr-24 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                        />
                        <div id="search-autocomplete" class="absolute top-full left-0 right-0 mt-1 bg-white border border-gray-300 rounded-lg shadow-lg z-50 hidden max-h-96 overflow-y-auto"></div>
                        <button
                            type="submit"
                            class="absolute right-2 top-1/2 transform -translate-y-1/2 px-4 py-1 bg-primary text-white rounded-md hover:opacity-90 transition-colors text-sm"
                        >
                            Rechercher
                        </button>
                    </form>
                </div>
                <script>
                // Search autocomplete
                (function() {
                    const input = document.getElementById('search-input');
                    const autocomplete = document.getElementById('search-autocomplete');
                    let timeout;
                    
                    if (input && autocomplete) {
                        input.addEventListener('input', function() {
                            clearTimeout(timeout);
                            const query = this.value.trim();
                            
                            if (query.length < 2) {
                                autocomplete.classList.add('hidden');
                                return;
                            }
                            
                            timeout = setTimeout(() => {
                                fetch('<?= route('api.search.autocomplete') ?>?q=' + encodeURIComponent(query))
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data.items && data.items.length > 0) {
                                            autocomplete.innerHTML = data.items.map(item => `
                                                <a href="${item.url}" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 border-b border-gray-100 last:border-0">
                                                    ${item.image ? `<img src="${item.image}" alt="" class="w-10 h-10 object-cover rounded">` : ''}
                                                    <div class="flex-1">
                                                        <div class="font-medium text-gray-900">${item.title}</div>
                                                        <div class="text-xs text-gray-500">${item.type === 'product' ? 'Produit' : 'Catégorie'}</div>
                                                    </div>
                                                </a>
                                            `).join('');
                                            autocomplete.classList.remove('hidden');
                                        } else {
                                            autocomplete.classList.add('hidden');
                                        }
                                    })
                                    .catch(() => {
                                        autocomplete.classList.add('hidden');
                                    });
                            }, 300);
                        });
                        
                        // Hide autocomplete when clicking outside
                        document.addEventListener('click', function(e) {
                            if (!input.contains(e.target) && !autocomplete.contains(e.target)) {
                                autocomplete.classList.add('hidden');
                            }
                        });
                    }
                })();
                </script>
            </div>

            <!-- Logo -->
            <div class="flex items-center gap-3">
                <a href="<?= route('home') ?>">
                    <img src="<?= assetUrl('logo.png') ?>" alt="Sultan Library" class="h-12 w-auto" />
                </a>
            </div>

            <!-- Icons -->
            <div class="flex items-center gap-6 flex-1 justify-end">
                <a href="<?= route('products.index') ?>" class="text-sm text-primary hover:text-accent-rose transition whitespace-nowrap">
                    Trouvez votre collection
                </a>
                <a href="<?= route('page.shipping') ?>" class="p-2 hover:bg-accent-beige rounded-md transition">
                    <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </a>
                <a href="<?= route('account.wishlist') ?>" class="p-2 hover:bg-accent-beige rounded-md transition">
                    <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                </a>
                <?php if ($user): ?>
                    <div class="relative group" id="user-menu-container">
                        <button onclick="toggleUserMenu()" class="p-2 hover:bg-accent-beige rounded-md transition flex items-center gap-2">
                            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <span class="text-sm text-primary hidden sm:inline"><?= e($user['name'] ?? explode('@', $user['email'])[0]) ?></span>
                            <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div id="user-menu" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-50 hidden group-hover:block">
                            <div class="py-2">
                                <a href="<?= route('account.index') ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Mon Compte
                                </a>
                                <?php if (Auth::isAdmin()): ?>
                                    <a href="<?= route('admin.dashboard') ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        Admin Panel
                                    </a>
                                <?php endif; ?>
                                <div class="border-t border-gray-200 my-1"></div>
                                <form action="<?= route('auth.logout') ?>" method="POST" class="block">
                                    <?= CSRF::field() ?>
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                        Déconnexion
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <script>
                    function toggleUserMenu() {
                        const menu = document.getElementById('user-menu');
                        menu.classList.toggle('hidden');
                    }
                    // Close menu when clicking outside
                    document.addEventListener('click', function(event) {
                        const container = document.getElementById('user-menu-container');
                        const menu = document.getElementById('user-menu');
                        if (container && menu && !container.contains(event.target)) {
                            menu.classList.add('hidden');
                        }
                    });
                    </script>
                <?php else: ?>
                    <a href="<?= route('auth.login') ?>" class="p-2 hover:bg-accent-beige rounded-md transition">
                        <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </a>
                <?php endif; ?>
                <button onclick="openCartDrawer()" class="relative p-2 hover:bg-accent-beige rounded-md transition">
                    <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    <?php if ($cartQuantity > 0): ?>
                        <span class="cart-badge absolute -top-1 -right-1 bg-primary text-white text-[10px] leading-none px-1.5 py-0.5 rounded">
                            <?= $cartQuantity ?>
                        </span>
                    <?php endif; ?>
                </button>
                
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="border-t border-primary/20 bg-gradient-to-b from-accent-beige/10 to-white">
        <div class="max-w-7xl mx-auto px-4">
            <div class="border-b border-primary/10 mb-2"></div>
            <ul class="flex items-center justify-center gap-8 py-4 text-sm overflow-visible font-medium">
                <?php foreach ($navItems as $category): 
                    $categoryUrl = route('category.show', ['slug' => $category['slug']]);
                    // Check if current route is category.show and slug matches
                    $currentSlug = Navigation::getRouteParam('slug');
                    $isActive = isRouteName('category.show') && $currentSlug === $category['slug'];
                    $children = Category::children($category['id']);
                ?>
                    <li class="group relative whitespace-nowrap">
                        <a 
                            href="<?= $categoryUrl ?>" 
                            class="relative px-4 py-2 text-neutral-dark hover:text-primary transition-all duration-200 <?= $isActive ? 'text-primary font-semibold' : 'font-medium' ?> hover:bg-primary/5 rounded-md"
                        >
                            <?= e($category['name']) ?>
                            <?php if ($isActive): ?>
                                <span class="absolute bottom-0 left-1/2 -translate-x-1/2 w-8 h-0.5 bg-primary rounded-full"></span>
                            <?php endif; ?>
                        </a>
                        <?php if (!empty($children)): ?>
                            <?php 
                            // Fetch two featured/latest products for the mega menu
                            $db = db();
                            $pstmt = $db->prepare("SELECT id, name, slug, price, image, badge FROM products WHERE categoryId = ? ORDER BY createdAt DESC LIMIT 2");
                            $pstmt->execute([$category['id']]);
                            $featuredInMenu = $pstmt->fetchAll();
                            // Split children into two columns
                            $mid = (int)ceil(count($children) / 2);
                            $childrenCol1 = array_slice($children, 0, $mid);
                            $childrenCol2 = array_slice($children, $mid);
                            ?>
                            <div class="absolute left-1/2 -translate-x-1/2 top-full mt-0 pt-2 hidden group-hover:block z-[60]">
                                <div class="w-screen max-w-7xl bg-white border border-gray-200 rounded-lg shadow-2xl p-6">
                                    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
                                        <!-- Columns of subcategories -->
                                        <div class="md:col-span-2 grid grid-cols-2 gap-6">
                                            <ul class="space-y-2">
                                                <?php foreach ($childrenCol1 as $child): ?>
                                                    <li>
                                                        <a href="<?= route('category.show', ['slug' => $child['slug']]) ?>" class="text-sm text-neutral-dark hover:text-primary">
                                                            <?= e($child['name']) ?>
                                                        </a>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                            <ul class="space-y-2">
                                                <?php foreach ($childrenCol2 as $child): ?>
                                                    <li>
                                                        <a href="<?= route('category.show', ['slug' => $child['slug']]) ?>" class="text-sm text-neutral-dark hover:text-primary">
                                                            <?= e($child['name']) ?>
                                                        </a>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </div>

                                        <!-- Feature cards on right -->
                                        <?php foreach ($featuredInMenu as $prod): ?>
                                            <a href="<?= route('product.show', ['slug' => $prod['slug']]) ?>" class="hidden md:block bg-white rounded-lg border hover:shadow-lg transition overflow-hidden">
                                                <div class="relative aspect-[4/3] overflow-hidden">
                                                    <img src="<?= baseUrl(ltrim($prod['image'], '/')) ?>" alt="<?= e($prod['name']) ?>" class="w-full h-full object-cover">
                                                    <?php if (!empty($prod['badge'])): ?>
                                                        <span class="absolute top-2 left-2 text-[10px] font-semibold px-2 py-0.5 rounded bg-accent-beige text-primary uppercase">
                                                            <?= e($prod['badge']) ?>
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="absolute top-2 left-2 text-[10px] font-semibold px-2 py-0.5 rounded bg-accent-beige text-primary uppercase">Nouveauté</span>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="p-3">
                                                    <div class="text-sm text-neutral-dark line-clamp-2 mb-1"><?= e($prod['name']) ?></div>
                                                    <div class="text-xs text-primary font-semibold">Voir →</div>
                                                </div>
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </nav>
</header>


