<div>
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Commande #<?= e($order['orderNumber']) ?></h1>
    
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-lg font-semibold mb-4">Changer le statut</h2>
        <form action="<?= route('admin.order.updateStatus', ['id' => $order['id']]) ?>" method="POST">
            <?= CSRF::field() ?>
            <select name="status" class="px-3 py-2 border border-gray-300 rounded-lg mr-4">
                <option value="PENDING" <?= $order['status'] === 'PENDING' ? 'selected' : '' ?>>En attente</option>
                <option value="CONFIRMED" <?= $order['status'] === 'CONFIRMED' ? 'selected' : '' ?>>Confirmée</option>
                <option value="PROCESSING" <?= $order['status'] === 'PROCESSING' ? 'selected' : '' ?>>En traitement</option>
                <option value="SHIPPED" <?= $order['status'] === 'SHIPPED' ? 'selected' : '' ?>>Expédiée</option>
                <option value="DELIVERED" <?= $order['status'] === 'DELIVERED' ? 'selected' : '' ?>>Livrée</option>
                <option value="CANCELLED" <?= $order['status'] === 'CANCELLED' ? 'selected' : '' ?>>Annulée</option>
            </select>
            <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:opacity-90">Mettre à jour</button>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-lg font-semibold mb-4">Détails</h2>
        <div class="space-y-2">
            <div class="flex justify-between">
                <span class="text-gray-600">Total:</span>
                <span class="font-semibold"><?= formatPrice($order['total']) ?></span>
            </div>
        </div>
        <h3 class="text-lg font-semibold mt-6 mb-4">Articles</h3>
        <div class="space-y-4">
            <?php foreach ($items as $item): ?>
                <div class="flex items-center gap-4 border-b pb-4">
                    <div class="flex-1">
                        <div class="font-medium"><?= e($item['name']) ?></div>
                        <div class="text-sm text-gray-600">Quantité: <?= $item['quantity'] ?></div>
                    </div>
                    <div class="font-semibold"><?= formatPrice($item['price'] * $item['quantity']) ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>


