<div class="max-w-7xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-4">
        Résultats de recherche<?= !empty($query) ? ' pour "' . e($query) . '"' : '' ?>
    </h1>
    
    <div class="flex flex-col lg:flex-row gap-6">
        <!-- Filters Sidebar -->
        <aside class="lg:w-72 flex-shrink-0">
            <form method="GET" action="<?= route('search.index') ?>" id="search-filter-form" class="bg-gray-50 rounded-lg p-4 space-y-4">
                <input type="hidden" name="query" value="<?= e($query) ?>">
                
                <div>
                    <h3 class="font-bold text-gray-900 mb-3">Catégorie</h3>
                    <select name="category" onchange="document.getElementById('search-filter-form').submit()" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        <option value="">Toutes les catégories</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= e($cat['id']) ?>" <?= ($filters['categoryId'] ?? '') === $cat['id'] ? 'selected' : '' ?>><?= e($cat['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div>
                    <h3 class="font-bold text-gray-900 mb-3">Prix</h3>
                    <div class="space-y-2">
                        <input type="number" name="minPrice" placeholder="Min" value="<?= e($filters['minPrice'] ?? '') ?>" 
                               onchange="document.getElementById('search-filter-form').submit()"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        <input type="number" name="maxPrice" placeholder="Max" value="<?= e($filters['maxPrice'] ?? '') ?>" 
                               onchange="document.getElementById('search-filter-form').submit()"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                    </div>
                </div>
                
                <div>
                    <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                        <input type="checkbox" name="inStock" value="1" <?= ($filters['inStock'] ?? false) ? 'checked' : '' ?> 
                               onchange="document.getElementById('search-filter-form').submit()"
                               class="rounded border-gray-300 text-primary focus:ring-primary">
                        <span>En stock uniquement</span>
                    </label>
                </div>
                
                <?php if (!empty($filters['categoryId']) || !empty($filters['minPrice']) || !empty($filters['maxPrice']) || !empty($filters['inStock'])): ?>
                    <a href="<?= route('search.index') ?>?query=<?= urlencode($query) ?>" class="block text-center text-sm text-primary hover:text-accent-rose">
                        Réinitialiser les filtres
                    </a>
                <?php endif; ?>
            </form>
        </aside>
        
        <!-- Results -->
        <div class="flex-1">
            <?php if (empty($products)): ?>
                <div class="text-center py-20">
                    <p class="text-gray-600 mb-4">Aucun résultat trouvé</p>
                    <p class="text-sm text-gray-500 mb-6">Essayez avec d'autres mots-clés ou modifiez les filtres</p>
                    <a href="<?= route('products.index') ?>" class="inline-block px-6 py-3 bg-primary text-white rounded-lg hover:opacity-90">
                        Voir tous les produits
                    </a>
                </div>
            <?php else: ?>
                <div class="mb-6 flex items-center justify-between">
                    <p class="text-gray-600">
                        <span class="font-semibold"><?= $pagination['totalItems'] ?? count($products) ?></span> résultat<?= ($pagination['totalItems'] ?? count($products)) > 1 ? 's' : '' ?> trouvé<?= ($pagination['totalItems'] ?? count($products)) > 1 ? 's' : '' ?>
                    </p>
                    <div class="flex items-center gap-2">
                        <label class="text-sm text-gray-600">Trier par:</label>
                        <form method="GET" action="<?= route('search.index') ?>" class="inline">
                            <input type="hidden" name="query" value="<?= e($query) ?>">
                            <?php foreach ($filters as $key => $value): ?>
                                <?php if ($key !== 'sortBy' && $key !== 'sortDirection' && $value): ?>
                                    <input type="hidden" name="<?= e($key) ?>" value="<?= e($value) ?>">
                                <?php endif; ?>
                            <?php endforeach; ?>
                            <select name="sortBy" onchange="this.form.submit()" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
                                <option value="createdAt" <?= ($filters['sortBy'] ?? 'createdAt') === 'createdAt' ? 'selected' : '' ?>>Pertinence</option>
                                <option value="price" <?= ($filters['sortBy'] ?? '') === 'price' ? 'selected' : '' ?>>Prix</option>
                                <option value="name" <?= ($filters['sortBy'] ?? '') === 'name' ? 'selected' : '' ?>>Nom</option>
                            </select>
                        </form>
                    </div>
                </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
            <?php foreach ($products as $product): ?>
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                        <a href="<?= route('product.show', ['slug' => $product['slug']]) ?>">
                        <img src="<?= baseUrl(ltrim($product['image'], '/')) ?>" alt="<?= e($product['name']) ?>" 
                             class="w-full h-48 object-cover">
                    </a>
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2">
                            <a href="<?= baseUrl('produit/' . $product['slug']) ?>" class="hover:text-primary">
                                <?= e($product['name']) ?>
                            </a>
                        </h3>
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-lg font-bold text-primary"><?= formatPrice($product['price']) ?></span>
                        </div>
                        <form action="<?= route('cart.add') ?>" method="POST" data-action="add-to-cart">
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
                <?php if (($pagination['total'] ?? 1) > 1): ?>
                    <div class="mt-8 flex justify-center">
                        <nav class="flex gap-2">
                            <?php
                            $queryParams = array_merge(['query' => $query], array_filter($filters, function($v, $k) {
                                return $k !== 'sortBy' && $k !== 'sortDirection' && $v;
                            }, ARRAY_FILTER_USE_BOTH));
                            ?>
                            <?php if ($pagination['current'] > 1): ?>
                                <a href="?<?= http_build_query(array_merge($queryParams, ['page' => $pagination['current'] - 1])) ?>" 
                                   class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">Précédent</a>
                            <?php endif; ?>
                            
                            <?php for ($i = max(1, $pagination['current'] - 2); $i <= min($pagination['total'], $pagination['current'] + 2); $i++): ?>
                                <a href="?<?= http_build_query(array_merge($queryParams, ['page' => $i])) ?>" 
                                   class="px-4 py-2 border rounded-lg <?= $i === $pagination['current'] ? 'bg-primary text-white border-primary' : 'border-gray-300 hover:bg-gray-50' ?>">
                                    <?= $i ?>
                                </a>
                            <?php endfor; ?>
                            
                            <?php if ($pagination['current'] < $pagination['total']): ?>
                                <a href="?<?= http_build_query(array_merge($queryParams, ['page' => $pagination['current'] + 1])) ?>" 
                                   class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">Suivant</a>
                            <?php endif; ?>
                        </nav>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>


