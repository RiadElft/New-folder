<div class="max-w-2xl mx-auto px-4 py-12 text-center">
    <div class="bg-green-100 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-6">
        <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
        </svg>
    </div>
    <h1 class="text-3xl font-bold text-gray-900 mb-4">Commande confirmée !</h1>
    <p class="text-gray-600 mb-8">Merci pour votre commande. Vous recevrez un email de confirmation sous peu.</p>
    <div class="flex gap-4 justify-center">
        <a href="<?= route('account.orders') ?>" class="px-6 py-3 bg-primary text-white rounded-lg hover:opacity-90 transition">
            Voir mes commandes
        </a>
        <a href="<?= route('products.index') ?>" class="px-6 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
            Continuer les achats
        </a>
    </div>
</div>


