<?php
require_once __DIR__ . '/../../lib/Auth.php';
require_once __DIR__ . '/../../models/Order.php';

$user = Auth::user();
$recentOrders = array_slice(Order::userOrders(Auth::id()), 0, 5);
?>

<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Mon Compte</h1>
        <form action="<?= route('auth.logout') ?>" method="POST" class="inline">
            <?= CSRF::field() ?>
            <button type="submit" class="px-4 py-2 text-sm text-red-600 border border-red-300 rounded-lg hover:bg-red-50 transition">
                Déconnexion
            </button>
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <a href="<?= route('account.profile') ?>" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition">
            <div class="text-3xl mb-2">👤</div>
            <h3 class="font-semibold text-gray-900">Mon Profil</h3>
            <p class="text-sm text-gray-600 mt-1">Gérer mes informations</p>
        </a>
        <a href="<?= route('account.orders') ?>" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition">
            <div class="text-3xl mb-2">📦</div>
            <h3 class="font-semibold text-gray-900">Mes Commandes</h3>
            <p class="text-sm text-gray-600 mt-1">Voir l'historique</p>
        </a>
        <a href="<?= route('account.addresses') ?>" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition">
            <div class="text-3xl mb-2">📍</div>
            <h3 class="font-semibold text-gray-900">Mes Adresses</h3>
            <p class="text-sm text-gray-600 mt-1">Gérer les adresses</p>
        </a>
        <a href="<?= route('account.wishlist') ?>" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition">
            <div class="text-3xl mb-2">❤️</div>
            <h3 class="font-semibold text-gray-900">Mes Favoris</h3>
            <p class="text-sm text-gray-600 mt-1">Voir mes favoris</p>
        </a>
    </div>

    <?php if (!empty($recentOrders)): ?>
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4">Commandes récentes</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-b">
                            <th class="text-left py-2">N° Commande</th>
                            <th class="text-left py-2">Date</th>
                            <th class="text-left py-2">Statut</th>
                            <th class="text-left py-2">Total</th>
                            <th class="text-left py-2"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentOrders as $order): ?>
                            <tr class="border-b">
                                <td class="py-3"><?= e($order['orderNumber']) ?></td>
                                <td class="py-3"><?= date('d/m/Y', strtotime($order['createdAt'])) ?></td>
                                <td class="py-3">
                                    <span class="px-2 py-1 text-xs rounded <?= 
                                        $order['status'] === 'DELIVERED' ? 'bg-green-100 text-green-800' :
                                        ($order['status'] === 'SHIPPED' ? 'bg-blue-100 text-blue-800' :
                                        ($order['status'] === 'PROCESSING' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800'))
                                    ?>">
                                        <?= e($order['status']) ?>
                                    </span>
                                </td>
                                <td class="py-3 font-semibold"><?= formatPrice($order['total']) ?></td>
                                <td class="py-3">
                                    <a href="<?= route('account.order.detail', ['id' => $order['id']]) ?>" class="text-primary hover:text-accent-rose">
                                        Voir →
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="mt-4 text-center">
                <a href="<?= route('account.orders') ?>" class="text-primary hover:text-accent-rose">
                    Voir toutes les commandes →
                </a>
            </div>
        </div>
    <?php endif; ?>
</div>


