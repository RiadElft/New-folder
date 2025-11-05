<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? e($title) . ' - ' : '' ?>Sultan Library - Livres et Objets Culturels Islamiques</title>
    <meta name="description" content="<?= isset($description) ? e($description) : 'Découvrez notre collection de livres islamiques, calendriers, parfums et objets culturels de qualité.' ?>">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#56474A',
                        'accent-rose': '#E8CFC4',
                        'accent-beige': '#C4BEB2',
                        'accent-olive': '#B2C68E',
                        'neutral-black': '#000000',
                        'neutral-white': '#FFFFFF',
                        'neutral-dark': '#333333',
                        'neutral-medium': '#4F4F4F',
                        'neutral-gray': '#666666',
                        'neutral-light': '#999999',
                    }
                }
            }
        }
    </script>
    
    <!-- Custom CSS if needed -->
    <style>
        html { scroll-behavior: smooth; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Oxygen', 'Ubuntu', 'Cantarell', 'Fira Sans', 'Droid Sans', 'Helvetica Neue', sans-serif; }
    </style>
</head>
<body class="min-h-screen bg-white flex flex-col">
    <?php require_once __DIR__ . '/../partials/header.php'; ?>
    
    <main class="flex-1">
        <?php if (isset($content)): ?>
            <?= $content ?>
        <?php endif; ?>
    </main>
    
    <?php require_once __DIR__ . '/../partials/footer.php'; ?>
    
    <!-- JavaScript -->
    <script src="<?= baseUrl('js/app.js') ?>"></script>
</body>
</html>

