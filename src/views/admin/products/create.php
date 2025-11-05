<div>
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Nouveau produit</h1>
    
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
    
    <form action="<?= route('admin.product.store') ?>" method="POST" enctype="multipart/form-data" class="bg-white rounded-lg shadow-md p-6 space-y-6">
        <?= CSRF::field() ?>
        
        <!-- Basic Information -->
        <div class="border-b pb-6">
            <h2 class="text-xl font-semibold mb-4">Informations de base</h2>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nom *</label>
                    <input type="text" name="name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Prix *</label>
                    <input type="number" step="0.01" name="price" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Prix original (optionnel)</label>
                    <input type="number" step="0.01" name="originalPrice" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Badge</label>
                    <select name="badge" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                        <option value="">Aucun</option>
                        <option value="new">Nouveau</option>
                        <option value="bestseller">Meilleure vente</option>
                        <option value="promo">Promotion</option>
                    </select>
                </div>
            </div>
            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Description courte</label>
                <textarea name="shortDescription" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"></textarea>
            </div>
            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Description complète</label>
                <textarea name="description" rows="6" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"></textarea>
            </div>
        </div>
        
        <!-- Category -->
        <div class="border-b pb-6">
            <h2 class="text-xl font-semibold mb-4">Catégorie</h2>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Catégorie principale *</label>
                    <select name="categoryId" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                        <option value="">Sélectionner...</option>
                        <?php foreach ($categories as $cat): ?>
                            <?php if (!$cat['parentId']): ?>
                                <option value="<?= e($cat['id']) ?>"><?= e($cat['name']) ?></option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sous-catégorie</label>
                    <select name="subcategoryId" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                        <option value="">Aucune</option>
                        <?php foreach ($categories as $cat): ?>
                            <?php if ($cat['parentId']): ?>
                                <option value="<?= e($cat['id']) ?>" data-parent="<?= e($cat['parentId']) ?>"><?= e($cat['name']) ?></option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
        
        <!-- Images -->
        <div class="border-b pb-6">
            <h2 class="text-xl font-semibold mb-4">Images</h2>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Image principale *</label>
                    <input type="file" name="image" accept="image/*" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                    <p class="text-xs text-gray-500 mt-1">JPEG, PNG ou WebP (max 5MB)</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Images supplémentaires</label>
                    <input type="file" name="images[]" accept="image/*" multiple class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                    <p class="text-xs text-gray-500 mt-1">Vous pouvez sélectionner plusieurs images</p>
                </div>
            </div>
        </div>
        
        <!-- Stock -->
        <div class="border-b pb-6">
            <h2 class="text-xl font-semibold mb-4">Stock</h2>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="inStock" checked class="rounded border-gray-300 text-primary focus:ring-primary">
                        <span>En stock</span>
                    </label>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Quantité en stock</label>
                    <input type="number" name="stockQuantity" value="0" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                </div>
            </div>
        </div>
        
        <!-- Book Details (Optional) -->
        <div class="border-b pb-6">
            <h2 class="text-xl font-semibold mb-4">Détails livre (optionnel)</h2>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Auteur</label>
                    <input type="text" name="author" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Éditeur</label>
                    <input type="text" name="publisher" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">ISBN</label>
                    <input type="text" name="isbn" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nombre de pages</label>
                    <input type="number" name="pages" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Langue</label>
                    <input type="text" name="language" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Format</label>
                    <select name="format" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                        <option value="">Sélectionner...</option>
                        <option value="Broché">Broché</option>
                        <option value="Relié">Relié</option>
                        <option value="Poche">Poche</option>
                    </select>
                </div>
            </div>
        </div>
        
        <!-- Tags -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Tags (séparés par des virgules)</label>
            <input type="text" name="tags" placeholder="tag1, tag2, tag3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
        </div>
        
        <div class="flex gap-4 pt-4">
            <button type="submit" class="px-6 py-3 bg-primary text-white rounded-lg hover:opacity-90 transition">
                Créer le produit
            </button>
            <a href="<?= route('admin.products') ?>" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                Annuler
            </a>
        </div>
    </form>
</div>

<script>
// Update subcategories based on selected category
document.querySelector('select[name="categoryId"]').addEventListener('change', function() {
    const categoryId = this.value;
    const subcategorySelect = document.querySelector('select[name="subcategoryId"]');
    
    Array.from(subcategorySelect.options).forEach(option => {
        if (option.value === '') {
            option.selected = true;
        } else {
            option.style.display = option.dataset.parent === categoryId ? '' : 'none';
        }
    });
});
</script>
