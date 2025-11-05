/**
 * Main JavaScript file
 * Handles cart interactions, form submissions, and UI enhancements
 */

// Cart functionality
document.addEventListener('DOMContentLoaded', function() {
    // Initialize hero carousel
    initHeroCarousel();
    
    // Initialize cart drawer
    initCartDrawer();
    
    // Initialize quantity selectors
    initQuantitySelectors();
    
    // Initialize image galleries
    initImageGalleries();
    
    // Initialize modals
    initModals();
    
    // Handle add to cart forms
    const addToCartForms = document.querySelectorAll('form[data-action="add-to-cart"]');
    addToCartForms.forEach(form => {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(form);
            const button = form.querySelector('button[type="submit"]');
            const originalText = button ? button.textContent : '';
            
            if (button) {
                button.disabled = true;
                button.textContent = 'Ajout...';
            }
            
            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData
                });
                
                const data = await response.json();
                
                if (data.success) {
                    // Update cart badge
                    updateCartBadge(data.cartQuantity || 0);
                    
                    // Show mini cart toast
                    showMiniCartToast(data.productName || 'Produit');
                    
                    // Optionally open cart drawer
                    openCartDrawer();
                } else {
                    showToast(data.error || 'Erreur lors de l\'ajout au panier', 'error');
                }
            } catch (error) {
                showToast('Erreur de connexion', 'error');
            } finally {
                if (button) {
                    button.disabled = false;
                    button.textContent = originalText;
                }
            }
        });
    });
    
    // Handle wishlist forms (remove)
    const removeWishlistForms = document.querySelectorAll('form[data-action="remove-wishlist"]');
    removeWishlistForms.forEach(form => {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(form);
            
            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData
                });
                
                const data = await response.json();
                
                if (data.success) {
                    showToast('Produit retiré des favoris', 'success');
                    const container = form.closest('.bg-white');
                    if (container) {
                        container.style.transition = 'opacity 0.3s';
                        container.style.opacity = '0';
                        setTimeout(() => {
                            container.remove();
                        }, 300);
                    } else {
                        // On product detail page, reload to update button
                        location.reload();
                    }
                } else {
                    showToast(data.error || 'Erreur', 'error');
                }
            } catch (error) {
                showToast('Erreur de connexion', 'error');
            }
        });
    });
    
    // Handle wishlist forms (add)
    const addWishlistForms = document.querySelectorAll('form[data-action="add-wishlist"]');
    addWishlistForms.forEach(form => {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(form);
            
            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData
                });
                
                const data = await response.json();
                
                if (data.success) {
                    showToast('Produit ajouté aux favoris', 'success');
                    location.reload();
                } else {
                    showToast(data.error || 'Erreur', 'error');
                }
            } catch (error) {
                showToast('Erreur de connexion', 'error');
            }
        });
    });
    
    // Update cart quantity on page load
    updateCartBadge();
});

// Update cart badge
function updateCartBadge(quantity) {
    const cartBadge = document.querySelector('.cart-badge') || 
                      document.querySelector('a[href*="panier"] span');
    if (cartBadge) {
        if (quantity > 0) {
            cartBadge.textContent = quantity;
            cartBadge.style.display = 'block';
        } else {
            cartBadge.style.display = 'none';
        }
    }
}

// Toast notification system
function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 ${
        type === 'success' ? 'bg-green-500' :
        type === 'error' ? 'bg-red-500' :
        'bg-blue-500'
    } text-white`;
    toast.textContent = message;
    toast.style.opacity = '0';
    toast.style.transition = 'opacity 0.3s';
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.style.opacity = '1';
    }, 10);
    
    setTimeout(() => {
        toast.style.opacity = '0';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// Mobile menu toggle (if needed)
function toggleMobileMenu() {
    const menu = document.getElementById('mobile-menu');
    if (menu) {
        menu.classList.toggle('hidden');
    }
}

// Form validation
document.querySelectorAll('form').forEach(form => {
    form.addEventListener('submit', function(e) {
        const requiredFields = form.querySelectorAll('[required]');
        let isValid = true;
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                isValid = false;
                field.classList.add('border-red-500');
            } else {
                field.classList.remove('border-red-500');
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            showToast('Veuillez remplir tous les champs obligatoires', 'error');
        }
    });
});

// Cart Drawer
function initCartDrawer() {
    // Create cart drawer HTML if it doesn't exist
    if (!document.getElementById('cart-drawer')) {
        const drawer = document.createElement('div');
        drawer.id = 'cart-drawer';
        drawer.className = 'fixed inset-y-0 right-0 w-full max-w-md bg-white shadow-xl z-50 transform translate-x-full transition-transform duration-300 ease-in-out';
        drawer.innerHTML = `
            <div class="flex flex-col h-full">
                <div class="flex items-center justify-between p-6 border-b">
                    <h2 class="text-xl font-bold">Panier</h2>
                    <button onclick="closeCartDrawer()" class="text-gray-500 hover:text-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div id="cart-drawer-content" class="flex-1 overflow-y-auto p-6">
                    <p class="text-gray-500 text-center py-8">Votre panier est vide</p>
                </div>
                <div class="p-6 border-t">
                    <div class="flex justify-between mb-4">
                        <span class="font-semibold">Total:</span>
                        <span id="cart-drawer-total" class="font-bold text-primary">0,00 €</span>
                    </div>
                    <a href="${baseUrl('commander')}" class="block w-full px-6 py-3 bg-primary text-white text-center rounded-lg hover:opacity-90 transition">
                        Commander
                    </a>
                </div>
            </div>
        `;
        document.body.appendChild(drawer);
        
        // Add overlay
        const overlay = document.createElement('div');
        overlay.id = 'cart-drawer-overlay';
        overlay.className = 'fixed inset-0 bg-black bg-opacity-50 z-40 hidden';
        overlay.onclick = closeCartDrawer;
        document.body.appendChild(overlay);
    }
}

function openCartDrawer() {
    const drawer = document.getElementById('cart-drawer');
    const overlay = document.getElementById('cart-drawer-overlay');
    if (drawer) {
        drawer.classList.remove('translate-x-full');
        if (overlay) overlay.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        loadCartDrawerContent();
    }
}

function closeCartDrawer() {
    const drawer = document.getElementById('cart-drawer');
    const overlay = document.getElementById('cart-drawer-overlay');
    if (drawer) {
        drawer.classList.add('translate-x-full');
        if (overlay) overlay.classList.add('hidden');
        document.body.style.overflow = '';
    }
}

function loadCartDrawerContent() {
    fetch(baseUrl('panier') + '?ajax=1')
        .then(response => response.json())
        .then(data => {
            const content = document.getElementById('cart-drawer-content');
            const total = document.getElementById('cart-drawer-total');
            
            if (data.items && data.items.length > 0) {
                content.innerHTML = data.items.map(item => `
                    <div class="flex gap-4 mb-4 pb-4 border-b">
                        <img src="${item.image}" alt="${item.name}" class="w-20 h-20 object-cover rounded">
                        <div class="flex-1">
                            <h3 class="font-semibold">${item.name}</h3>
                            <p class="text-sm text-gray-600">${item.price} € x ${item.qty}</p>
                        </div>
                    </div>
                `).join('');
                if (total) total.textContent = data.total + ' €';
            } else {
                content.innerHTML = '<p class="text-gray-500 text-center py-8">Votre panier est vide</p>';
                if (total) total.textContent = '0,00 €';
            }
        })
        .catch(() => {
            const content = document.getElementById('cart-drawer-content');
            if (content) {
                content.innerHTML = '<p class="text-gray-500 text-center py-8">Erreur de chargement</p>';
            }
        });
}

// Mini Cart Toast
function showMiniCartToast(productName) {
    const toast = document.createElement('div');
    toast.className = 'fixed bottom-4 right-4 bg-white rounded-lg shadow-xl p-4 z-50 max-w-sm transform translate-y-0 opacity-0 transition-all duration-300';
    toast.innerHTML = `
        <div class="flex items-center gap-3">
            <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <div class="flex-1">
                <p class="font-semibold text-gray-900">Produit ajouté</p>
                <p class="text-sm text-gray-600">${productName}</p>
            </div>
            <button onclick="this.parentElement.parentElement.remove()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    `;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.classList.remove('opacity-0', 'translate-y-0');
    }, 10);
    
    setTimeout(() => {
        toast.classList.add('opacity-0', 'translate-y-4');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// Quantity Selectors
function initQuantitySelectors() {
    document.querySelectorAll('.quantity-selector').forEach(selector => {
        const input = selector.querySelector('input[type="number"]');
        const decreaseBtn = selector.querySelector('[data-action="decrease"]');
        const increaseBtn = selector.querySelector('[data-action="increase"]');
        
        if (decreaseBtn) {
            decreaseBtn.addEventListener('click', () => {
                const currentValue = parseInt(input.value) || 1;
                if (currentValue > parseInt(input.min || 1)) {
                    input.value = currentValue - 1;
                    input.dispatchEvent(new Event('change'));
                }
            });
        }
        
        if (increaseBtn) {
            increaseBtn.addEventListener('click', () => {
                const currentValue = parseInt(input.value) || 1;
                const maxValue = parseInt(input.max || 999);
                if (currentValue < maxValue) {
                    input.value = currentValue + 1;
                    input.dispatchEvent(new Event('change'));
                }
            });
        }
    });
}

// Image Galleries
function initImageGalleries() {
    // Handle thumbnail clicks
    document.querySelectorAll('.product-gallery-thumbnail').forEach(thumb => {
        thumb.addEventListener('click', function() {
            const mainImage = document.getElementById('main-image');
            if (mainImage) {
                mainImage.src = this.src;
                // Update active thumbnail
                document.querySelectorAll('.product-gallery-thumbnail').forEach(t => {
                    t.classList.remove('border-primary');
                    t.classList.add('border-transparent');
                });
                this.classList.remove('border-transparent');
                this.classList.add('border-primary');
            }
        });
    });
    
    // Handle product detail image clicks for lightbox
    const mainImage = document.getElementById('main-image');
    if (mainImage) {
        mainImage.addEventListener('click', function() {
            openImageModal(this.src, this.alt);
        });
    }
}

// Modals
function initModals() {
    // Create modal container if it doesn't exist
    if (!document.getElementById('modal-container')) {
        const container = document.createElement('div');
        container.id = 'modal-container';
        container.className = 'fixed inset-0 z-50 hidden items-center justify-center';
        container.innerHTML = `
            <div class="fixed inset-0 bg-black bg-opacity-50" onclick="closeModal()"></div>
            <div class="relative bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
                <button onclick="closeModal()" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 z-10">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
                <div id="modal-content" class="p-6"></div>
            </div>
        `;
        document.body.appendChild(container);
    }
}

function openModal(content) {
    const container = document.getElementById('modal-container');
    const modalContent = document.getElementById('modal-content');
    if (container && modalContent) {
        modalContent.innerHTML = content;
        container.classList.remove('hidden');
        container.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }
}

function closeModal() {
    const container = document.getElementById('modal-container');
    if (container) {
        container.classList.add('hidden');
        container.classList.remove('flex');
        document.body.style.overflow = '';
    }
}

function openImageModal(src, alt) {
    openModal(`<img src="${src}" alt="${alt}" class="w-full h-auto">`);
}

// Helper function for baseUrl (if not defined globally)
function baseUrl(path) {
    const base = '/php-app/public/';
    return base + path.replace(/^\//, '');
}

// Hero Carousel functionality
function initHeroCarousel() {
    const carousel = document.getElementById('heroCarousel');
    if (!carousel) return;
    
    const track = carousel.querySelector('.carousel-track');
    const dots = carousel.querySelectorAll('.carousel-dot');
    const slides = carousel.querySelectorAll('.carousel-slide');
    const titleEl = document.getElementById('heroTitle');
    const descEl = document.getElementById('heroDesc');
    const btnEl = document.getElementById('heroBtn');
    const prevBtn = document.getElementById('carouselPrev');
    const nextBtn = document.getElementById('carouselNext');
    
    if (!track || slides.length === 0) return;
    
    let currentSlide = 0;
    const totalSlides = slides.length || dots.length;

    // Ensure track/slides have correct widths for translation
    Array.from(slides).forEach(slide => {
        slide.style.minWidth = '100%';
        slide.style.flexShrink = '0';
    });
    // Force a reflow before first transform to avoid sticky first translate
    void track.offsetWidth;
    let autoPlayInterval;
    let isPaused = false;
    
    // Function to update carousel position
    function updateCarousel() {
        const translateX = currentSlide * -100;
        track.style.transform = `translateX(${translateX}%)`;
        
        // Update dot indicators if present
        if (dots && dots.length) {
            dots.forEach((dot, index) => {
                if (index === currentSlide) {
                    dot.classList.remove('opacity-50');
                    dot.classList.add('opacity-100');
                } else {
                    dot.classList.remove('opacity-100');
                    dot.classList.add('opacity-50');
                }
            });
        }

        // Update overlay content from the active slide's data-*
        if (slides && slides[currentSlide]) {
            const active = slides[currentSlide];
            const t = active.getAttribute('data-title');
            const d = active.getAttribute('data-desc');
            const u = active.getAttribute('data-url');
            if (titleEl && t !== null) titleEl.textContent = t;
            if (descEl && d !== null) descEl.textContent = d;
            if (btnEl && u) btnEl.setAttribute('href', u);
        }
    }
    
    // Function to go to next slide
    function nextSlide() {
        currentSlide = (currentSlide + 1) % totalSlides;
        updateCarousel();
    }
    
    // Function to go to previous slide
    function prevSlide() {
        currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
        updateCarousel();
    }
    
    // Go to specific slide
    function goToSlide(index) {
        currentSlide = index;
        updateCarousel();
    }
    
    // Auto-play functionality
    function startAutoPlay() {
        stopAutoPlay(); // Clear any existing interval
        autoPlayInterval = setInterval(() => {
            if (!isPaused) {
                nextSlide();
            }
        }, 5000); // Change slide every 5 seconds
    }
    
    function stopAutoPlay() {
        if (autoPlayInterval) {
            clearInterval(autoPlayInterval);
            autoPlayInterval = null;
        }
    }
    
    // Event listeners
    if (prevBtn) {
        prevBtn.style.pointerEvents = 'auto';
        prevBtn.addEventListener('click', () => {
            prevSlide();
            stopAutoPlay();
            startAutoPlay(); // Restart auto-play after manual navigation
        });
    }
    
    if (nextBtn) {
        nextBtn.style.pointerEvents = 'auto';
        nextBtn.addEventListener('click', () => {
            nextSlide();
            stopAutoPlay();
            startAutoPlay(); // Restart auto-play after manual navigation
        });
    }
    
    // Dot navigation
    dots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
            goToSlide(index);
            stopAutoPlay();
            startAutoPlay(); // Restart auto-play after manual navigation
        });
    });
    
    // Pause on hover
    carousel.addEventListener('mouseenter', () => {
        isPaused = true;
    });
    
    carousel.addEventListener('mouseleave', () => {
        isPaused = false;
    });
    
    // Touch swipe support
    let startX = 0; let deltaX = 0; let touching = false;
    carousel.addEventListener('touchstart', (e) => {
        touching = true;
        startX = e.touches[0].clientX;
        stopAutoPlay();
    }, { passive: true });
    carousel.addEventListener('touchmove', (e) => {
        if (!touching) return;
        deltaX = e.touches[0].clientX - startX;
    }, { passive: true });
    carousel.addEventListener('touchend', () => {
        if (!touching) return;
        if (Math.abs(deltaX) > 40) {
            if (deltaX < 0) { nextSlide(); } else { prevSlide(); }
        }
        touching = false; deltaX = 0; startAutoPlay();
    });

    // Hide controls if only one slide
    if (totalSlides <= 1) {
        if (prevBtn) prevBtn.style.display = 'none';
        if (nextBtn) nextBtn.style.display = 'none';
    }

    // Start auto-play
    startAutoPlay();
    // Initial sync
    updateCarousel();
}


