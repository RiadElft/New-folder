<div class="max-w-2xl mx-auto px-4 py-12">
    <h1 class="text-4xl font-bold text-gray-900 mb-8">Contactez-nous</h1>
    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="<?= baseUrl('contact') ?>" method="POST">
            <?= CSRF::field() ?>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nom *</label>
                    <input type="text" name="name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                    <input type="email" name="email" required class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Message *</label>
                    <textarea name="message" rows="6" required class="w-full px-3 py-2 border border-gray-300 rounded-lg"></textarea>
                </div>
                <button type="submit" class="w-full px-6 py-3 bg-primary text-white rounded-lg hover:opacity-90">
                    Envoyer
                </button>
            </div>
        </form>
    </div>
    <div class="mt-8 text-center">
        <p class="text-gray-600">Ou contactez-nous directement :</p>
        <p class="mt-2"><a href="mailto:contact@sultanlibrary.com" class="text-primary hover:text-accent-rose">contact@sultanlibrary.com</a></p>
        <p class="mt-2">+33 1 23 45 67 89</p>
    </div>
</div>


