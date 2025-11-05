<?php
require_once __DIR__ . '/../../lib/Auth.php';
$currentUser = Auth::user();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? e($title) . ' - ' : '' ?>Admin - Sultan Library</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#56474A',
                        'accent-rose': '#E8CFC4',
                        'accent-beige': '#C4BEB2',
                    }
                }
            }
        }
    </script>
    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside class="w-64 bg-primary text-white flex-shrink-0 hidden lg:flex flex-col">
            <!-- Logo -->
            <div class="p-6 border-b border-white/10">
                <h1 class="text-2xl font-bold">Admin Panel</h1>
                <p class="text-sm text-white/70 mt-1">Sultan Library</p>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
                <a href="<?= route('admin.dashboard') ?>" class="flex items-center gap-3 px-4 py-3 rounded-lg transition <?= isRouteName('admin.dashboard') ? 'bg-white/20 text-white font-semibold' : 'text-white/80 hover:bg-white/10' ?>">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    Dashboard
                </a>
                <a href="<?= route('admin.products') ?>" class="flex items-center gap-3 px-4 py-3 rounded-lg transition <?= isAnyRouteName(['admin.products', 'admin.product.create', 'admin.product.edit']) ? 'bg-white/20 text-white font-semibold' : 'text-white/80 hover:bg-white/10' ?>">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    Produits
                </a>
                <a href="<?= route('admin.categories') ?>" class="flex items-center gap-3 px-4 py-3 rounded-lg transition <?= isRouteName('admin.categories') ? 'bg-white/20 text-white font-semibold' : 'text-white/80 hover:bg-white/10' ?>">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                    </svg>
                    Catégories
                </a>
                <a href="<?= route('admin.hero') ?>" class="flex items-center gap-3 px-4 py-3 rounded-lg transition <?= isRouteName('admin.hero') ? 'bg-white/20 text-white font-semibold' : 'text-white/80 hover:bg-white/10' ?>">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v12a1 1 0 01-1 1H8l-5 5V4z"></path>
                    </svg>
                    Hero d'accueil
                </a>
                <a href="<?= route('admin.orders') ?>" class="flex items-center gap-3 px-4 py-3 rounded-lg transition <?= isAnyRouteName(['admin.orders', 'admin.order.detail']) ? 'bg-white/20 text-white font-semibold' : 'text-white/80 hover:bg-white/10' ?>">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    Commandes
                </a>
                <a href="<?= route('admin.users') ?>" class="flex items-center gap-3 px-4 py-3 rounded-lg transition <?= isRouteName('admin.users') ? 'bg-white/20 text-white font-semibold' : 'text-white/80 hover:bg-white/10' ?>">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    Utilisateurs
                </a>
            </nav>

            <!-- User Info & Logout -->
            <div class="p-4 border-t border-white/10">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center font-semibold">
                        <?= strtoupper(substr($currentUser['name'] ?? $currentUser['email'], 0, 1)) ?>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold truncate"><?= e($currentUser['name'] ?? 'Admin') ?></p>
                        <p class="text-xs text-white/70 truncate"><?= e($currentUser['email']) ?></p>
                    </div>
                </div>
                <div class="flex gap-2">
                    <a href="<?= route('home') ?>" class="flex-1 px-3 py-2 text-sm bg-white/10 hover:bg-white/20 rounded-lg transition text-center">
                        Voir le site
                    </a>
                    <form action="<?= route('auth.logout') ?>" method="POST" class="flex-1">
                        <?= CSRF::field() ?>
                        <button type="submit" class="w-full px-3 py-2 text-sm bg-red-500/20 hover:bg-red-500/30 rounded-lg transition">
                            Déconnexion
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Bar (Mobile) -->
            <header class="bg-white border-b border-gray-200 lg:hidden">
                <div class="flex items-center justify-between p-4">
                    <h1 class="text-lg font-bold text-primary">Admin</h1>
                    <button onclick="toggleMobileMenu()" class="p-2 hover:bg-gray-100 rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
                <!-- Mobile Menu -->
                <div id="mobile-menu" class="hidden border-t border-gray-200 p-4 bg-white">
                    <nav class="space-y-2">
                        <a href="<?= route('admin.dashboard') ?>" class="block px-4 py-2 rounded-lg <?= isRouteName('admin.dashboard') ? 'bg-primary text-white' : 'text-gray-700 hover:bg-gray-100' ?>">Dashboard</a>
                        <a href="<?= route('admin.products') ?>" class="block px-4 py-2 rounded-lg <?= isAnyRouteName(['admin.products', 'admin.product.create', 'admin.product.edit']) ? 'bg-primary text-white' : 'text-gray-700 hover:bg-gray-100' ?>">Produits</a>
                        <a href="<?= route('admin.categories') ?>" class="block px-4 py-2 rounded-lg <?= isRouteName('admin.categories') ? 'bg-primary text-white' : 'text-gray-700 hover:bg-gray-100' ?>">Catégories</a>
                        <a href="<?= route('admin.orders') ?>" class="block px-4 py-2 rounded-lg <?= isAnyRouteName(['admin.orders', 'admin.order.detail']) ? 'bg-primary text-white' : 'text-gray-700 hover:bg-gray-100' ?>">Commandes</a>
                        <a href="<?= route('admin.users') ?>" class="block px-4 py-2 rounded-lg <?= isRouteName('admin.users') ? 'bg-primary text-white' : 'text-gray-700 hover:bg-gray-100' ?>">Utilisateurs</a>
                        <a href="<?= route('admin.hero') ?>" class="block px-4 py-2 rounded-lg <?= isRouteName('admin.hero') ? 'bg-primary text-white' : 'text-gray-700 hover:bg-gray-100' ?>">Hero d'accueil</a>
                        <div class="border-t border-gray-200 pt-2 mt-2">
                            <a href="<?= route('home') ?>" class="block px-4 py-2 rounded-lg text-gray-700 hover:bg-gray-100">Voir le site</a>
                            <form action="<?= route('auth.logout') ?>" method="POST" class="block">
                                <?= CSRF::field() ?>
                                <button type="submit" class="w-full text-left px-4 py-2 rounded-lg text-red-600 hover:bg-red-50">Déconnexion</button>
                            </form>
                        </div>
                    </nav>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-6">
                <?php if (isset($content)): ?>
                    <?= $content ?>
                <?php endif; ?>
            </main>
        </div>
    </div>

    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        }
    </script>
</body>
</html>

