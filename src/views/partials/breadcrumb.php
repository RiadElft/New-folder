<?php if (!empty($breadcrumbItems)): ?>
    <nav class="mb-6">
        <ol class="flex items-center space-x-2 text-sm">
            <?php foreach ($breadcrumbItems as $index => $item): ?>
                <?php if ($index > 0): ?>
                    <li><span class="text-gray-400">/</span></li>
                <?php endif; ?>
                <li>
                    <?php if (isset($item['href'])): ?>
                        <a href="<?= baseUrl(ltrim($item['href'], '/')) ?>" class="text-gray-500 hover:text-primary">
                            <?= e($item['label']) ?>
                        </a>
                    <?php else: ?>
                        <span class="text-gray-900"><?= e($item['label']) ?></span>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ol>
    </nav>
<?php endif; ?>


