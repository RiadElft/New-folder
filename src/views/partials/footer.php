<footer class="bg-primary text-white">
    <!-- Main Footer -->
    <div class="max-w-7xl mx-auto px-4 py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- About Section -->
            <div>
                <h3 class="text-white font-semibold text-lg mb-4">Sultan Library</h3>
                <p class="text-sm mb-4 text-white/80">
                    Votre librairie musulmane en ligne. Découvrez notre sélection de livres islamiques, parfums, qamis et bien plus encore.
                </p>
                <div class="flex gap-4">
                    <a href="https://www.instagram.com/sultanlibrary" target="_blank" rel="noopener noreferrer" class="hover:text-white transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                        </svg>
                    </a>
                    <a href="https://www.facebook.com/sultanlibrary" target="_blank" rel="noopener noreferrer" class="hover:text-white transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Quick Links -->
            <div>
                <h3 class="text-white font-semibold text-lg mb-4">Liens Rapides</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="<?= baseUrl('produits') ?>" class="hover:text-white transition-colors">Tous les produits</a></li>
                    <li><a href="<?= baseUrl('categorie/livres') ?>" class="hover:text-white transition-colors">Livres</a></li>
                    <li><a href="<?= baseUrl('categorie/parfum') ?>" class="hover:text-white transition-colors">Parfums</a></li>
                    <li><a href="<?= baseUrl('categorie/qamis') ?>" class="hover:text-white transition-colors">Qamis</a></li>
                    <li><a href="<?= baseUrl('compte') ?>" class="hover:text-white transition-colors">Mon Compte</a></li>
                </ul>
            </div>

            <!-- Customer Service -->
            <div>
                <h3 class="text-white font-semibold text-lg mb-4">Service Client</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="<?= baseUrl('contact') ?>" class="hover:text-white transition-colors">Nous Contacter</a></li>
                    <li><a href="<?= baseUrl('faq') ?>" class="hover:text-white transition-colors">FAQ</a></li>
                    <li><a href="<?= baseUrl('livraison') ?>" class="hover:text-white transition-colors">Livraison</a></li>
                    <li><a href="<?= baseUrl('retours') ?>" class="hover:text-white transition-colors">Retours & Remboursements</a></li>
                    <li><a href="<?= baseUrl('a-propos') ?>" class="hover:text-white transition-colors">À Propos</a></li>
                </ul>
            </div>

            <!-- Contact Info -->
            <div>
                <h3 class="text-white font-semibold text-lg mb-4">Contact</h3>
                <ul class="space-y-3 text-sm">
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        <a href="mailto:contact@sultanlibrary.com" class="hover:text-white transition-colors">contact@sultanlibrary.com</a>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        <span class="text-white/80">+33 1 23 45 67 89</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span class="text-white/80">Paris, France</span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Newsletter -->
        <div class="mt-12 pt-8 border-t border-white/10">
            <div class="max-w-md mx-auto text-center">
                <h3 class="text-white font-semibold text-lg mb-2">Newsletter</h3>
                <p class="text-sm mb-4 text-white/80">
                    Inscrivez-vous pour recevoir nos offres exclusives et nouveautés
                </p>
                <form action="<?= baseUrl('newsletter') ?>" method="POST" class="flex gap-2">
                    <?= CSRF::field() ?>
                    <input
                        type="email"
                        name="email"
                        placeholder="Votre email"
                        class="flex-1 px-4 py-2 rounded-lg bg-white/10 border border-white/20 focus:outline-none focus:border-white text-white placeholder:text-white/60"
                        required
                    />
                    <button type="submit" class="px-6 py-2 bg-white text-primary font-semibold rounded-lg hover:opacity-90 transition-colors">
                        S'inscrire
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Bottom Bar -->
    <div class="border-t border-white/10">
        <div class="max-w-7xl mx-auto px-4 py-6">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <!-- Copyright -->
                <div class="text-sm text-white/80">
                    © <?= date('Y') ?> Sultan Library. Tous droits réservés.
                </div>

                <!-- Payment Methods -->
                <div class="flex items-center gap-4">
                    <span class="text-sm text-white/80">Paiement sécurisé:</span>
                    <div class="flex gap-2">
                        <svg class="w-8 h-6 text-white/70" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M20 4H4c-1.11 0-1.99.89-1.99 2L2 18c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2zm0 14H4v-6h16v6zm0-10H4V6h16v2z"/>
                        </svg>
                    </div>
                </div>

                <!-- Legal Links -->
                <div class="flex gap-4 text-sm">
                    <a href="<?= baseUrl('cgv') ?>" class="hover:text-white transition-colors">CGV</a>
                    <a href="<?= baseUrl('mentions-legales') ?>" class="hover:text-white transition-colors">Mentions Légales</a>
                    <a href="<?= baseUrl('confidentialite') ?>" class="hover:text-white transition-colors">Confidentialité</a>
                </div>
            </div>
        </div>
    </div>
</footer>


