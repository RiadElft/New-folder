<?php
$success = getFlash('success');
?>

<div class="max-w-4xl mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Mes Adresses</h1>
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

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <?php foreach ($addresses as $address): ?>
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="font-semibold"><?= e($address['firstName'] . ' ' . $address['lastName']) ?></h3>
                    <?php if ($address['isDefault']): ?>
                        <span class="text-xs bg-primary text-white px-2 py-1 rounded">Par défaut</span>
                    <?php endif; ?>
                </div>
                <p class="text-gray-700 text-sm mb-4">
                    <?= e($address['street']) ?><br>
                    <?= e($address['postalCode'] . ' ' . $address['city']) ?><br>
                    <?= e($address['country']) ?>
                </p>
                <?php if ($address['phone']): ?>
                    <p class="text-sm text-gray-600 mb-4"><?= e($address['phone']) ?></p>
                <?php endif; ?>
                <form action="<?= route('account.address.delete', ['id' => $address['id']]) ?>" method="POST" class="inline">
                    <?= CSRF::field() ?>
                    <button type="submit" class="text-red-600 hover:text-red-800 text-sm">Supprimer</button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold mb-4">Ajouter une nouvelle adresse</h2>
        <form action="<?= route('account.address.add') ?>" method="POST">
            <?= CSRF::field() ?>
            <div class="grid grid-cols-2 gap-4 mb-4">
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
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Adresse *</label>
                <input type="text" name="street" required 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
            </div>
            <div class="grid grid-cols-2 gap-4 mb-4">
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
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Pays</label>
                <input type="text" name="country" value="France" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Téléphone</label>
                <input type="tel" name="phone" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
            </div>
            <button type="submit" class="px-6 py-3 bg-primary text-white rounded-lg hover:opacity-90 transition">
                Ajouter l'adresse
            </button>
        </form>
    </div>
</div>


