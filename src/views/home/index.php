<?php
require_once __DIR__ . '/../../models/Product.php';
require_once __DIR__ . '/../../models/Category.php';
require_once __DIR__ . '/../../lib/Auth.php';
?>

<!-- Hero Section -->
<section class="bg-gradient-to-br from-accent-beige/40 via-accent-rose/30 to-accent-beige/20 relative border-b border-primary/10 overflow-hidden">
    <!-- Decorative Background Elements -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-20 left-10 w-72 h-72 bg-primary/5 rounded-full blur-3xl"></div>
        <div class="absolute bottom-20 right-20 w-96 h-96 bg-primary/5 rounded-full blur-3xl"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-primary/3 rounded-full blur-3xl"></div>
    </div>
    
    <div class="max-w-7xl mx-auto px-4 py-16 md:py-20 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 md:gap-12 items-center">
            <!-- Left Side - Image Carousel -->
            <div class="relative order-2 lg:order-1">
                <div id="heroCarousel" class="relative overflow-hidden group">
                    <!-- Carousel Container with Responsive Height -->
                    <div class="carousel-container relative rounded-2xl overflow-hidden shadow-2xl border-4 border-primary/20" style="height: 60vh; min-height: 400px; max-height: 600px;">
                         <div class="carousel-track flex transition-transform duration-500 ease-in-out h-full" style="transform: translateX(0%);">
                            <?php
                            $hasDynamic = !empty($heroProducts);
                            if ($hasDynamic) {
                                foreach ($heroProducts as $hp):
                            ?>
                                <div class="carousel-slide min-w-full flex-shrink-0 h-full flex items-center justify-center" data-title="<?= e($hp['name']) ?>" data-desc="<?= e($hp['shortDescription'] ?? '') ?>" data-url="<?= route('product.show', ['slug' => $hp['slug']]) ?>">
                                    <div class="w-full h-full relative">
                                        <img src="<?= baseUrl(ltrim($hp['image'], '/')) ?>" 
                                             alt="<?= e($hp['name']) ?>" 
                                             class="w-full h-full object-cover">
                                        <!-- Gradient Overlay -->
                                        <div class="absolute inset-0 bg-gradient-to-t from-primary/20 via-transparent to-transparent"></div>
                                    </div>
                                </div>
                            <?php 
                                endforeach; 
                            } else {
                            $heroItems = [
                                ['image' => 'images/img_0126-scaled.jpeg', 'alt' => 'Collection Sultan Library 2024'],
                                ['image' => 'images/img_8104-scaled.jpeg', 'alt' => 'Livres Rares'],
                                ['image' => 'images/img_8236-scaled.jpeg', 'alt' => 'Collections Premium'],
                                ['image' => 'images/img_8309-scaled.jpeg', 'alt' => 'Objets Culturels'],
                            ];
                            foreach ($heroItems as $item):
                            ?>
                                <div class="carousel-slide min-w-full flex-shrink-0 h-full flex items-center justify-center" data-title="<?= e($item['alt']) ?>" data-desc="" data-url="<?= route('products.index') ?>">
                                    <div class="w-full h-full relative">
                                    <img src="<?= baseUrl($item['image']) ?>" 
                                         alt="<?= e($item['alt']) ?>" 
                                         class="w-full h-full object-cover">
                                    <!-- Gradient Overlay -->
                                    <div class="absolute inset-0 bg-gradient-to-t from-primary/20 via-transparent to-transparent"></div>
                                    </div>
                                </div>
                            <?php endforeach; } ?>
                        </div>

                        <!-- Navigation Arrows -->
                        <button id="carouselPrev" 
                                    class="absolute left-4 top-1/2 -translate-y-1/2 bg-white/95 hover:bg-white border-2 border-primary/30 hover:border-primary rounded-full p-3 shadow-xl transition z-20 group">
                            <svg class="w-6 h-6 text-primary group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </button>
                        <button id="carouselNext" 
                                    class="absolute right-4 top-1/2 -translate-y-1/2 bg-white/95 hover:bg-white border-2 border-primary/30 hover:border-primary rounded-full p-3 shadow-xl transition z-20 group">
                            <svg class="w-6 h-6 text-primary group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>

                        <!-- Dot Indicators -->
                        <div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex gap-2 z-20">
                            <?php if (!empty($heroProducts)): $total = count($heroProducts); for ($index=0; $index<$total; $index++): ?>
                                <button class="carousel-dot w-3 h-3 rounded-full transition-all border-2 border-white <?= $index === 0 ? 'bg-primary opacity-100 scale-125' : 'bg-white/50 opacity-70' ?>" 
                                        data-slide="<?= $index ?>"></button>
                            <?php endfor; elseif (!empty($heroItems)): foreach ($heroItems as $index => $item): ?>
                                <button class="carousel-dot w-3 h-3 rounded-full transition-all border-2 border-white <?= $index === 0 ? 'bg-primary opacity-100 scale-125' : 'bg-white/50 opacity-70' ?>" 
                                    data-slide="<?= $index ?>"></button>
                            <?php endforeach; endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side - Content -->
            <div class="order-1 lg:order-2 space-y-6">
                <!-- Badge -->
                <div class="inline-flex items-center gap-2 bg-primary/10 border-2 border-primary/30 text-primary px-4 py-2 rounded-full text-sm font-semibold">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                    </svg>
                    <span>NOUVEAUTÉ 2024</span>
                </div>
                
                <?php $firstHero = $hasDynamic ? ($heroProducts[0] ?? null) : null; ?>
                <h1 id="heroTitle" class="text-4xl md:text-5xl lg:text-6xl font-bold text-primary leading-tight">
                    <?= $firstHero ? e($firstHero['name']) : 'Collection Sultan Library 2024' ?>
                </h1>
                
                <p id="heroDesc" class="text-lg md:text-xl text-neutral-dark leading-relaxed max-w-xl">
                    <?= $firstHero ? e($firstHero['shortDescription'] ?? '') : 'Découvrez notre sélection exclusive de livres, objets culturels et collections spéciales. Chaque produit est soigneusement sélectionné pour vous offrir une expérience unique.' ?>
                </p>

                <!-- Features List -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 py-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <span class="text-gray-700 font-medium">Livraison gratuite dès 100€</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <span class="text-gray-700 font-medium">Qualité garantie</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <span class="text-gray-700 font-medium">12 500+ avis clients</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <span class="text-gray-700 font-medium">Retours gratuits</span>
                    </div>
                </div>

                <!-- CTA Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 pt-4">
                    <a id="heroBtn" href="<?= $firstHero ? route('product.show', ['slug' => $firstHero['slug']]) : route('products.index') ?>" class="inline-flex items-center justify-center gap-2 bg-primary text-white px-8 py-4 rounded-xl font-semibold hover:bg-primary/90 transition-all duration-300 hover:shadow-xl hover:-translate-y-1 text-base">
                        Découvrir la collection
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                        </svg>
                    </a>
                    <a href="<?= route('products.index') ?>" class="inline-flex items-center justify-center gap-2 border-2 border-primary text-primary px-8 py-4 rounded-xl font-semibold hover:bg-primary/10 transition-all duration-300 text-base">
                        Voir tous les produits
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>


<?php if (!empty($topCategories)): ?>
    <?php
    // Define desired order for categories section (same as header menu)
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
    $categoriesCopy = $topCategories;
    
    // First, add categories in the specified order
    foreach ($menuOrder as $orderName) {
        foreach ($categoriesCopy as $key => $category) {
            if (stripos($category['name'], $orderName) !== false || stripos($orderName, $category['name']) !== false) {
                $orderedCategories[] = $category;
                unset($categoriesCopy[$key]);
                break;
            }
        }
    }
    
    // Add remaining categories that weren't in the order list
    $unorderedCategories = array_values($categoriesCopy);
    $displayCategories = array_merge($orderedCategories, $unorderedCategories);
    $displayCategories = array_slice($displayCategories, 0, 8);
    ?>
    <section class="bg-gradient-to-b from-white via-accent-rose/5 to-white py-16 border-b border-primary/10 relative">
        <!-- Decorative accent line -->
        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-transparent via-primary/20 to-transparent"></div>
        <!-- Left side accent strip - Categories identity -->
        <div class="absolute left-0 top-0 bottom-0 w-1 bg-gradient-to-b from-primary via-primary/60 to-transparent"></div>
        
        <div class="max-w-7xl mx-auto px-4 pl-6">
            <div class="flex items-center justify-between mb-10">
                <div>
                    <h2 class="text-3xl font-bold text-primary mb-2">Nos catégories</h2>
                    <div class="h-1 w-20 bg-primary rounded-full"></div>
                </div>
                <a href="<?= route('products.index') ?>" class="flex items-center gap-2 text-primary hover:text-primary/80 transition font-medium text-sm border-2 border-primary/30 hover:border-primary px-4 py-2 rounded-lg">
                    Voir toutes les catégories <span>→</span>
                </a>
            </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php foreach ($displayCategories as $category): ?>
                <a href="<?= route('category.show', ['slug' => $category['slug']]) ?>" 
                   class="relative bg-white rounded-2xl overflow-hidden group hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 border-2 border-primary/10 hover:border-primary/40 shadow-sm hover:shadow-primary/10">
                    <!-- Image Container -->
                    <div class="relative h-56 overflow-hidden">
                        <img src="<?= $category['image'] ? assetUrl(ltrim($category['image'], '/')) : baseUrl('images/logo.png') ?>" 
                             alt="<?= e($category['name']) ?>" 
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        <!-- Primary Color Overlay on Hover -->
                        <div class="absolute inset-0 bg-primary/0 group-hover:bg-primary/10 transition-all duration-500"></div>
                        <!-- Product Count Badge -->
                        <?php if (isset($category['productCount']) && $category['productCount'] > 0): ?>
                            <div class="absolute top-4 right-4 bg-primary text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-lg group-hover:scale-110 group-hover:shadow-xl transition-all duration-300">
                                <?= $category['productCount'] ?> <?= $category['productCount'] === 1 ? 'produit' : 'produits' ?>
                            </div>
                        <?php endif; ?>
                        <!-- Subtle border accent -->
                        <div class="absolute inset-0 border-2 border-transparent group-hover:border-primary/20 transition-all duration-500 rounded-2xl"></div>
                    </div>
                    
                    <!-- Content -->
                    <div class="p-6 bg-white group-hover:bg-gray-50/50 transition-all duration-300 relative">
                        <!-- Decorative accent bar -->
                        <div class="absolute top-0 left-6 right-6 h-0.5 bg-primary/0 group-hover:bg-primary/30 transition-all duration-300"></div>
                        
                        <h3 class="text-xl font-bold text-gray-900 mb-2 leading-tight group-hover:text-primary transition-colors duration-300">
                            <?= e($category['name']) ?>
                        </h3>
                        <?php if (!empty($category['description'])): ?>
                            <p class="text-sm text-gray-600 mb-4 line-clamp-2">
                                <?= e($category['description']) ?>
                            </p>
                        <?php else: ?>
                            <p class="text-sm text-gray-500 mb-4">
                                Découvrez notre sélection
                            </p>
                        <?php endif; ?>
                        <span class="inline-flex items-center gap-2 text-primary font-semibold group-hover:gap-3 transition-all duration-300">
                            Découvrir
                            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </span>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </section>
<?php endif; ?>

<!-- Featured Products -->
<section class="bg-gradient-to-br from-accent-beige/35 via-accent-rose/8 to-accent-beige/25 py-20 border-b border-primary/10 relative overflow-hidden">
    <!-- Subtle paper texture / page lines pattern -->
    <div class="absolute inset-0 opacity-[0.03]" style="background-image: repeating-linear-gradient(0deg, transparent, transparent 24px, rgb(86, 71, 74) 24px, rgb(86, 71, 74) 25px);"></div>
    
    <!-- Additional subtle texture overlay -->
    <div class="absolute inset-0 bg-gradient-to-b from-primary/5 via-transparent to-primary/5"></div>
    
    <!-- Elegant top border - like a book cover edge -->
    <div class="absolute top-0 left-0 right-0 h-0.5 bg-primary/15"></div>
    
    <!-- Left margin accent - like book spine -->
    <div class="absolute left-0 top-0 bottom-0 w-0.5 bg-gradient-to-b from-primary/20 via-primary/10 to-primary/20"></div>
    
    <!-- Right margin accent - balanced elegance -->
    <div class="absolute right-0 top-0 bottom-0 w-0.5 bg-gradient-to-b from-primary/20 via-primary/10 to-primary/20"></div>
    
    <!-- Bottom border - refined finish -->
    <div class="absolute bottom-0 left-0 right-0 h-0.5 bg-primary/15"></div>
    
    <!-- Subtle corner elements - like book corners -->
    <div class="absolute top-0 left-0 w-16 h-16 border-t-2 border-l-2 border-primary/10"></div>
    <div class="absolute top-0 right-0 w-16 h-16 border-t-2 border-r-2 border-primary/10"></div>
    <div class="absolute bottom-0 left-0 w-16 h-16 border-b-2 border-l-2 border-primary/10"></div>
    <div class="absolute bottom-0 right-0 w-16 h-16 border-b-2 border-r-2 border-primary/10"></div>
    
    <div class="max-w-7xl mx-auto px-4 relative z-10">
        <div class="flex items-center justify-between mb-12">
            <div class="relative">
                <!-- Decorative primary accent behind title -->
                <div class="absolute -left-6 top-0 bottom-0 w-1.5 bg-gradient-to-b from-primary via-primary/80 to-primary/40 rounded-full"></div>
                <h2 class="text-4xl font-bold text-primary mb-3 pl-6">Notre sélection Sultan Library</h2>
                <div class="h-2 w-32 bg-gradient-to-r from-primary to-primary/60 rounded-full ml-6"></div>
                <p class="text-gray-600 mt-3 ml-6 text-lg">Une collection soigneusement sélectionnée pour vous</p>
                            </div>
            <a href="<?= route('products.index') ?>" class="flex items-center gap-2 bg-primary text-white hover:bg-primary/90 transition-all font-semibold text-sm px-6 py-3 rounded-lg shadow-lg hover:shadow-xl hover:scale-105 transform duration-300">
                Toute notre collection <span class="text-lg">→</span>
            </a>
        </div>

        <?php if (!empty($featuredProducts)): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <?php foreach ($featuredProducts as $product): ?>
                    <div class="bg-white rounded-2xl overflow-hidden group hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 border-2 border-primary/30 hover:border-primary shadow-lg hover:shadow-primary/20 relative">
                        <!-- Heart Icon (Favorites) -->
                            <?php if (Auth::check()): ?>
                                <form action="<?= route('account.wishlist.add') ?>" method="POST" class="absolute top-3 right-3 z-10" data-action="wishlist">
                                    <?= CSRF::field() ?>
                                    <input type="hidden" name="productId" value="<?= $product['id'] ?>">
                                <button type="submit" class="bg-white/95 backdrop-blur-sm rounded-full p-2.5 hover:bg-primary hover:text-white transition-all shadow-md hover:shadow-lg">
                                    <svg class="w-5 h-5 text-primary group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                        </svg>
                                    </button>
                                </form>
                            <?php endif; ?>

                        <!-- Product Image -->
                        <div class="relative h-56 overflow-hidden bg-gradient-to-br from-gray-50 to-white">
                            <a href="<?= route('product.show', ['slug' => $product['slug']]) ?>">
                                <img src="<?= baseUrl(ltrim($product['image'], '/')) ?>" 
                                     alt="<?= e($product['name']) ?>" 
                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                            </a>
                            <!-- Primary Color Overlay on Hover -->
                            <div class="absolute inset-0 bg-primary/0 group-hover:bg-primary/10 transition-all duration-500"></div>
                            <!-- Primary color border accent -->
                            <div class="absolute inset-0 border-2 border-primary/20 group-hover:border-primary/50 transition-all duration-500 rounded-2xl"></div>
                            <!-- Primary color corner accent -->
                            <div class="absolute top-0 left-0 w-20 h-20 bg-gradient-to-br from-primary/30 via-primary/10 to-transparent rounded-tl-2xl"></div>
                            <!-- Bottom accent bar -->
                            <div class="absolute bottom-0 left-0 right-0 h-1 bg-primary/0 group-hover:bg-primary/40 transition-all duration-500"></div>
                        </div>

                        <!-- Product Info -->
                        <div class="p-6 bg-white group-hover:bg-gradient-to-br group-hover:from-gray-50/80 group-hover:to-white transition-all duration-300 relative">
                            <!-- Decorative accent bar with primary color -->
                            <div class="absolute top-0 left-6 right-6 h-1.5 bg-gradient-to-r from-primary/20 via-primary/40 to-primary/20 group-hover:from-primary/40 group-hover:via-primary/60 group-hover:to-primary/40 transition-all duration-300 rounded-full"></div>
                            
                            <!-- Product Name -->
                            <h3 class="text-xl font-bold text-gray-900 mb-3 leading-tight group-hover:text-primary transition-colors duration-300">
                                <a href="<?= route('product.show', ['slug' => $product['slug']]) ?>" class="hover:underline">
                                    <?= e($product['name']) ?>
                                </a>
                            </h3>

                            <!-- Star Rating -->
                            <div class="flex items-center gap-1 mb-2">
                                <?php 
                                $rating = round($product['rating'] ?? 0);
                                for ($i = 1; $i <= 5; $i++): 
                                ?>
                                    <svg class="w-4 h-4 <?= $i <= $rating ? 'text-yellow-400 fill-yellow-400' : 'text-gray-300' ?>" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                <?php endfor; ?>
                                <?php if ($product['reviewCount'] > 0): ?>
                                    <span class="text-xs text-gray-500 ml-1">(<?= $product['reviewCount'] ?>)</span>
                                <?php endif; ?>
                            </div>

                            <!-- Benefits/Features -->
                            <?php if ($product['shortDescription']): ?>
                                <p class="text-sm text-gray-600 mb-4 line-clamp-2">
                                    <?= e($product['shortDescription']) ?>
                                </p>
                            <?php else: ?>
                                <p class="text-sm text-gray-500 mb-4">
                                    Découvrez ce produit
                                </p>
                            <?php endif; ?>

                            <!-- Badge -->
                            <?php if ($product['badge']): ?>
                                <div class="inline-block bg-primary text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-lg mb-3">
                                    <?= strtoupper($product['badge']) === 'BESTSELLER' ? 'MEILLEURES VENTES' : strtoupper($product['badge']) ?>
                                </div>
                            <?php endif; ?>

                            <!-- Price and Add to Cart -->
                            <div class="flex items-end justify-between mt-4 pt-4 border-t border-gray-100">
                                <div>
                                    <div class="text-2xl font-bold text-primary"><?= formatPrice($product['price']) ?></div>
                                    <div class="text-xs text-gray-500 mt-1">Produit</div>
                                </div>
                                <form action="<?= route('cart.add') ?>" method="POST" data-action="add-to-cart">
                                    <?= CSRF::field() ?>
                                    <input type="hidden" name="productId" value="<?= $product['id'] ?>">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="bg-primary text-white rounded-lg px-4 py-2.5 hover:bg-primary/90 transition-all shadow-md hover:shadow-lg hover:scale-105 transform duration-300 flex items-center gap-2 group">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                        <span class="text-sm font-medium">Ajouter</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="text-center text-gray-500 py-12">Aucun produit en vedette pour le moment</p>
        <?php endif; ?>
    </div>
</section>

<!-- Guides & Ressources -->
<section class="bg-white py-16 border-b border-primary/10 relative">
    <!-- Decorative accent line -->
    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-transparent via-primary/20 to-transparent"></div>
    
    <div class="max-w-7xl mx-auto px-4 relative z-10">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-primary mb-3">Guides & Ressources</h2>
            <p class="text-gray-600 text-lg max-w-2xl mx-auto">
                Découvrez nos outils pour vous accompagner dans votre parcours de lecture et d'apprentissage
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <a href="#" class="bg-white rounded-xl p-6 shadow-md hover:shadow-xl transition-all duration-300 border-2 border-transparent hover:border-primary/30 group">
                <div class="w-12 h-12 bg-gradient-to-br from-primary/10 to-primary/5 rounded-lg flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2 group-hover:text-primary transition-colors">Guide par thème</h3>
                <p class="text-sm text-gray-600">Trouvez des livres adaptés à vos centres d'intérêt</p>
            </a>

            <a href="#" class="bg-white rounded-xl p-6 shadow-md hover:shadow-xl transition-all duration-300 border-2 border-transparent hover:border-primary/30 group">
                <div class="w-12 h-12 bg-gradient-to-br from-primary/10 to-primary/5 rounded-lg flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2 group-hover:text-primary transition-colors">Guide par niveau</h3>
                <p class="text-sm text-gray-600">Collections adaptées à votre niveau de lecture</p>
            </a>

            <a href="#" class="bg-white rounded-xl p-6 shadow-md hover:shadow-xl transition-all duration-300 border-2 border-transparent hover:border-primary/30 group">
                <div class="w-12 h-12 bg-gradient-to-br from-primary/10 to-primary/5 rounded-lg flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2 group-hover:text-primary transition-colors">Recommandations</h3>
                <p class="text-sm text-gray-600">Sélections personnalisées pour vous</p>
            </a>

            <a href="#" class="bg-white rounded-xl p-6 shadow-md hover:shadow-xl transition-all duration-300 border-2 border-transparent hover:border-primary/30 group">
                <div class="w-12 h-12 bg-gradient-to-br from-primary/10 to-primary/5 rounded-lg flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
            </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2 group-hover:text-primary transition-colors">Collections</h3>
                <p class="text-sm text-gray-600">Découvrez nos sélections thématiques</p>
            </a>
        </div>
    </div>
</section>

<!-- Trust Section (Reviews) -->
<section class="bg-gradient-to-br from-accent-beige/30 via-accent-rose/15 to-accent-beige/25 py-16 border-b border-primary/10 relative">
    <!-- Decorative accent line -->
    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-transparent via-primary/20 to-transparent"></div>
    
    <div class="max-w-4xl mx-auto px-4 relative z-10">
        <div class="text-center mb-12">
            <div class="inline-block bg-primary text-white px-4 py-2 rounded-lg text-sm font-semibold mb-4 uppercase tracking-wide">
                Témoignages
            </div>
            <h2 class="text-3xl font-bold text-primary mb-3">
                Ce que disent nos clients
            </h2>
            <div class="flex items-center justify-center gap-2 mb-2">
                <?php for ($i = 0; $i < 5; $i++): ?>
                    <svg class="w-6 h-6 fill-primary text-primary" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path>
                    </svg>
                <?php endfor; ?>
            </div>
            <p class="text-gray-700">12 500+ avis clients · 4.9/5 étoiles</p>
        </div>

        <!-- Featured Review -->
        <div class="bg-white rounded-2xl shadow-xl p-8 md:p-12 border-2 border-primary/15">
            <div class="text-center mb-6">
                <svg class="w-12 h-12 text-primary/30 mx-auto mb-4" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.996 2.151c-2.433.917-3.995 3.638-3.995 5.849h4v10h-9.996zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.995 3.638-3.995 5.849h3.983v10h-9.983z"/>
                </svg>
            </div>
            <p class="text-xl text-gray-800 mb-6 text-center leading-relaxed italic font-medium">
                "Cette collection de livres est absolument magnifique. Je recommande vivement Sultan Library pour la qualité et la diversité des ouvrages. Un service client exceptionnel !"
            </p>

            <div class="text-center">
                <div class="flex items-center justify-center gap-1 mb-3">
                    <?php for ($i = 0; $i < 5; $i++): ?>
                        <svg class="w-5 h-5 fill-primary text-primary" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path>
                        </svg>
                    <?php endfor; ?>
                </div>
                <p class="text-gray-700 font-semibold mb-1">Amina K.</p>
                <p class="text-sm text-gray-500">Client vérifié · 27 août 2024</p>
            </div>
        </div>
    </div>
</section>

<!-- Qui sommes-nous ? Section -->
<section class="bg-white py-16 border-b border-primary/10 relative">
    <!-- Decorative accent line -->
    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-transparent via-primary/20 to-transparent"></div>
    <!-- Side accent bars - Qui sommes-nous identity -->
    <div class="absolute left-0 top-1/4 bottom-1/4 w-2 bg-primary/30 rounded-r-full"></div>
    <div class="absolute right-0 top-1/4 bottom-1/4 w-2 bg-primary/30 rounded-l-full"></div>
    <!-- Subtle dots pattern - Qui sommes-nous identity -->
    <div class="absolute inset-0 opacity-[0.03]" style="background-image: radial-gradient(circle, rgb(86, 71, 74) 1px, transparent 1px); background-size: 30px 30px;"></div>
    
    <div class="max-w-6xl mx-auto px-4 relative z-10">
        <div class="grid md:grid-cols-2 gap-12 items-center">
            <div class="space-y-6">
                <div class="inline-block bg-primary text-white px-3 py-1 rounded text-xs font-semibold mb-4 uppercase tracking-wide">
                    Notre histoire
                </div>
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Qui sommes-nous ?</h2>
                <p class="text-lg md:text-xl text-gray-800 font-medium leading-relaxed">
                    Bienvenue chez SultanLibrary, une entreprise née de la passion commune de deux jeunes entrepreneurs 
                    pour les livres et la religion. Notre aventure a commencé modestement, avec un simple stand sur les 
                    marchés, et grâce à notre travail acharné, nous avons réussi à faire grandir notre petite entreprise 
                    jour après jour.
                </p>
                <p class="text-lg md:text-xl text-gray-800 font-medium leading-relaxed">
                    Aujourd'hui, nous sommes heureux de vous retrouver sur notre site internet, une étape clé dans 
                    l'évolution de notre projet. Notre mission est simple : offrir à chacun la possibilité d'accéder à 
                    des livres et des produits religieux de qualité, tout en créant un espace où chaque client se sent 
                    le bienvenu.
                </p>
                <div class="flex flex-wrap gap-4 mt-6">
                    <div class="flex items-center gap-2 text-gray-700">
                        <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        <span class="text-sm font-medium">Livres religieux</span>
                    </div>
                    <div class="flex items-center gap-2 text-gray-700">
                        <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <span class="text-sm font-medium">Communauté fidèle</span>
                    </div>
                    <div class="flex items-center gap-2 text-gray-700">
                        <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-sm font-medium">Qualité garantie</span>
                    </div>
                </div>
                <div class="pt-4">
                    <a href="<?= route('page.about') ?>" 
                       class="inline-flex items-center gap-2 bg-primary text-white px-6 py-3 rounded-xl font-semibold hover:bg-primary/90 transition-all duration-300 hover:shadow-lg hover:-translate-y-1">
                        En savoir plus
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                        </svg>
                    </a>
                </div>
            </div>
            
            <div class="relative">
                <div class="bg-white rounded-2xl p-8 shadow-xl border border-accent-beige/30">
                    <div class="space-y-6">
                        <div class="flex items-start gap-4">
                            <div class="bg-gradient-to-br from-accent-beige to-accent-rose rounded-full p-3 flex-shrink-0">
                                <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900 mb-2">Des marchés locaux à un site web</h3>
                                <p class="text-gray-600 text-sm">Une croissance progressive grâce au travail acharné et à la persévérance.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="bg-gradient-to-br from-accent-rose to-accent-beige rounded-full p-3 flex-shrink-0">
                                <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900 mb-2">Inclusion et respect</h3>
                                <p class="text-gray-600 text-sm">Un espace chaleureux où tout le monde se sent bien, peu importe ses croyances.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="bg-gradient-to-br from-accent-beige via-accent-rose to-white rounded-full p-3 flex-shrink-0">
                                <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900 mb-2">Sélection rigoureuse</h3>
                                <p class="text-gray-600 text-sm">Chaque produit est sélectionné avec soin pour partager notre passion.</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Social Media Links -->
                    <div class="mt-8 pt-6 border-t border-gray-200 flex items-center gap-4">
                        <span class="text-sm font-medium text-gray-700">Suivez-nous :</span>
                        <a href="https://snapchat.com/add/sultan_library" target="_blank" rel="noopener noreferrer" 
                           class="bg-yellow-400 text-gray-900 rounded-full p-2 hover:bg-yellow-500 transition">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12.206.793c.99 0 4.347.276 5.93 3.821.529 1.193.403 3.219.299 4.847l-.003.15c-.012.85-.024 1.693-.016 2.79l.009.71c.004.4.004.8.004 1.24 0 .19-.001.38-.003.56-.004.31-.007.61-.005.87.01 1.17.006 1.884-.015 2.64-.042 1.46-.15 2.65-.663 3.95-.574 1.447-1.528 2.91-3.09 3.64-1.494.695-3.04.888-4.52.888-.99 0-1.925-.103-2.782-.28-.853-.176-1.617-.43-2.303-.693a11.12 11.12 0 01-.13-.055c-.82-.33-1.56-.668-2.213-1.044a9.9 9.9 0 01-1.1-.794l-.017-.014c-.3-.246-.57-.498-.83-.767a8.88 8.88 0 01-.9-1.06c-.54-.71-.938-1.49-1.181-2.298-.243-.81-.336-1.644-.39-2.46a18.31 18.31 0 01-.017-1.48c.002-.18.01-.37.017-.57.014-.37.027-.75.05-1.14.046-.78.128-1.55.32-2.29.386-1.48 1.238-2.89 2.333-3.88C4.695 1.84 6.13 1.28 7.66.945c.77-.17 1.57-.266 2.37-.307.8-.04 1.6-.014 2.38.014.78.028 1.53.088 2.23.18.7.092 1.36.204 1.97.336.61.132 1.17.28 1.69.425zm-.008 2.19c-.68-.09-1.37-.18-2.05-.25-.68-.07-1.37-.11-2.04-.1-.67.01-1.34.05-1.99.14-.65.09-1.28.22-1.88.4-1.2.36-2.18 1.06-2.85 2.02-.67.96-1.02 2.11-1.19 3.33-.08.61-.12 1.23-.14 1.85-.02.62-.01 1.24.01 1.85.02.61.06 1.21.13 1.79.14 1.16.43 2.23.98 3.12.55.89 1.34 1.59 2.35 2.02 1.01.43 2.17.6 3.34.6.68 0 1.36-.07 2.01-.2.65-.13 1.27-.31 1.85-.54.58-.23 1.12-.5 1.61-.81.49-.31.93-.66 1.33-1.04.4-.38.75-.79 1.05-1.22.6-.86.98-1.83 1.12-2.83.07-.5.1-1.01.11-1.52.01-.51 0-1.02-.02-1.52-.04-1-.15-1.99-.42-2.93-.54-1.88-1.57-3.49-3.13-4.33-.78-.42-1.65-.7-2.55-.84z"/>
                            </svg>
                        </a>
                        <a href="https://instagram.com/sultanlibrary" target="_blank" rel="noopener noreferrer" 
                           class="bg-gradient-to-br from-pink-500 to-purple-600 text-white rounded-full p-2 hover:from-pink-600 hover:to-purple-700 transition">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2.163c3.36 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Resource -->
<section class="bg-gradient-to-br from-accent-beige/25 via-accent-rose/10 to-accent-beige/20 py-20 border-b border-primary/10 relative">
    <!-- Decorative accent line -->
    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-transparent via-primary/30 to-transparent"></div>
    
    <div class="max-w-7xl mx-auto px-4 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div class="relative">
                <div class="absolute -inset-4 bg-primary/5 rounded-3xl blur-2xl"></div>
                <img src="https://images.pexels.com/photos/159711/books-bookstore-book-reading-159711.jpeg?auto=compress&cs=tinysrgb&w=800" 
                     alt="Sultan Library Resources" 
                     class="relative w-full h-auto rounded-2xl shadow-2xl border-2 border-primary/20">
            </div>

            <div class="space-y-6">
            <div>
                    <div class="inline-block bg-primary text-white px-4 py-2 rounded-lg text-xs font-semibold mb-4 uppercase tracking-wide">
                        RESSOURCE DU MOIS
                    </div>
                    <h2 class="text-3xl md:text-4xl font-bold text-primary mb-4">
                        Guide d'apprentissage de l'arabe classique
                    </h2>
                    <p class="text-lg text-gray-700 leading-relaxed mb-6">
                        Découvrez notre guide complet pour apprendre l'arabe classique. 
                        Une méthode progressive et interactive avec exercices pratiques et ressources audio pour vous accompagner dans votre apprentissage.
                    </p>
                </div>
                
                <div class="space-y-3 mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-primary/10 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <span class="text-gray-700">Méthode progressive adaptée à tous les niveaux</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-primary/10 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <span class="text-gray-700">Exercices pratiques et ressources audio inclus</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-primary/10 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <span class="text-gray-700">Accès à une communauté d'apprenants</span>
                    </div>
                </div>
                
                <a href="#" class="inline-flex items-center gap-2 bg-primary text-white px-8 py-4 rounded-xl font-semibold hover:bg-primary/90 transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                    Découvrir le guide
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</section>

<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        line-clamp: 2;
    }
</style>
