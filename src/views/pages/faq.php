<div class="max-w-4xl mx-auto px-4 py-12">
    <h1 class="text-4xl font-bold text-gray-900 mb-8">Questions Fréquentes</h1>
    <div class="space-y-4">
        <?php
        $faqs = [
            [
                'question' => 'Quels sont les délais de livraison ?',
                'answer' => 'Les délais de livraison varient selon la méthode choisie : Livraison standard (5-7 jours ouvrables), Livraison express (2-3 jours ouvrables). La livraison est gratuite pour les commandes supérieures à 100 €.'
            ],
            [
                'question' => 'Puis-je retourner un produit ?',
                'answer' => 'Oui, vous disposez d\'un délai de 14 jours à compter de la réception pour retourner un produit non conforme ou défectueux. Le produit doit être dans son emballage d\'origine et non utilisé.'
            ],
            [
                'question' => 'Quels modes de paiement acceptez-vous ?',
                'answer' => 'Nous acceptons les cartes bancaires (Visa, Mastercard, Amex) et PayPal. Tous les paiements sont sécurisés via SSL.'
            ],
            [
                'question' => 'Comment suivre ma commande ?',
                'answer' => 'Une fois votre commande expédiée, vous recevrez un email avec un numéro de suivi que vous pourrez utiliser sur le site du transporteur.'
            ],
            [
                'question' => 'Proposez-vous la livraison à l\'étranger ?',
                'answer' => 'Oui, nous livrons en France métropolitaine, Belgique et Suisse. Les frais de livraison varient selon la destination.'
            ],
            [
                'question' => 'Les produits sont-ils authentiques ?',
                'answer' => 'Absolument. Nous garantissons l\'authenticité de tous nos produits. Nous travaillons uniquement avec des fournisseurs de confiance et certifiés.'
            ],
            [
                'question' => 'Puis-je modifier ou annuler ma commande ?',
                'answer' => 'Vous pouvez modifier ou annuler votre commande dans les 24 heures suivant la passation. Contactez-nous par email avec votre numéro de commande.'
            ],
            [
                'question' => 'Avez-vous une boutique physique ?',
                'answer' => 'Pour le moment, nous sommes uniquement en ligne. Cependant, nous prévoyons d\'ouvrir des points de vente dans le futur.'
            ]
        ];
        ?>
        <?php foreach ($faqs as $index => $faq): ?>
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <button onclick="toggleFaq(<?= $index ?>)" class="w-full px-6 py-4 text-left flex items-center justify-between hover:bg-gray-50 transition faq-button" data-faq-index="<?= $index ?>">
                    <h2 class="text-lg font-semibold text-gray-900"><?= e($faq['question']) ?></h2>
                    <svg class="w-5 h-5 text-gray-500 transform transition-transform faq-icon" data-faq-index="<?= $index ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div class="px-6 pb-4 hidden faq-content" data-faq-index="<?= $index ?>">
                    <p class="text-gray-700"><?= e($faq['answer']) ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script>
function toggleFaq(index) {
    const content = document.querySelector(`.faq-content[data-faq-index="${index}"]`);
    const icon = document.querySelector(`.faq-icon[data-faq-index="${index}"]`);
    
    if (content.classList.contains('hidden')) {
        // Close all other FAQs
        document.querySelectorAll('.faq-content').forEach(el => el.classList.add('hidden'));
        document.querySelectorAll('.faq-icon').forEach(el => el.classList.remove('rotate-180'));
        
        // Open this one
        content.classList.remove('hidden');
        icon.classList.add('rotate-180');
    } else {
        content.classList.add('hidden');
        icon.classList.remove('rotate-180');
    }
}
</script>


