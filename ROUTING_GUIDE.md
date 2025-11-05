# Routing System Guide

The application now uses a named routing system that makes URL generation easier and more maintainable.

## Features

- **Named Routes**: All routes have unique names for easy reference
- **URL Generation**: Generate URLs using route names instead of hardcoded paths
- **Active Route Detection**: Automatically detect which route is currently active
- **Parameter Support**: Pass parameters to routes dynamically

## Usage

### Generating URLs

Use the `route()` helper function to generate URLs:

```php
// Simple route
route('home')  // Returns: /

// Route with parameters
route('product.show', ['slug' => 'my-product'])  // Returns: /produit/my-product
route('category.show', ['slug' => 'livres'])     // Returns: /categorie/livres
route('account.order.detail', ['id' => '123'])   // Returns: /compte/commandes/123
```

### Checking Active Routes

```php
// Check if current route matches a name
isRouteName('products.index')  // Returns true/false

// Check if current route matches any of the given names
isAnyRouteName(['products.index', 'product.show'])  // Returns true/false

// Get route parameter from current URL
Navigation::getRouteParam('slug')  // Returns the slug parameter value
```

### Common Routes

#### Public Routes
- `home` - Homepage
- `products.index` - Products listing
- `product.show` - Product detail (requires `slug` parameter)
- `category.show` - Category page (requires `slug` parameter)
- `search.index` - Search results
- `cart.index` - Shopping cart
- `checkout.index` - Checkout page
- `checkout.success` - Order success page

#### Authentication Routes
- `auth.login` - Login page
- `auth.register` - Registration page
- `auth.logout` - Logout (POST)

#### Account Routes
- `account.index` - Account dashboard
- `account.profile` - Profile page
- `account.orders` - Order history
- `account.order.detail` - Order detail (requires `id` parameter)
- `account.addresses` - Addresses page
- `account.wishlist` - Wishlist page

#### Admin Routes
- `admin.dashboard` - Admin dashboard
- `admin.products` - Products management
- `admin.product.create` - Create product
- `admin.product.edit` - Edit product (requires `id` parameter)
- `admin.categories` - Categories management
- `admin.orders` - Orders management
- `admin.users` - Users management

#### Info Pages
- `page.about` - About page
- `page.contact` - Contact page
- `page.faq` - FAQ page
- `page.shipping` - Shipping info
- `page.returns` - Returns policy
- `page.terms` - Terms and conditions
- `page.legal` - Legal notice
- `page.privacy` - Privacy policy

## Examples

### In Views

```php
<!-- Link to product -->
<a href="<?= route('product.show', ['slug' => $product['slug']]) ?>">
    <?= e($product['name']) ?>
</a>

<!-- Link to category -->
<a href="<?= route('category.show', ['slug' => $category['slug']]) ?>">
    <?= e($category['name']) ?>
</a>

<!-- Active navigation item -->
<a href="<?= route('products.index') ?>" 
   class="<?= isRouteName('products.index') ? 'active' : '' ?>">
    Products
</a>

<!-- Form action -->
<form action="<?= route('cart.add') ?>" method="POST">
    <!-- form fields -->
</form>
```

### Navigation with Active State

```php
<?php foreach ($categories as $category): 
    $isActive = isRouteName('category.show') && 
                Navigation::getRouteParam('slug') === $category['slug'];
?>
    <a href="<?= route('category.show', ['slug' => $category['slug']]) ?>"
       class="<?= $isActive ? 'text-primary font-medium' : '' ?>">
        <?= e($category['name']) ?>
    </a>
<?php endforeach; ?>
```

## Migration from baseUrl()

Replace hardcoded URLs with route helpers:

```php
// Old way
baseUrl('produit/' . $product['slug'])
baseUrl('compte/commandes')
baseUrl('admin/produits')

// New way
route('product.show', ['slug' => $product['slug']])
route('account.orders')
route('admin.products')
```

## Benefits

1. **Maintainability**: Change a route path in one place (`routes.php`) and all references update automatically
2. **Type Safety**: IDE autocomplete can help with route names
3. **Documentation**: Route names serve as documentation of available routes
4. **Flexibility**: Easy to change URL structure without breaking links
5. **Active State**: Built-in support for detecting active routes in navigation

