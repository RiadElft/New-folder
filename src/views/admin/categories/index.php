<div>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Gérer les catégories</h1>
    </div>
    
    <?php $success = getFlash('success'); $error = getFlash('error'); ?>
    <?php if ($success): ?>
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded mb-4">
            <?= e($success) ?>
        </div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-4">
            <?= e($error) ?>
        </div>
    <?php endif; ?>
    
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-lg font-semibold mb-4">Nouvelle catégorie</h2>
        <form action="<?= route('admin.category.store') ?>" method="POST" class="space-y-4">
            <?= CSRF::field() ?>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nom *</label>
                    <input type="text" name="name" placeholder="Nom de la catégorie" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
                    <input type="text" name="slug" placeholder="Auto-généré si vide" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                    <p class="text-xs text-gray-500 mt-1">Laisser vide pour auto-génération</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Catégorie parente</label>
                    <select name="parentId" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                        <option value="">Aucune (catégorie principale)</option>
                        <?php foreach ($categories as $cat): ?>
                            <?php if (!$cat['parentId']): ?>
                                <option value="<?= e($cat['id']) ?>"><?= e($cat['name']) ?></option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Ordre d'affichage</label>
                    <input type="number" name="sortOrder" value="0" min="0" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="description" rows="3" placeholder="Description de la catégorie" 
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"></textarea>
            </div>
            <div>
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="isActive" checked class="rounded border-gray-300 text-primary focus:ring-primary">
                    <span>Active</span>
                </label>
            </div>
            <button type="submit" class="px-6 py-2 bg-primary text-white rounded-lg hover:opacity-90">
                Créer la catégorie
            </button>
        </form>
    </div>
    
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nom</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Slug</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Parent</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ordre</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php 
                $mainCategories = array_filter($categories, function($cat) { return !$cat['parentId']; });
                $subCategories = array_filter($categories, function($cat) { return $cat['parentId']; });
                ?>
                <?php foreach ($mainCategories as $category): ?>
                    <tr class="bg-gray-50">
                        <td class="px-6 py-4 font-semibold"><?= e($category['name']) ?></td>
                        <td class="px-6 py-4 text-gray-600"><?= e($category['slug']) ?></td>
                        <td class="px-6 py-4 text-gray-500">—</td>
                        <td class="px-6 py-4"><?= e($category['sortOrder'] ?? 0) ?></td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded <?= $category['isActive'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                <?= $category['isActive'] ? 'Active' : 'Inactive' ?>
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <form action="<?= route('admin.category.delete', ['id' => $category['id']]) ?>" method="POST" class="inline">
                                <?= CSRF::field() ?>
                                <button type="submit" onclick="return confirm('Êtes-vous sûr ? Cette action supprimera aussi toutes les sous-catégories.')" 
                                        class="text-red-600 hover:text-red-800 text-sm">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                    <?php 
                    $children = array_filter($subCategories, function($cat) use ($category) { 
                        return $cat['parentId'] === $category['id']; 
                    });
                    foreach ($children as $subcat): ?>
                        <tr>
                            <td class="px-6 py-4 pl-12">
                                <span class="text-gray-400">└─</span> <?= e($subcat['name']) ?>
                            </td>
                            <td class="px-6 py-4 text-gray-600"><?= e($subcat['slug']) ?></td>
                            <td class="px-6 py-4 text-gray-500"><?= e($category['name']) ?></td>
                            <td class="px-6 py-4"><?= e($subcat['sortOrder'] ?? 0) ?></td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs rounded <?= $subcat['isActive'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                    <?= $subcat['isActive'] ? 'Active' : 'Inactive' ?>
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <form action="<?= route('admin.category.delete', ['id' => $subcat['id']]) ?>" method="POST" class="inline">
                                    <?= CSRF::field() ?>
                                    <button type="submit" onclick="return confirm('Êtes-vous sûr ?')" 
                                            class="text-red-600 hover:text-red-800 text-sm">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>


