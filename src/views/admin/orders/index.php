<div>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Gérer les commandes</h1>
    </div>
    
    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-md p-4 mb-6">
        <form method="GET" action="<?= route('admin.orders') ?>" class="flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-gray-700 mb-1">Rechercher</label>
                <input type="text" name="search" value="<?= e($filters['search'] ?? '') ?>" placeholder="N° commande, email, nom..." 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                <select name="status" class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                    <option value="">Tous</option>
                    <option value="PENDING" <?= ($filters['status'] ?? '') === 'PENDING' ? 'selected' : '' ?>>En attente</option>
                    <option value="PROCESSING" <?= ($filters['status'] ?? '') === 'PROCESSING' ? 'selected' : '' ?>>En traitement</option>
                    <option value="SHIPPED" <?= ($filters['status'] ?? '') === 'SHIPPED' ? 'selected' : '' ?>>Expédié</option>
                    <option value="DELIVERED" <?= ($filters['status'] ?? '') === 'DELIVERED' ? 'selected' : '' ?>>Livré</option>
                    <option value="CANCELLED" <?= ($filters['status'] ?? '') === 'CANCELLED' ? 'selected' : '' ?>>Annulé</option>
                </select>
            </div>
            <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:opacity-90">
                Filtrer
            </button>
            <?php if (!empty($filters['search']) || !empty($filters['status'])): ?>
                <a href="<?= route('admin.orders') ?>" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                    Réinitialiser
                </a>
            <?php endif; ?>
        </form>
    </div>
    
    <!-- Results Count -->
    <?php if (isset($pagination['totalItems'])): ?>
        <div class="mb-4 text-sm text-gray-600">
            <?= $pagination['totalItems'] ?> commande<?= $pagination['totalItems'] > 1 ? 's' : '' ?> trouvée<?= $pagination['totalItems'] > 1 ? 's' : '' ?>
        </div>
    <?php endif; ?>
    
    <!-- Orders Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">N° Commande</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Client</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php if (empty($orders)): ?>
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                            Aucune commande trouvée
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($orders as $order): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 font-medium"><?= e($order['orderNumber']) ?></td>
                            <td class="px-6 py-4">
                                <div><?= e($order['userName'] ?? 'N/A') ?></div>
                                <div class="text-sm text-gray-500"><?= e($order['userEmail'] ?? '') ?></div>
                            </td>
                            <td class="px-6 py-4"><?= date('d/m/Y H:i', strtotime($order['createdAt'])) ?></td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs rounded font-medium <?= 
                                    $order['status'] === 'DELIVERED' ? 'bg-green-100 text-green-800' :
                                    ($order['status'] === 'SHIPPED' ? 'bg-blue-100 text-blue-800' :
                                    ($order['status'] === 'PROCESSING' ? 'bg-yellow-100 text-yellow-800' :
                                    ($order['status'] === 'CANCELLED' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')))
                                ?>">
                                    <?= e($order['status']) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 font-semibold"><?= formatPrice($order['total']) ?></td>
                            <td class="px-6 py-4">
                                <a href="<?= route('admin.order.detail', ['id' => $order['id']]) ?>" class="text-primary hover:text-accent-rose">
                                    Voir →
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <?php if (($pagination['total'] ?? 1) > 1): ?>
        <div class="mt-6 flex justify-center">
            <nav class="flex gap-2">
                <?php if ($pagination['current'] > 1): ?>
                    <a href="?page=<?= $pagination['current'] - 1 ?><?= !empty($filters) ? '&' . http_build_query($filters) : '' ?>" 
                       class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">Précédent</a>
                <?php endif; ?>
                
                <?php for ($i = max(1, $pagination['current'] - 2); $i <= min($pagination['total'], $pagination['current'] + 2); $i++): ?>
                    <a href="?page=<?= $i ?><?= !empty($filters) ? '&' . http_build_query($filters) : '' ?>" 
                       class="px-4 py-2 border rounded-lg <?= $i === $pagination['current'] ? 'bg-primary text-white border-primary' : 'border-gray-300 hover:bg-gray-50' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>
                
                <?php if ($pagination['current'] < $pagination['total']): ?>
                    <a href="?page=<?= $pagination['current'] + 1 ?><?= !empty($filters) ? '&' . http_build_query($filters) : '' ?>" 
                       class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">Suivant</a>
                <?php endif; ?>
            </nav>
        </div>
    <?php endif; ?>
</div>
