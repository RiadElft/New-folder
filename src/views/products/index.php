<?php
$currentPage = $pagination['current'] ?? 1;
$totalPages = $pagination['total'] ?? 1;
$sortBy = $_GET['sortBy'] ?? 'createdAt';
$sortDirection = $_GET['sortDirection'] ?? 'DESC';
$currentSort = $sortBy === 'price' && $sortDirection === 'asc' ? 'price-asc' : 
               ($sortBy === 'price' && $sortDirection === 'desc' ? 'price-desc' :
               ($sortBy === 'name' ? 'name' : 
               ($sortBy === 'createdAt' && $sortDirection === 'desc' ? 'newest' : 'pertinence')));
?>

<div class="bg-white min-h-screen">
    <div class="max-w-7xl mx-auto px-4 py-6">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-3">Tous les Produits</h1>
            <p class="text-gray-700 text-lg">Découvrez notre collection complète de livres, parfums et objets culturels islamiques</p>
        </div>

        <div class="flex flex-col lg:flex-row gap-6">
            <!-- Sidebar Filters -->
            <aside class="lg:w-72 flex-shrink-0">
                <form method="GET" action="<?= route('products.index') ?>" id="filter-form">
                    <div class="space-y-6">
                        <!-- Availability Filter -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h3 class="font-bold text-gray-900 mb-3">Disponibilité</h3>
                            <div class="space-y-2">
                                <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                                    <input type="checkbox" name="inStock" value="1" <?= isset($_GET['inStock']) && $_GET['inStock'] === '1' ? 'checked' : '' ?> 
                                           onchange="document.getElementById('filter-form').submit()"
                                           class="rounded border-gray-300 text-primary focus:ring-primary">
                                    <span>En stock uniquement</span>
                                </label>
                            </div>
                        </div>

                        <!-- Type Filter -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h3 class="font-bold text-gray-900 mb-3">Type</h3>
                            <div class="space-y-2">
                                <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                                    <input type="radio" name="badge" value="new" <?= ($_GET['badge'] ?? '') === 'new' ? 'checked' : '' ?> 
                                           onchange="document.getElementById('filter-form').submit()"
                                           class="rounded-full border-gray-300 text-primary focus:ring-primary">
                                    <span>Nouveautés</span>
                                </label>
                                <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                                    <input type="radio" name="badge" value="bestseller" <?= ($_GET['badge'] ?? '') === 'bestseller' ? 'checked' : '' ?> 
                                           onchange="document.getElementById('filter-form').submit()"
                                           class="rounded-full border-gray-300 text-primary focus:ring-primary">
                                    <span>Meilleures ventes</span>
                                </label>
                                <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                                    <input type="radio" name="badge" value="promo" <?= ($_GET['badge'] ?? '') === 'promo' ? 'checked' : '' ?> 
                                           onchange="document.getElementById('filter-form').submit()"
                                           class="rounded-full border-gray-300 text-primary focus:ring-primary">
                                    <span>Promotions</span>
                                </label>
                                <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                                    <input type="radio" name="badge" value="" <?= empty($_GET['badge']) ? 'checked' : '' ?> 
                                           onchange="document.getElementById('filter-form').submit()"
                                           class="rounded-full border-gray-300 text-primary focus:ring-primary">
                                    <span>Tous</span>
                                </label>
                            </div>
                        </div>

                        <!-- Price Range Filter -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h3 class="font-bold text-gray-900 mb-3">Prix</h3>
                            <div class="space-y-3">
                                <div class="flex items-center gap-3">
                                    <input type="number" name="minPrice" placeholder="Min" value="<?= e($_GET['minPrice'] ?? '') ?>" 
                                           onchange="document.getElementById('filter-form').submit()"
                                           class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                                    <span class="text-gray-500">-</span>
                                    <input type="number" name="maxPrice" placeholder="Max" value="<?= e($_GET['maxPrice'] ?? '') ?>" 
                                           onchange="document.getElementById('filter-form').submit()"
                                           class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </aside>

            <!-- Products Section -->
            <div class="flex-1">
                <!-- Sort & Count Bar -->
                <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-200">
                    <div class="text-sm text-gray-600">
                        <span><span class="font-semibold"><?= count($products) ?></span> produit<?= count($products) > 1 ? 's' : '' ?></span>
                    </div>
                    <div class="flex items-center gap-2">
                        <label class="text-sm text-gray-600">Trier par:</label>
                        <form method="GET" action="<?= route('products.index') ?>" class="inline">
                            <?php foreach ($_GET as $key => $value): ?>
                                <?php if ($key !== 'sortBy' && $key !== 'sortDirection'): ?>
                                    <input type="hidden" name="<?= e($key) ?>" value="<?= e($value) ?>">
                                <?php endif; ?>
                            <?php endforeach; ?>
                            <select name="sort" onchange="this.form.submit()" 
                                    class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                                <option value="pertinence" <?= $sortBy === 'createdAt' ? 'selected' : '' ?>>Pertinence</option>
                                <option value="price-asc" <?= $sortBy === 'price' && $sortDirection === 'asc' ? 'selected' : '' ?>>Prix croissant</option>
                                <option value="price-desc" <?= $sortBy === 'price' && $sortDirection === 'desc' ? 'selected' : '' ?>>Prix décroissant</option>
                                <option value="name" <?= $sortBy === 'name' ? 'selected' : '' ?>>Nom A-Z</option>
                                <option value="newest" <?= $sortBy === 'createdAt' && $sortDirection === 'desc' ? 'selected' : '' ?>>Nouveautés</option>
                            </select>
                        </form>
                    </div>
                </div>

                <!-- Products Grid -->
                <?php if (empty($products)): ?>
                    <div class="text-center py-20">
                        <p class="text-gray-600 mb-4">Aucun produit trouvé</p>
                        <p class="text-sm text-gray-500">Essayez de modifier vos filtres</p>
                    </div>
                <?php else: ?>
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                        <?php foreach ($products as $product): ?>
                            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                                <a href="<?= route('product.show', ['slug' => $product['slug']]) ?>">
                                    <img src="<?= baseUrl(ltrim($product['image'], '/')) ?>" alt="<?= e($product['name']) ?>" 
                                         class="w-full h-48 object-cover">
                                </a>
                                <div class="p-4">
                                    <?php if ($product['badge']): ?>
                                        <span class="inline-block text-xs bg-accent-beige text-primary px-2 py-1 rounded mb-2 font-bold">
                                            <?= strtoupper($product['badge']) ?>
                                        </span>
                                    <?php endif; ?>
                                    <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2">
                                        <a href="<?= baseUrl('produit/' . $product['slug']) ?>" class="hover:text-primary">
                                            <?= e($product['name']) ?>
                                        </a>
                                    </h3>
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex items-center gap-1">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <svg class="w-4 h-4 <?= $i <= round($product['rating']) ? 'text-yellow-400' : 'text-gray-300' ?>" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                </svg>
                                            <?php endfor; ?>
                                            <span class="text-xs text-gray-500 ml-1">(<?= $product['reviewCount'] ?>)</span>
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-between mb-3">
                                        <div>
                                            <span class="text-lg font-bold text-primary"><?= formatPrice($product['price']) ?></span>
                                            <?php if ($product['originalPrice'] && $product['originalPrice'] > $product['price']): ?>
                                                <span class="text-sm text-gray-500 line-through ml-2"><?= formatPrice($product['originalPrice']) ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <form action="<?= route('cart.add') ?>" method="POST" data-action="add-to-cart" class="mt-2">
                                        <?= CSRF::field() ?>
                                        <input type="hidden" name="productId" value="<?= e($product['id']) ?>">
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="w-full px-4 py-2 bg-primary text-white rounded-lg hover:opacity-90 transition text-sm">
                                            Ajouter au panier
                                        </button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Pagination -->
                    <?php if ($totalPages > 1): ?>
                        <div class="mt-8 flex justify-center">
                            <nav class="flex gap-2">
                                <?php if ($currentPage > 1): ?>
                                    <a href="?page=<?= $currentPage - 1 ?><?= !empty($_GET) ? '&' . http_build_query(array_diff_key($_GET, ['page' => ''])) : '' ?>" 
                                       class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">Précédent</a>
                                <?php endif; ?>
                                
                                <?php for ($i = max(1, $currentPage - 2); $i <= min($totalPages, $currentPage + 2); $i++): ?>
                                    <a href="?page=<?= $i ?><?= !empty($_GET) ? '&' . http_build_query(array_diff_key($_GET, ['page' => ''])) : '' ?>" 
                                       class="px-4 py-2 border rounded-lg <?= $i === $currentPage ? 'bg-primary text-white border-primary' : 'border-gray-300 hover:bg-gray-50' ?>">
                                        <?= $i ?>
                                    </a>
                                <?php endfor; ?>
                                
                                <?php if ($currentPage < $totalPages): ?>
                                    <a href="?page=<?= $currentPage + 1 ?><?= !empty($_GET) ? '&' . http_build_query(array_diff_key($_GET, ['page' => ''])) : '' ?>" 
                                       class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">Suivant</a>
                                <?php endif; ?>
                            </nav>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

