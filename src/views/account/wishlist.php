<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Mes Favoris</h1>
        <form action="<?= route('auth.logout') ?>" method="POST" class="inline">
            <?= CSRF::field() ?>
            <button type="submit" class="px-4 py-2 text-sm text-red-600 border border-red-300 rounded-lg hover:bg-red-50 transition">
                Déconnexion
            </button>
        </form>
    </div>
    
    <?php if (empty($items)): ?>
        <div class="text-center py-20">
            <svg class="w-24 h-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
            </svg>
            <p class="text-gray-600 mb-4">Votre liste de favoris est vide</p>
            <p class="text-sm text-gray-500 mb-6">Ajoutez des produits à vos favoris pour les retrouver facilement</p>
            <a href="<?= route('products.index') ?>" class="inline-block px-6 py-3 bg-primary text-white rounded-lg hover:opacity-90 transition">
                Découvrir les produits
            </a>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
            <?php foreach ($items as $item): ?>
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                    <a href="<?= route('product.show', ['slug' => $item['slug']]) ?>">
                        <img src="<?= assetUrl(ltrim($item['image'], '/')) ?>" alt="<?= e($item['name']) ?>" 
                             class="w-full h-48 object-cover">
                    </a>
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2">
                            <a href="<?= route('product.show', ['slug' => $item['slug']]) ?>" class="hover:text-primary">
                                <?= e($item['name']) ?>
                            </a>
                        </h3>
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-lg font-bold text-primary"><?= formatPrice($item['price']) ?></span>
                        </div>
                        <div class="flex gap-2">
                            <form action="<?= route('cart.add') ?>" method="POST" data-action="add-to-cart" class="flex-1">
                                <?= CSRF::field() ?>
                                <input type="hidden" name="productId" value="<?= e($item['id']) ?>">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="w-full px-4 py-2 bg-primary text-white rounded-lg hover:opacity-90 transition text-sm">
                                    Ajouter au panier
                                </button>
                            </form>
                            <form action="<?= route('account.wishlist.remove') ?>" method="POST" data-action="remove-wishlist" class="flex-shrink-0">
                                <?= CSRF::field() ?>
                                <input type="hidden" name="productId" value="<?= e($item['id']) ?>">
                                <button type="submit" class="px-4 py-2 text-red-600 hover:text-red-800 border border-red-300 rounded-lg hover:bg-red-50 transition">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>


