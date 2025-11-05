<div>
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Hero Products</h1>

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

    <form action="<?= route('admin.hero.save') ?>" method="POST" class="bg-white rounded-lg shadow-md p-6">
        <?= CSRF::field() ?>
        <p class="text-sm text-gray-600 mb-4">Sélectionnez et ordonnez les produits à afficher dans le carrousel d'accueil. Faites glisser pour réordonner.</p>

        <div id="hero-sortable" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <?php
            // Build map for selected ordering
            $selectedSet = array_flip($selectedIds);
            // Show selected first in their order
            $ordered = [];
            foreach ($selectedIds as $sid) {
                foreach ($products as $p) { if ($p['id'] === $sid) { $ordered[] = $p; } }
            }
            // Then show remaining
            foreach ($products as $p) { if (!isset($selectedSet[$p['id']])) { $ordered[] = $p; } }
            ?>
            <?php foreach ($ordered as $p): ?>
                <div class="item border rounded-lg p-3 flex items-center gap-3 bg-white shadow-sm" data-id="<?= e($p['id']) ?>">
                    <img src="<?= baseUrl(ltrim($p['image'], '/')) ?>" alt="" class="w-14 h-14 object-cover rounded">
                    <div class="flex-1">
                        <div class="font-medium text-gray-900 text-sm line-clamp-2"><?= e($p['name']) ?></div>
                        <div class="text-xs text-gray-500"><?= formatPrice($p['price']) ?></div>
                    </div>
                    <label class="inline-flex items-center gap-2 text-sm">
                        <input type="checkbox" class="select-checkbox" <?= isset($selectedSet[$p['id']]) ? 'checked' : '' ?>>
                        <span>Sélectionner</span>
                    </label>
                </div>
            <?php endforeach; ?>
        </div>

        <input type="hidden" name="ordered" id="ordered-input">

        <div class="mt-6 flex gap-3">
            <button type="submit" class="px-6 py-3 bg-primary text-white rounded-lg hover:opacity-90">Enregistrer</button>
            <a href="<?= route('admin.dashboard') ?>" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">Annuler</a>
        </div>
    </form>
</div>

<script>
// Simple drag-reorder without external libs
const container = document.getElementById('hero-sortable');
let dragSrcEl = null;

container.addEventListener('dragstart', (e) => {
    const item = e.target.closest('.item');
    if (!item) return;
    dragSrcEl = item;
    item.style.opacity = '0.6';
});
container.addEventListener('dragend', (e) => {
    const item = e.target.closest('.item');
    if (item) item.style.opacity = '';
});
container.addEventListener('dragover', (e) => {
    e.preventDefault();
    const over = e.target.closest('.item');
    if (!over || over === dragSrcEl) return;
    const rect = over.getBoundingClientRect();
    const next = (e.clientY - rect.top) / (rect.bottom - rect.top) > 0.5;
    container.insertBefore(dragSrcEl, next ? over.nextSibling : over);
});

// Make items draggable
Array.from(container.children).forEach(el => el.setAttribute('draggable', 'true'));

// On submit, collect ordered selected ids only
document.querySelector('form[action="<?= route('admin.hero.save') ?>"]').addEventListener('submit', (e) => {
    const order = [];
    Array.from(container.children).forEach(el => {
        const cb = el.querySelector('.select-checkbox');
        if (cb && cb.checked) {
            order.push(el.getAttribute('data-id'));
        }
    });
    document.getElementById('ordered-input').value = order.join(',');
});
</script>


