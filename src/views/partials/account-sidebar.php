<?php
require_once __DIR__ . '/../../lib/Auth.php';
$user = Auth::user();
$isAdmin = Auth::isAdmin();
?>
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <div class="flex items-center gap-4 mb-6 pb-6 border-b border-gray-200">
        <div class="w-12 h-12 bg-primary rounded-full flex items-center justify-center text-white font-semibold">
            <?= strtoupper(substr($user['name'] ?? $user['email'], 0, 1)) ?>
        </div>
        <div>
            <div class="font-semibold text-gray-900"><?= e($user['name'] ?? 'Utilisateur') ?></div>
            <div class="text-sm text-gray-600"><?= e($user['email']) ?></div>
        </div>
    </div>
    
    <nav class="space-y-2">
        <a href="<?= route('account.index') ?>" class="flex items-center gap-3 px-4 py-2 rounded-lg <?= isRouteName('account.index') ? 'bg-primary text-white' : 'text-gray-700 hover:bg-gray-100' ?>">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
            </svg>
            Tableau de bord
        </a>
        <a href="<?= route('account.profile') ?>" class="flex items-center gap-3 px-4 py-2 rounded-lg <?= isRouteName('account.profile') ? 'bg-primary text-white' : 'text-gray-700 hover:bg-gray-100' ?>">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            Mon Profil
        </a>
        <a href="<?= route('account.orders') ?>" class="flex items-center gap-3 px-4 py-2 rounded-lg <?= isAnyRouteName(['account.orders', 'account.order.detail']) ? 'bg-primary text-white' : 'text-gray-700 hover:bg-gray-100' ?>">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
            </svg>
            Mes Commandes
        </a>
        <a href="<?= route('account.addresses') ?>" class="flex items-center gap-3 px-4 py-2 rounded-lg <?= isRouteName('account.addresses') ? 'bg-primary text-white' : 'text-gray-700 hover:bg-gray-100' ?>">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.293 12.293a1.999 1.999 0 00-2.827 0l-4.364 4.364a8 8 0 11.707-.707l4.364-4.364a2 2 0 012.828 0l4.364 4.364a8 8 0 11.707.707z"></path>
            </svg>
            Mes Adresses
        </a>
        <a href="<?= route('account.wishlist') ?>" class="flex items-center gap-3 px-4 py-2 rounded-lg <?= isRouteName('account.wishlist') ? 'bg-primary text-white' : 'text-gray-700 hover:bg-gray-100' ?>">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
            </svg>
            Mes Favoris
        </a>
        <?php if ($isAdmin): ?>
            <div class="border-t border-gray-200 my-2 pt-2">
                <a href="<?= route('admin.dashboard') ?>" class="flex items-center gap-3 px-4 py-2 rounded-lg text-gray-700 hover:bg-gray-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                    Admin Panel
                </a>
            </div>
        <?php endif; ?>
        <div class="border-t border-gray-200 my-2 pt-2">
            <form action="<?= route('auth.logout') ?>" method="POST">
                <?= CSRF::field() ?>
                <button type="submit" class="w-full flex items-center gap-3 px-4 py-2 rounded-lg text-red-600 hover:bg-red-50">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    Déconnexion
                </button>
            </form>
        </div>
    </nav>
</div>

