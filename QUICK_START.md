# Quick Start Guide - Sultan Library PHP

## Installation Steps

### 1. Database Setup

1. Create a MySQL/MariaDB database on IONOS
2. Import the schema:
   ```bash
   mysql -u your_user -p your_database < database/schema.sql
   ```
3. Run the seed script:
   ```bash
   php database/seed.php
   ```

### 2. Configuration

Edit `src/config/config.php`:
- Update `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS` with your database credentials
- Update `ADMIN_EMAIL` if needed (default: test@sultanlibrary.com)

### 3. File Permissions

```bash
chmod 755 public/uploads
chmod 644 public/.htaccess
```

### 4. Deploy to IONOS

1. Upload entire `php-app/` folder via FTP
2. Set document root to `php-app/public`
3. Ensure PHP 8.1+ is enabled
4. Ensure mod_rewrite is enabled

### 5. Test

- Visit your domain
- Login with: test@sultanlibrary.com / password123
- Access admin at: /admin (requires admin email login)

## Default Credentials

- **Email:** test@sultanlibrary.com
- **Password:** password123

⚠️ **Change these immediately after deployment!**

## Troubleshooting

- **404 errors:** Check `.htaccess` exists and mod_rewrite is enabled
- **Database errors:** Verify credentials in `config.php`
- **Images not loading:** Check image paths in `public/images/`
- **Session issues:** Ensure session directory is writable


