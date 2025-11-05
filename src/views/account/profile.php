<?php
$success = getFlash('success');
$error = getFlash('error');
?>

<div class="max-w-2xl mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Mon Profil</h1>
        <form action="<?= route('auth.logout') ?>" method="POST" class="inline">
            <?= CSRF::field() ?>
            <button type="submit" class="px-4 py-2 text-sm text-red-600 border border-red-300 rounded-lg hover:bg-red-50 transition">
                Déconnexion
            </button>
        </form>
    </div>

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

    <form action="<?= route('account.profile.update') ?>" method="POST" class="bg-white rounded-lg shadow-md p-6">
        <?= CSRF::field() ?>
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" value="<?= e($user['email']) ?>" disabled 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50">
                <p class="text-xs text-gray-500 mt-1">L'email ne peut pas être modifié</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nom</label>
                <input type="text" name="name" value="<?= e($user['name'] ?? '') ?>" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Téléphone</label>
                <input type="tel" name="phone" value="<?= e($user['phone'] ?? '') ?>" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nouveau mot de passe (optionnel)</label>
                <input type="password" name="password" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                <p class="text-xs text-gray-500 mt-1">Laissez vide pour ne pas changer</p>
            </div>
        </div>
        <button type="submit" class="mt-6 px-6 py-3 bg-primary text-white rounded-lg hover:opacity-90 transition">
            Enregistrer les modifications
        </button>
    </form>
</div>


