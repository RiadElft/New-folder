<?php
$currentPage = $pagination['current'] ?? 1;
$totalPages = $pagination['total'] ?? 1;
?>

<div class="max-w-7xl mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <nav class="mb-6">
        <ol class="flex items-center space-x-2 text-sm">
            <li><a href="<?= route('home') ?>" class="text-gray-500 hover:text-primary">Accueil</a></li>
            <li><span class="text-gray-400">/</span></li>
            <li><span class="text-gray-900"><?= e($category['name']) ?></span></li>
        </ol>
    </nav>

    <!-- Category Header -->
    <div class="mb-8">
        <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-3"><?= e($category['name']) ?></h1>
        <?php if ($category['description']): ?>
            <p class="text-gray-700 text-lg"><?= e($category['description']) ?></p>
        <?php endif; ?>
    </div>

    <!-- Subcategories -->
    <?php if (!empty($category['children'])): ?>
        <div class="mb-8">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Sous-catégories</h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                <?php foreach ($category['children'] as $subcat): ?>
                    <a href="<?= route('category.show', ['slug' => $subcat['slug']]) ?>" 
                       class="bg-white rounded-lg shadow-md p-4 text-center hover:shadow-lg transition">
                        <h3 class="font-medium text-gray-900"><?= e($subcat['name']) ?></h3>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- Products -->
    <div class="mb-6">
        <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-200">
            <div class="text-sm text-gray-600">
                <span><span class="font-semibold"><?= count($products) ?></span> produit<?= count($products) > 1 ? 's' : '' ?></span>
            </div>
            <form method="GET" action="<?= route('category.show', ['slug' => $category['slug']]) ?>" class="flex items-center gap-2">
                <label class="text-sm text-gray-600">Trier par:</label>
                <select name="sortBy" onchange="this.form.submit()" 
                        class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                    <option value="createdAt" <?= ($_GET['sortBy'] ?? 'createdAt') === 'createdAt' ? 'selected' : '' ?>>Pertinence</option>
                    <option value="price" <?= ($_GET['sortBy'] ?? '') === 'price' && ($_GET['sortDirection'] ?? '') === 'asc' ? 'selected' : '' ?>>Prix croissant</option>
                    <option value="price" <?= ($_GET['sortBy'] ?? '') === 'price' && ($_GET['sortDirection'] ?? '') === 'desc' ? 'selected' : '' ?>>Prix décroissant</option>
                    <option value="name" <?= ($_GET['sortBy'] ?? '') === 'name' ? 'selected' : '' ?>>Nom A-Z</option>
                </select>
                <input type="hidden" name="sortDirection" value="<?= ($_GET['sortBy'] ?? '') === 'price' ? ($_GET['sortDirection'] ?? 'DESC') : 'DESC' ?>">
            </form>
        </div>

        <?php if (empty($products)): ?>
            <div class="text-center py-20">
                <p class="text-gray-600 mb-4">Aucun produit dans cette catégorie</p>
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


