<?php
require_once __DIR__ . '/../../lib/Auth.php';
require_once __DIR__ . '/../../lib/Helpers.php';
$user = Auth::user();
?>

<div class="space-y-6">
    <!-- Welcome Header -->
    <div class="bg-gradient-to-r from-primary to-accent-rose rounded-lg shadow-lg p-6 text-white">
        <h1 class="text-3xl font-bold mb-2">Bienvenue, <?= e($user['name'] ?? 'Administrateur') ?> 👋</h1>
        <p class="text-white/90">Voici un aperçu de votre boutique</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Products Card -->
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500 hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium mb-1">Produits</p>
                    <p class="text-3xl font-bold text-gray-900"><?= number_format($stats['totalProducts'] ?? 0) ?></p>
                    <p class="text-xs text-gray-500 mt-1">Total enregistrés</p>
                </div>
                <div class="bg-blue-100 rounded-full p-3">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Orders Card -->
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500 hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium mb-1">Commandes</p>
                    <p class="text-3xl font-bold text-gray-900"><?= number_format($stats['totalOrders'] ?? 0) ?></p>
                    <p class="text-xs text-gray-500 mt-1">
                        <?= $stats['pendingOrders'] ?? 0 ?> en attente
                    </p>
                </div>
                <div class="bg-green-100 rounded-full p-3">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Users Card -->
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-purple-500 hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium mb-1">Utilisateurs</p>
                    <p class="text-3xl font-bold text-gray-900"><?= number_format($stats['totalUsers'] ?? 0) ?></p>
                    <p class="text-xs text-gray-500 mt-1">Comptes actifs</p>
                </div>
                <div class="bg-purple-100 rounded-full p-3">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Revenue Card -->
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-yellow-500 hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium mb-1">Revenus</p>
                    <p class="text-3xl font-bold text-gray-900"><?= formatPrice($stats['revenue'] ?? 0) ?></p>
                    <p class="text-xs text-gray-500 mt-1">
                        <?= formatPrice($stats['revenueThisMonth'] ?? 0) ?> ce mois
                    </p>
                </div>
                <div class="bg-yellow-100 rounded-full p-3">
                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-xl shadow-md p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Actions rapides</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="<?= route('admin.product.create') ?>" class="flex flex-col items-center justify-center p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-primary hover:bg-primary/5 transition">
                <svg class="w-8 h-8 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span class="text-sm font-medium text-gray-700">Nouveau produit</span>
            </a>
            <a href="<?= route('admin.categories') ?>" class="flex flex-col items-center justify-center p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-primary hover:bg-primary/5 transition">
                <svg class="w-8 h-8 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span class="text-sm font-medium text-gray-700">Nouvelle catégorie</span>
            </a>
            <a href="<?= route('admin.orders') ?>?status=PENDING" class="flex flex-col items-center justify-center p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-primary hover:bg-primary/5 transition">
                <svg class="w-8 h-8 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <span class="text-sm font-medium text-gray-700">Commandes en attente</span>
            </a>
            <a href="<?= route('admin.products') ?>" class="flex flex-col items-center justify-center p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-primary hover:bg-primary/5 transition">
                <svg class="w-8 h-8 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"></path>
                </svg>
                <span class="text-sm font-medium text-gray-700">Gérer produits</span>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Orders -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-gray-900">Commandes récentes</h2>
                <a href="<?= route('admin.orders') ?>" class="text-sm text-primary hover:text-accent-rose">Voir tout →</a>
            </div>
            <?php if (empty($recentOrders)): ?>
                <p class="text-gray-500 text-center py-8">Aucune commande récente</p>
            <?php else: ?>
                <div class="space-y-3">
                    <?php foreach ($recentOrders as $order): ?>
                        <a href="<?= route('admin.order.detail', ['id' => $order['id']]) ?>" class="block p-4 border border-gray-200 rounded-lg hover:border-primary hover:bg-primary/5 transition">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-semibold text-gray-900"><?= e($order['orderNumber']) ?></p>
                                    <p class="text-sm text-gray-600"><?= e($order['userName'] ?? $order['userEmail'] ?? 'Client') ?></p>
                                    <p class="text-xs text-gray-500 mt-1"><?= date('d/m/Y H:i', strtotime($order['createdAt'])) ?></p>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-primary"><?= formatPrice($order['total']) ?></p>
                                    <span class="inline-block px-2 py-1 text-xs rounded mt-1 <?= 
                                        $order['status'] === 'DELIVERED' ? 'bg-green-100 text-green-800' :
                                        ($order['status'] === 'SHIPPED' ? 'bg-blue-100 text-blue-800' :
                                        ($order['status'] === 'PROCESSING' ? 'bg-yellow-100 text-yellow-800' :
                                        ($order['status'] === 'PENDING' ? 'bg-gray-100 text-gray-800' : 'bg-red-100 text-red-800')))
                                    ?>">
                                        <?= e($order['status']) ?>
                                    </span>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Low Stock Alert -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-gray-900">Stock faible</h2>
                <?php if (!empty($lowStockProducts)): ?>
                    <span class="px-3 py-1 bg-red-100 text-red-800 text-sm font-semibold rounded-full">
                        <?= count($lowStockProducts) ?> produit<?= count($lowStockProducts) > 1 ? 's' : '' ?>
                    </span>
                <?php endif; ?>
            </div>
            <?php if (empty($lowStockProducts)): ?>
                <div class="text-center py-8">
                    <svg class="w-16 h-16 text-green-500 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-gray-500">Tous les produits sont bien approvisionnés</p>
                </div>
            <?php else: ?>
                <div class="space-y-3">
                    <?php foreach ($lowStockProducts as $product): ?>
                        <a href="<?= route('admin.product.edit', ['id' => $product['id']]) ?>" class="block p-4 border border-red-200 rounded-lg hover:border-red-400 hover:bg-red-50 transition">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <?php if ($product['image']): ?>
                                        <img src="<?= baseUrl(ltrim($product['image'], '/')) ?>" alt="<?= e($product['name']) ?>" class="w-12 h-12 object-cover rounded">
                                    <?php endif; ?>
                                    <div>
                                        <p class="font-semibold text-gray-900"><?= e($product['name']) ?></p>
                                        <p class="text-sm text-gray-600">Stock: <span class="font-bold text-red-600"><?= $product['stockQuantity'] ?></span></p>
                                    </div>
                                </div>
                                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Order Status Breakdown -->
    <div class="bg-white rounded-xl shadow-md p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Répartition des commandes</h2>
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
            <?php
            $statusLabels = [
                'PENDING' => ['label' => 'En attente', 'color' => 'bg-gray-100 text-gray-800'],
                'CONFIRMED' => ['label' => 'Confirmée', 'color' => 'bg-blue-100 text-blue-800'],
                'PROCESSING' => ['label' => 'En traitement', 'color' => 'bg-yellow-100 text-yellow-800'],
                'SHIPPED' => ['label' => 'Expédiée', 'color' => 'bg-purple-100 text-purple-800'],
                'DELIVERED' => ['label' => 'Livrée', 'color' => 'bg-green-100 text-green-800'],
            ];
            foreach ($statusLabels as $status => $info):
                $count = $statusBreakdown[$status] ?? 0;
            ?>
                <div class="text-center p-4 border border-gray-200 rounded-lg">
                    <p class="text-2xl font-bold text-gray-900"><?= $count ?></p>
                    <p class="text-sm text-gray-600 mt-1"><?= $info['label'] ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Recent Products -->
    <div class="bg-white rounded-xl shadow-md p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold text-gray-900">Produits récents</h2>
            <a href="<?= route('admin.products') ?>" class="text-sm text-primary hover:text-accent-rose">Voir tout →</a>
        </div>
        <?php if (empty($recentProducts)): ?>
            <p class="text-gray-500 text-center py-8">Aucun produit récent</p>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <?php foreach ($recentProducts as $product): ?>
                    <a href="<?= route('admin.product.edit', ['id' => $product['id']]) ?>" class="group">
                        <div class="border border-gray-200 rounded-lg overflow-hidden hover:border-primary hover:shadow-md transition">
                            <?php if ($product['image']): ?>
                                <img src="<?= baseUrl(ltrim($product['image'], '/')) ?>" alt="<?= e($product['name']) ?>" class="w-full h-32 object-cover group-hover:scale-105 transition">
                            <?php else: ?>
                                <div class="w-full h-32 bg-gray-200 flex items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            <?php endif; ?>
                            <div class="p-3">
                                <p class="font-semibold text-gray-900 text-sm line-clamp-2 mb-1 group-hover:text-primary"><?= e($product['name']) ?></p>
                                <p class="text-sm font-bold text-primary"><?= formatPrice($product['price']) ?></p>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
