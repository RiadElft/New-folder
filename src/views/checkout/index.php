<?php
require_once __DIR__ . '/../../lib/Auth.php';
require_once __DIR__ . '/../../models/Address.php';

$user = Auth::user();
$addresses = Address::userAddresses(Auth::id());
$step = $_GET['step'] ?? 'address';
?>

<div class="max-w-4xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Finaliser la commande</h1>

    <!-- Progress Steps -->
    <div class="mb-8 flex justify-between">
        <?php
        $steps = [
            ['id' => 'address', 'label' => 'Adresse', 'number' => 1],
            ['id' => 'shipping', 'label' => 'Livraison', 'number' => 2],
            ['id' => 'payment', 'label' => 'Paiement', 'number' => 3],
            ['id' => 'review', 'label' => 'Révision', 'number' => 4],
        ];
        $currentStepIndex = array_search($step ?? 'address', array_column($steps, 'id'));
        if ($currentStepIndex === false) $currentStepIndex = 0;
        ?>
        <?php foreach ($steps as $index => $stepItem): ?>
            <div class="flex items-center">
                <div class="flex items-center justify-center w-10 h-10 rounded-full font-bold <?= 
                    ($step ?? 'address') === $stepItem['id'] ? 'bg-primary text-white' : 
                    ($index < $currentStepIndex ? 'bg-primary text-white' : 'bg-gray-300 text-gray-600')
                ?>">
                    <?= $index < $currentStepIndex ? '✓' : $stepItem['number'] ?>
                </div>
                <span class="ml-2 text-sm font-medium <?= ($step ?? 'address') === $stepItem['id'] ? 'text-primary' : 'text-gray-600' ?>">
                    <?= $stepItem['label'] ?>
                </span>
                <?php if ($index < count($steps) - 1): ?>
                    <div class="w-16 h-0.5 mx-4 <?= $index < $currentStepIndex ? 'bg-primary' : 'bg-gray-300' ?>"></div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Form -->
        <div class="lg:col-span-2">
            <?php if (($step ?? 'address') === 'address'): ?>
                <form action="<?= route('checkout.index') ?>?step=shipping" method="POST" class="bg-white rounded-lg shadow-md p-6">
                    <?= CSRF::field() ?>
                    <h2 class="text-xl font-semibold mb-4">Adresse de livraison</h2>
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Prénom *</label>
                                <input type="text" name="firstName" required 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nom *</label>
                                <input type="text" name="lastName" required 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Adresse *</label>
                            <input type="text" name="street" required 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Ville *</label>
                                <input type="text" name="city" required 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Code postal *</label>
                                <input type="text" name="postalCode" required 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Téléphone</label>
                            <input type="tel" name="phone" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                        </div>
                    </div>
                    <button type="submit" class="mt-6 w-full px-6 py-3 bg-primary text-white rounded-lg hover:opacity-90 transition">
                        Continuer
                    </button>
                </form>
            <?php elseif (($step ?? 'address') === 'shipping'): ?>
                <form action="<?= route('checkout.index') ?>?step=payment" method="POST" class="bg-white rounded-lg shadow-md p-6">
                    <?= CSRF::field() ?>
                    <h2 class="text-xl font-semibold mb-4">Méthode de livraison</h2>
                    <div class="space-y-3">
                        <label class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="shippingMethod" value="standard" checked class="mr-3">
                            <div>
                                <div class="font-medium">Livraison standard</div>
                                <div class="text-sm text-gray-600"><?= formatPrice(9.99) ?> - 5-7 jours ouvrables</div>
                            </div>
                        </label>
                        <label class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="shippingMethod" value="express" class="mr-3">
                            <div>
                                <div class="font-medium">Livraison express</div>
                                <div class="text-sm text-gray-600"><?= formatPrice(19.99) ?> - 2-3 jours ouvrables</div>
                            </div>
                        </label>
                    </div>
                    <button type="submit" class="mt-6 w-full px-6 py-3 bg-primary text-white rounded-lg hover:opacity-90 transition">
                        Continuer
                    </button>
                </form>
            <?php elseif (($step ?? 'address') === 'payment'): ?>
                <form action="<?= route('checkout.index') ?>?step=review" method="POST" class="bg-white rounded-lg shadow-md p-6">
                    <?= CSRF::field() ?>
                    <h2 class="text-xl font-semibold mb-4">Méthode de paiement</h2>
                    <div class="space-y-3">
                        <label class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="paymentMethod" value="card" checked class="mr-3">
                            <div>
                                <div class="font-medium">Carte bancaire</div>
                                <div class="text-sm text-gray-600">Visa, Mastercard, Amex</div>
                            </div>
                        </label>
                        <label class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="paymentMethod" value="paypal" class="mr-3">
                            <div class="font-medium">PayPal</div>
                        </label>
                    </div>
                    <button type="submit" class="mt-6 w-full px-6 py-3 bg-primary text-white rounded-lg hover:opacity-90 transition">
                        Continuer
                    </button>
                </form>
            <?php elseif (($step ?? 'address') === 'review'): ?>
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold mb-4">Récapitulatif de la commande</h2>
                    <div class="space-y-4 mb-6">
                        <?php foreach ($cartItems as $item): ?>
                            <div class="flex items-center gap-4 border-b pb-4">
                                <img src="<?= assetUrl(ltrim($item['image'], '/')) ?>" alt="<?= e($item['name']) ?>" class="w-16 h-16 object-cover rounded">
                                <div class="flex-1">
                                    <div class="font-medium"><?= e($item['name']) ?></div>
                                    <div class="text-sm text-gray-600">Quantité: <?= $item['qty'] ?></div>
                                </div>
                                <div class="font-semibold"><?= formatPrice($item['price'] * $item['qty']) ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <form action="<?= route('checkout.process') ?>" method="POST">
                        <?= CSRF::field() ?>
                        <button type="submit" class="w-full px-6 py-3 bg-primary text-white rounded-lg hover:opacity-90 transition font-semibold">
                            Confirmer la commande
                        </button>
                    </form>
                </div>
            <?php endif; ?>
        </div>

        <!-- Order Summary -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md p-6 sticky top-20">
                <h2 class="text-lg font-semibold mb-4">Résumé</h2>
                <div class="space-y-2 mb-4">
                    <div class="flex justify-between text-sm">
                        <span>Sous-total</span>
                        <span><?= formatPrice($subtotal) ?></span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span>Livraison</span>
                        <span><?= $shipping > 0 ? formatPrice($shipping) : 'Gratuite' ?></span>
                    </div>
                    <div class="border-t pt-2 mt-2">
                        <div class="flex justify-between font-semibold">
                            <span>Total</span>
                            <span><?= formatPrice($total) ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

