<?php
// Always include main image first, then any additional images
$additionalImages = [];
if (!empty($product['images']) && is_array($product['images'])) {
    $additionalImages = array_values(array_filter($product['images']));
}
$images = array_values(array_unique(array_merge([$product['image']], $additionalImages)));
$specs = $product['specifications'] ?? [];
?>

<div class="max-w-7xl mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <nav class="mb-6">
        <ol class="flex items-center space-x-2 text-sm">
            <li><a href="<?= route('home') ?>" class="text-gray-500 hover:text-primary">Accueil</a></li>
            <li><span class="text-gray-400">/</span></li>
            <?php if (!empty($product['categorySlug'])): ?>
                <li><a href="<?= route('category.show', ['slug' => $product['categorySlug']]) ?>" class="text-gray-500 hover:text-primary"><?= e($product['categoryName'] ?? 'Produits') ?></a></li>
                <li><span class="text-gray-400">/</span></li>
            <?php endif; ?>
            <li><span class="text-gray-900"><?= e($product['name']) ?></span></li>
        </ol>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
        <!-- Images -->
        <div>
            <div class="mb-4">
                <img id="main-image" src="<?= baseUrl(ltrim($images[0], '/')) ?>" alt="<?= e($product['name']) ?>" 
                     class="w-full h-96 object-cover rounded-lg">
            </div>
            <?php if (count($images) > 1): ?>
                <div class="grid grid-cols-4 gap-2">
                    <?php foreach ($images as $index => $img): ?>
                        <img src="<?= baseUrl(ltrim($img, '/')) ?>" alt="" 
                             onclick="document.getElementById('main-image').src = this.src"
                             class="product-gallery-thumbnail w-full h-20 object-cover rounded cursor-pointer border-2 <?= $index === 0 ? 'border-primary' : 'border-transparent' ?> hover:border-primary">
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Product Info -->
        <div>
            <?php if ($product['badge']): ?>
                <span class="inline-block text-xs bg-accent-beige text-primary px-3 py-1 rounded mb-3 font-bold">
                    <?= strtoupper($product['badge']) ?>
                </span>
            <?php endif; ?>
            
            <h1 class="text-3xl font-bold text-gray-900 mb-4"><?= e($product['name']) ?></h1>
            
            <div class="flex items-center gap-2 mb-4">
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <svg class="w-5 h-5 <?= $i <= round($product['rating']) ? 'text-yellow-400' : 'text-gray-300' ?>" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                    </svg>
                <?php endfor; ?>
                <span class="text-sm text-gray-600">(<?= $product['reviewCount'] ?> avis)</span>
            </div>

            <div class="mb-6">
                <div class="flex items-baseline gap-3">
                    <span class="text-3xl font-bold text-primary"><?= formatPrice($product['price']) ?></span>
                    <?php if ($product['originalPrice'] && $product['originalPrice'] > $product['price']): ?>
                        <span class="text-xl text-gray-500 line-through"><?= formatPrice($product['originalPrice']) ?></span>
                        <span class="text-sm bg-red-100 text-red-800 px-2 py-1 rounded">
                            -<?= round((1 - $product['price'] / $product['originalPrice']) * 100) ?>%
                        </span>
                    <?php endif; ?>
                </div>
            </div>

            <?php if ($product['shortDescription']): ?>
                <p class="text-gray-700 mb-6"><?= e($product['shortDescription']) ?></p>
            <?php endif; ?>

            <?php if ($product['inStock']): ?>
                <div class="mb-6">
                    <span class="text-green-600 font-semibold">✓ En stock</span>
                    <?php if ($product['stockQuantity'] > 0): ?>
                        <span class="text-sm text-gray-600">(<?= $product['stockQuantity'] ?> disponibles)</span>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="mb-6">
                    <span class="text-red-600 font-semibold">Rupture de stock</span>
                </div>
            <?php endif; ?>

            <form action="<?= route('cart.add') ?>" method="POST" data-action="add-to-cart" class="mb-6">
                <?= CSRF::field() ?>
                <input type="hidden" name="productId" value="<?= e($product['id']) ?>">
                <div class="flex items-center gap-4 mb-4">
                    <label class="text-sm font-medium">Quantité:</label>
                    <div class="quantity-selector flex items-center border border-gray-300 rounded">
                        <button type="button" data-action="decrease" class="px-3 py-2 hover:bg-gray-100">-</button>
                        <input type="number" name="quantity" value="1" min="1" max="<?= $product['stockQuantity'] ?? 99 ?>" 
                               class="w-20 px-3 py-2 text-center border-0 focus:outline-none">
                        <button type="button" data-action="increase" class="px-3 py-2 hover:bg-gray-100">+</button>
                    </div>
                </div>
                <button type="submit" class="w-full px-6 py-3 bg-primary text-white rounded-lg hover:opacity-90 transition font-semibold text-lg">
                    Ajouter au panier
                </button>
            </form>

            <div class="flex gap-4">
                <?php
                require_once __DIR__ . '/../../lib/Auth.php';
                $isInWishlist = false;
                if (Auth::check()) {
                    require_once __DIR__ . '/../../models/Wishlist.php';
                    $isInWishlist = Wishlist::has(Auth::id(), $product['id']);
                }
                ?>
                <?php if (Auth::check()): ?>
                    <?php if ($isInWishlist): ?>
                        <form action="<?= route('account.wishlist.remove') ?>" method="POST" data-action="remove-wishlist" class="flex-1">
                            <?= CSRF::field() ?>
                            <input type="hidden" name="productId" value="<?= e($product['id']) ?>">
                            <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2 border-2 border-red-300 text-red-600 rounded-lg hover:bg-red-50 transition">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path>
                                </svg>
                                Retirer des favoris
                            </button>
                        </form>
                    <?php else: ?>
                        <form action="<?= route('account.wishlist.add') ?>" method="POST" data-action="add-wishlist" class="flex-1">
                            <?= CSRF::field() ?>
                            <input type="hidden" name="productId" value="<?= e($product['id']) ?>">
                            <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2 border-2 border-gray-300 text-gray-600 rounded-lg hover:bg-gray-50 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                                Ajouter aux favoris
                            </button>
                        </form>
                    <?php endif; ?>
                <?php else: ?>
                    <a href="<?= route('auth.login') ?>" class="flex-1 flex items-center justify-center gap-2 px-4 py-2 border-2 border-gray-300 text-gray-600 rounded-lg hover:bg-gray-50 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                        Ajouter aux favoris
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="border-t border-gray-200 pt-8">
        <div class="flex gap-6 mb-6 border-b border-gray-200">
            <button onclick="showTab('description')" class="tab-button pb-4 px-4 border-b-2 border-primary font-semibold text-primary">
                Description
            </button>
            <button onclick="showTab('details')" class="tab-button pb-4 px-4 border-b-2 border-transparent text-gray-600 hover:text-primary">
                Caractéristiques
            </button>
            <button onclick="showTab('reviews')" class="tab-button pb-4 px-4 border-b-2 border-transparent text-gray-600 hover:text-primary">
                Avis (<?= $reviews['total'] ?? 0 ?>)
            </button>
        </div>

        <div id="description-tab" class="tab-content">
            <div class="prose max-w-none">
                <p class="text-gray-700"><?= nl2br(e($product['description'] ?? $product['shortDescription'] ?? 'Aucune description disponible.')) ?></p>
            </div>
        </div>

        <div id="details-tab" class="tab-content hidden">
            <div class="space-y-3">
                <?php if ($product['author']): ?>
                    <div class="flex border-b border-gray-200 py-2">
                        <span class="font-medium text-gray-900 w-40">Auteur:</span>
                        <span class="text-gray-700"><?= e($product['author']) ?></span>
                    </div>
                <?php endif; ?>
                <?php if ($product['publisher']): ?>
                    <div class="flex border-b border-gray-200 py-2">
                        <span class="font-medium text-gray-900 w-40">Éditeur:</span>
                        <span class="text-gray-700"><?= e($product['publisher']) ?></span>
                    </div>
                <?php endif; ?>
                <?php if ($product['format']): ?>
                    <div class="flex border-b border-gray-200 py-2">
                        <span class="font-medium text-gray-900 w-40">Format:</span>
                        <span class="text-gray-700"><?= e($product['format']) ?></span>
                    </div>
                <?php endif; ?>
                <?php if ($product['pages']): ?>
                    <div class="flex border-b border-gray-200 py-2">
                        <span class="font-medium text-gray-900 w-40">Nombre de pages:</span>
                        <span class="text-gray-700"><?= e($product['pages']) ?></span>
                    </div>
                <?php endif; ?>
                <?php if ($product['language']): ?>
                    <div class="flex border-b border-gray-200 py-2">
                        <span class="font-medium text-gray-900 w-40">Langue:</span>
                        <span class="text-gray-700"><?= e($product['language']) ?></span>
                    </div>
                <?php endif; ?>
                <?php if ($product['isbn']): ?>
                    <div class="flex border-b border-gray-200 py-2">
                        <span class="font-medium text-gray-900 w-40">ISBN:</span>
                        <span class="text-gray-700"><?= e($product['isbn']) ?></span>
                    </div>
                <?php endif; ?>
                <?php if (is_array($specs)): ?>
                    <?php foreach ($specs as $key => $value): ?>
                        <div class="flex border-b border-gray-200 py-2">
                            <span class="font-medium text-gray-900 w-40"><?= e($key) ?>:</span>
                            <span class="text-gray-700"><?= e($value) ?></span>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
        
        <div id="reviews-tab" class="tab-content hidden">
            <?php
            require_once __DIR__ . '/../../lib/Auth.php';
            $canReview = Auth::check();
            $hasReviewed = false;
            if ($canReview) {
                require_once __DIR__ . '/../../models/Review.php';
                $db = db();
                $checkStmt = $db->prepare("SELECT id FROM reviews WHERE userId = ? AND productId = ?");
                $checkStmt->execute([Auth::id(), $product['id']]);
                $hasReviewed = $checkStmt->fetch() !== false;
            }
            ?>
            
            <!-- Add Review Form -->
            <?php if ($canReview && !$hasReviewed): ?>
                <div class="bg-gray-50 rounded-lg p-6 mb-8">
                    <h3 class="text-lg font-semibold mb-4">Donner votre avis</h3>
                    <form action="<?= baseUrl('api/reviews') ?>" method="POST" data-action="add-review" class="space-y-4">
                        <?= CSRF::field() ?>
                        <input type="hidden" name="productId" value="<?= e($product['id']) ?>">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Note</label>
                            <div class="flex gap-2" id="rating-input">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <button type="button" onclick="setRating(<?= $i ?>)" class="rating-star w-8 h-8 text-gray-300 hover:text-yellow-400 transition" data-rating="<?= $i ?>">
                                        <svg class="w-full h-full" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                    </button>
                                <?php endfor; ?>
                            </div>
                            <input type="hidden" name="rating" id="rating-value" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Commentaire</label>
                            <textarea name="comment" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"></textarea>
                        </div>
                        <button type="submit" class="px-6 py-2 bg-primary text-white rounded-lg hover:opacity-90">
                            Publier l'avis
                        </button>
                    </form>
                </div>
            <?php elseif ($canReview && $hasReviewed): ?>
                <div class="bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded mb-6">
                    Vous avez déjà laissé un avis pour ce produit.
                </div>
            <?php else: ?>
                <div class="bg-gray-50 border border-gray-200 text-gray-700 px-4 py-3 rounded mb-6">
                    <a href="<?= route('auth.login') ?>" class="text-primary hover:underline">Connectez-vous</a> pour laisser un avis.
                </div>
            <?php endif; ?>
            
            <!-- Reviews List -->
            <div class="space-y-6">
                <?php if (empty($reviews['items'])): ?>
                    <p class="text-gray-500 text-center py-8">Aucun avis pour le moment. Soyez le premier à en laisser un !</p>
                <?php else: ?>
                    <?php foreach ($reviews['items'] as $review): ?>
                        <div class="border-b border-gray-200 pb-6">
                            <div class="flex items-start justify-between mb-2">
                                <div>
                                    <div class="font-semibold text-gray-900"><?= e($review['userName'] ?? 'Utilisateur anonyme') ?></div>
                                    <div class="text-sm text-gray-500"><?= date('d/m/Y', strtotime($review['createdAt'])) ?></div>
                                </div>
                                <div class="flex items-center gap-1">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <svg class="w-4 h-4 <?= $i <= $review['rating'] ? 'text-yellow-400' : 'text-gray-300' ?>" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                    <?php endfor; ?>
                                </div>
                            </div>
                            <?php if ($review['comment']): ?>
                                <p class="text-gray-700 mt-2"><?= nl2br(e($review['comment'])) ?></p>
                            <?php endif; ?>
                            <?php if (Auth::check() && Auth::id() === $review['userId']): ?>
                                <form action="<?= baseUrl('api/reviews/' . $review['id'] . '/delete') ?>" method="POST" data-action="delete-review" class="mt-2 inline">
                                    <?= CSRF::field() ?>
                                    <button type="submit" class="text-sm text-red-600 hover:text-red-800">Supprimer mon avis</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    <?php if (!empty($relatedProducts)): ?>
        <div class="mt-12">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Produits similaires</h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                <?php foreach ($relatedProducts as $related): ?>
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                        <a href="<?= route('product.show', ['slug' => $related['slug']]) ?>">
                            <img src="<?= baseUrl(ltrim($related['image'], '/')) ?>" alt="<?= e($related['name']) ?>" 
                                 class="w-full h-48 object-cover">
                        </a>
                        <div class="p-4">
                            <h3 class="font-semibold text-gray-900 mb-2">
                                <a href="<?= baseUrl('produit/' . $related['slug']) ?>" class="hover:text-primary">
                                    <?= e($related['name']) ?>
                                </a>
                            </h3>
                            <div class="flex items-center justify-between">
                                <span class="text-lg font-bold text-primary"><?= formatPrice($related['price']) ?></span>
                                <a href="<?= baseUrl('produit/' . $related['slug']) ?>" class="text-sm text-primary hover:text-accent-rose">
                                    Voir →
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
function showTab(tab) {
    document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
    document.querySelectorAll('.tab-button').forEach(el => {
        el.classList.remove('border-primary', 'text-primary', 'font-semibold');
        el.classList.add('border-transparent', 'text-gray-600');
    });
    document.getElementById(tab + '-tab').classList.remove('hidden');
    event.target.classList.add('border-primary', 'text-primary', 'font-semibold');
    event.target.classList.remove('border-transparent', 'text-gray-600');
}

let selectedRating = 0;
function setRating(rating) {
    selectedRating = rating;
    document.getElementById('rating-value').value = rating;
    document.querySelectorAll('.rating-star').forEach((star, index) => {
        if (index < rating) {
            star.classList.remove('text-gray-300');
            star.classList.add('text-yellow-400');
        } else {
            star.classList.remove('text-yellow-400');
            star.classList.add('text-gray-300');
        }
    });
}

// Handle review form submission
document.querySelectorAll('form[data-action="add-review"]').forEach(form => {
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        if (!selectedRating) {
            alert('Veuillez sélectionner une note');
            return;
        }
        
        const formData = new FormData(form);
        const button = form.querySelector('button[type="submit"]');
        const originalText = button.textContent;
        button.disabled = true;
        button.textContent = 'Publication...';
        
        try {
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                showToast('Avis publié avec succès', 'success');
                location.reload();
            } else {
                showToast(data.error || 'Erreur', 'error');
            }
        } catch (error) {
            showToast('Erreur de connexion', 'error');
        } finally {
            button.disabled = false;
            button.textContent = originalText;
        }
    });
});

// Handle delete review
document.querySelectorAll('form[data-action="delete-review"]').forEach(form => {
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        if (!confirm('Êtes-vous sûr de vouloir supprimer votre avis ?')) return;
        
        const formData = new FormData(form);
        
        try {
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                showToast('Avis supprimé', 'success');
                location.reload();
            } else {
                showToast('Erreur', 'error');
            }
        } catch (error) {
            showToast('Erreur de connexion', 'error');
        }
    });
});
</script>


