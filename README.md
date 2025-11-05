# Sultan Library - PHP Migration

Complete PHP migration of the Next.js e-commerce application, ready for IONOS deployment.

## Setup Instructions

### 1. Database Setup

1. Create a MySQL/MariaDB database on IONOS
2. Import the schema:
   ```sql
   mysql -u your_user -p your_database < database/schema.sql
   ```
3. Run the seed script to populate data:
   ```bash
   php database/seed.php
   ```

### 2. Configuration

Edit `src/config/config.php` with your database credentials:
- `DB_HOST` - Database host (usually `localhost`)
- `DB_NAME` - Database name
- `DB_USER` - Database username
- `DB_PASS` - Database password

### 3. Deploy to IONOS

1. Upload the entire `php-app/` folder to your hosting
2. Set the document root to `php-app/public`
3. Ensure PHP 8.1+ is enabled
4. Ensure mod_rewrite is enabled (for clean URLs)
5. Set folder permissions:
   - `public/uploads/` - writable (755 or 775)
   - Session directory - writable

### 4. File Permissions

```bash
chmod 755 public/uploads
chmod 644 public/.htaccess
```

## File Structure

```
php-app/
├── public/              # Web root (document root)
│   ├── index.php       # Front controller
│   ├── .htaccess       # URL rewriting
│   ├── images/         # Product images
│   └── js/             # JavaScript files
├── src/
│   ├── config/         # Configuration
│   ├── controllers/    # Route handlers
│   ├── models/          # Data models
│   ├── lib/            # Libraries (Auth, CSRF, Helpers)
│   └── views/          # PHP templates
└── database/           # SQL files
```

## Default Credentials

- **Email:** test@sultanlibrary.com
- **Password:** password123

⚠️ **Change these after deployment!**

## Features Implemented

- ✅ Database schema (MySQL/MariaDB)
- ✅ Authentication system (PHP sessions)
- ✅ Shopping cart (session-based)
- ✅ Product browsing
- ✅ Category navigation
- ✅ Layout components (Header, Footer)
- ✅ Routing system
- ✅ CSRF protection

## URL Structure

- `/` - Home page
- `/produits` - Product listing
- `/produit/{slug}` - Product detail
- `/categorie/{slug}` - Category page
- `/panier` - Shopping cart
- `/commander` - Checkout
- `/auth/login` - Login
- `/auth/register` - Registration
- `/compte` - Account dashboard
- `/admin` - Admin panel

## Tech Stack

- PHP 8.1+
- MySQL/MariaDB
- Apache with mod_rewrite
- Tailwind CSS (CDN)
- Vanilla JavaScript

## Development

For local development with XAMPP/MAMP:

1. Place `php-app/` in `htdocs/` or `www/`
2. Configure database in `src/config/config.php`
3. Access via `http://localhost/php-app/public/`

## Notes

- Images need to be copied from the Next.js `public/` folder to `php-app/public/images/`
- All product images should be in `public/images/`
- Logo should be at `public/images/logo.png`


