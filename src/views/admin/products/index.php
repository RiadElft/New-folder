<div>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Gérer les produits</h1>
        <a href="<?= baseUrl('admin/produits/nouveau') ?>" class="px-4 py-2 bg-primary text-white rounded-lg hover:opacity-90">
            Nouveau produit
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Image</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nom</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Prix</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stock</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td class="px-6 py-4">
                            <img src="<?= baseUrl(ltrim($product['image'], '/')) ?>" alt="" class="w-16 h-16 object-cover rounded">
                        </td>
                        <td class="px-6 py-4 font-medium"><?= e($product['name']) ?></td>
                        <td class="px-6 py-4"><?= formatPrice($product['price']) ?></td>
                        <td class="px-6 py-4">
                            <span class="<?= $product['inStock'] ? 'text-green-600' : 'text-red-600' ?>">
                                <?= $product['inStock'] ? 'En stock' : 'Rupture' ?>
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <a href="<?= baseUrl('admin/produits/' . $product['id']) ?>" class="text-primary hover:text-accent-rose mr-4">Modifier</a>
                            <form action="<?= baseUrl('admin/produits/' . $product['id'] . '/delete') ?>" method="POST" class="inline">
                                <?= CSRF::field() ?>
                                <button type="submit" onclick="return confirm('Êtes-vous sûr ?')" class="text-red-600 hover:text-red-800">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>


