<?php
/**
 * Route Definitions
 * Define all application routes here with names for navigation
 */

// Home
$router->get('/', 'HomeController@index', 'home');

// Products
$router->get('/produits', 'ProductController@index', 'products.index');
$router->get('/produit/{slug}', 'ProductController@show', 'product.show');

// Categories
$router->get('/categorie/{slug}', 'CategoryController@show', 'category.show');

// Search
$router->get('/recherche', 'SearchController@index', 'search.index');
$router->get('/api/search/autocomplete', 'SearchController@autocomplete', 'api.search.autocomplete');

// Cart
$router->get('/panier', 'CartController@index', 'cart.index');
$router->post('/panier/add', 'CartController@add', 'cart.add');
$router->post('/panier/update', 'CartController@update', 'cart.update');
$router->post('/panier/remove', 'CartController@remove', 'cart.remove');
$router->post('/panier/clear', 'CartController@clear', 'cart.clear');

// Checkout
$router->get('/commander', 'CheckoutController@index', 'checkout.index');
$router->post('/commander', 'CheckoutController@process', 'checkout.process');
$router->get('/commande/succes', 'CheckoutController@success', 'checkout.success');

// Authentication
$router->get('/auth/login', 'AuthController@showLogin', 'auth.login');
$router->post('/auth/login', 'AuthController@login', 'auth.login.post');
$router->get('/auth/register', 'AuthController@showRegister', 'auth.register');
$router->post('/auth/register', 'AuthController@register', 'auth.register.post');
$router->post('/auth/logout', 'AuthController@logout', 'auth.logout');

// Account (protected)
$router->get('/compte', 'AccountController@index', 'account.index');
$router->get('/compte/profil', 'AccountController@profile', 'account.profile');
$router->post('/compte/profil', 'AccountController@updateProfile', 'account.profile.update');
$router->get('/compte/commandes', 'AccountController@orders', 'account.orders');
$router->get('/compte/commandes/{id}', 'AccountController@orderDetail', 'account.order.detail');
$router->get('/compte/adresses', 'AccountController@addresses', 'account.addresses');
$router->post('/compte/adresses', 'AccountController@addAddress', 'account.address.add');
$router->post('/compte/adresses/{id}/delete', 'AccountController@deleteAddress', 'account.address.delete');
$router->get('/compte/favoris', 'AccountController@wishlist', 'account.wishlist');
$router->post('/compte/favoris/add', 'AccountController@addToWishlist', 'account.wishlist.add');
$router->post('/compte/favoris/remove', 'AccountController@removeFromWishlist', 'account.wishlist.remove');

// Admin (protected)
$router->get('/admin', 'AdminController@dashboard', 'admin.dashboard');
$router->get('/admin/produits', 'AdminController@products', 'admin.products');
$router->get('/admin/hero', 'AdminController@heroIndex', 'admin.hero');
$router->post('/admin/hero', 'AdminController@heroSave', 'admin.hero.save');
$router->get('/admin/produits/nouveau', 'AdminController@productCreate', 'admin.product.create');
$router->post('/admin/produits', 'AdminController@productStore', 'admin.product.store');
$router->get('/admin/produits/{id}', 'AdminController@productEdit', 'admin.product.edit');
$router->post('/admin/produits/{id}', 'AdminController@productUpdate', 'admin.product.update');
$router->post('/admin/produits/{id}/delete', 'AdminController@productDelete', 'admin.product.delete');
$router->get('/admin/categories', 'AdminController@categories', 'admin.categories');
$router->post('/admin/categories', 'AdminController@categoryStore', 'admin.category.store');
$router->post('/admin/categories/{id}/delete', 'AdminController@categoryDelete', 'admin.category.delete');
$router->get('/admin/commandes', 'AdminController@orders', 'admin.orders');
$router->get('/admin/commandes/{id}', 'AdminController@orderDetail', 'admin.order.detail');
$router->post('/admin/commandes/{id}/status', 'AdminController@orderUpdateStatus', 'admin.order.updateStatus');
$router->get('/admin/utilisateurs', 'AdminController@users', 'admin.users');
$router->post('/admin/utilisateurs/{id}', 'AdminController@userUpdate', 'admin.user.update');
$router->post('/admin/utilisateurs/{id}/delete', 'AdminController@userDelete', 'admin.user.delete');

// Info pages
$router->get('/a-propos', 'PageController@about', 'page.about');
$router->get('/contact', 'PageController@contact', 'page.contact');
$router->get('/faq', 'PageController@faq', 'page.faq');
$router->get('/livraison', 'PageController@shipping', 'page.shipping');
$router->get('/retours', 'PageController@returns', 'page.returns');
$router->get('/cgv', 'PageController@terms', 'page.terms');
$router->get('/mentions-legales', 'PageController@legal', 'page.legal');
$router->get('/confidentialite', 'PageController@privacy', 'page.privacy');

// Reviews
$router->post('/api/reviews', 'ReviewController@create', 'api.review.create');
$router->post('/api/reviews/{id}/delete', 'ReviewController@delete', 'api.review.delete');

// API endpoints (for AJAX)
$router->get('/api/categories', 'ApiController@categories', 'api.categories');
$router->get('/api/products', 'ApiController@products', 'api.products');
$router->get('/api/search', 'ApiController@search', 'api.search');


