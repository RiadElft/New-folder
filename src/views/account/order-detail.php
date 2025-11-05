<div class="max-w-4xl mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Commande #<?= e($order['orderNumber']) ?></h1>
        <form action="<?= route('auth.logout') ?>" method="POST" class="inline">
            <?= CSRF::field() ?>
            <button type="submit" class="px-4 py-2 text-sm text-red-600 border border-red-300 rounded-lg hover:bg-red-50 transition">
                Déconnexion
            </button>
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold mb-4">Adresse de livraison</h2>
            <p class="text-gray-700">
                <?= e($order['shippingFirstName'] . ' ' . $order['shippingLastName']) ?><br>
                <?= e($order['shippingStreet']) ?><br>
                <?= e($order['shippingPostalCode'] . ' ' . $order['shippingCity']) ?><br>
                <?= e($order['shippingCountry']) ?>
            </p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold mb-4">Informations de commande</h2>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-600">Date:</span>
                    <span><?= date('d/m/Y H:i', strtotime($order['createdAt'])) ?></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Statut:</span>
                    <span class="px-2 py-1 text-xs rounded <?= 
                        $order['status'] === 'DELIVERED' ? 'bg-green-100 text-green-800' :
                        ($order['status'] === 'SHIPPED' ? 'bg-blue-100 text-blue-800' :
                        ($order['status'] === 'PROCESSING' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800'))
                    ?>">
                        <?= e($order['status']) ?>
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Méthode de paiement:</span>
                    <span><?= e($order['paymentMethod']) ?></span>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-lg font-semibold mb-4">Articles</h2>
        <div class="space-y-4">
            <?php foreach ($items as $item): ?>
                <div class="flex items-center gap-4 border-b pb-4">
                    <img src="<?= assetUrl(ltrim($item['image'], '/')) ?>" alt="<?= e($item['name']) ?>" class="w-16 h-16 object-cover rounded">
                    <div class="flex-1">
                        <div class="font-medium"><?= e($item['name']) ?></div>
                        <div class="text-sm text-gray-600">Quantité: <?= $item['quantity'] ?></div>
                    </div>
                    <div class="font-semibold"><?= formatPrice($item['price'] * $item['quantity']) ?></div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="mt-6 pt-4 border-t">
            <div class="flex justify-between mb-2">
                <span>Sous-total:</span>
                <span><?= formatPrice($order['subtotal']) ?></span>
            </div>
            <div class="flex justify-between mb-2">
                <span>Livraison:</span>
                <span><?= formatPrice($order['shipping']) ?></span>
            </div>
            <div class="flex justify-between font-semibold text-lg">
                <span>Total:</span>
                <span><?= formatPrice($order['total']) ?></span>
            </div>
        </div>
    </div>

    <a href="<?= route('account.orders') ?>" class="text-primary hover:text-accent-rose">
        ← Retour aux commandes
    </a>
</div>


