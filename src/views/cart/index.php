<?php
require_once __DIR__ . '/../../models/Cart.php';
require_once __DIR__ . '/../../lib/CSRF.php';

$subtotal = Cart::subtotal();
$shipping = Cart::shipping();
$total = Cart::total();
?>

<div class="max-w-7xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Panier</h1>
    
    <?php if (empty($items)): ?>
        <div class="text-center py-12">
            <p class="text-gray-600 mb-4">Votre panier est vide</p>
            <a href="<?= route('products.index') ?>" class="inline-block px-6 py-3 bg-primary text-white rounded-lg hover:opacity-90">
                Continuer vos achats
            </a>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Cart Items -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Produit</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Prix</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantité</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase"></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($items as $item): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <img src="<?= assetUrl(ltrim($item['image'], '/')) ?>" alt="<?= e($item['name']) ?>" class="h-16 w-16 object-cover rounded">
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900"><?= e($item['name']) ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?= formatPrice($item['price']) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <form action="<?= route('cart.update') ?>" method="POST" class="inline" id="qty-form-<?= e($item['id']) ?>">
                                            <?= CSRF::field() ?>
                                            <input type="hidden" name="productId" value="<?= e($item['id']) ?>">
                                            <input type="number" name="quantity" value="<?= $item['qty'] ?>" min="1" 
                                                   class="w-20 px-2 py-1 border border-gray-300 rounded text-sm" 
                                                   onchange="document.getElementById('qty-form-<?= e($item['id']) ?>').submit()">
                                        </form>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        <?= formatPrice($item['price'] * $item['qty']) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <form action="<?= route('cart.remove') ?>" method="POST" class="inline">
                                            <?= CSRF::field() ?>
                                            <input type="hidden" name="productId" value="<?= e($item['id']) ?>">
                                            <button type="submit" class="text-red-600 hover:text-red-900">Supprimer</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md p-6 sticky top-20">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Résumé de la commande</h2>
                    <div class="space-y-2 mb-4">
                        <div class="flex justify-between text-sm">
                            <span>Sous-total</span>
                            <span><?= formatPrice($subtotal) ?></span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span>Livraison</span>
                            <span><?= $shipping > 0 ? formatPrice($shipping) : 'Gratuite' ?></span>
                        </div>
                        <?php if ($subtotal < 100): ?>
                            <div class="text-xs text-gray-500">
                                Ajoutez <?= formatPrice(100 - $subtotal) ?> pour la livraison gratuite
                            </div>
                        <?php endif; ?>
                        <div class="border-t pt-2 mt-2">
                            <div class="flex justify-between font-semibold">
                                <span>Total</span>
                                <span><?= formatPrice($total) ?></span>
                            </div>
                        </div>
                    </div>
                    <a href="<?= route('checkout.index') ?>" class="block w-full text-center px-6 py-3 bg-primary text-white rounded-lg hover:opacity-90 transition">
                        Commander
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>


