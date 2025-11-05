<?php
$title = 'Page non trouvée';
require_once __DIR__ . '/../../views/layouts/main.php';
?>

<div class="min-h-screen flex items-center justify-center bg-gray-50">
    <div class="text-center">
        <h1 class="text-6xl font-bold text-primary mb-4">404</h1>
        <h2 class="text-2xl font-semibold text-gray-900 mb-4">Page non trouvée</h2>
        <p class="text-gray-600 mb-8">La page que vous recherchez n'existe pas.</p>
        <a href="<?= baseUrl('') ?>" class="inline-block px-6 py-3 bg-primary text-white rounded-lg hover:bg-opacity-90 transition">
            Retour à l'accueil
        </a>
    </div>
</div>

<?php require_once __DIR__ . '/../../views/layouts/footer.php'; ?>


