# XAMPP Setup Instructions

## Quick Setup Steps

### 1. Enable mod_rewrite in XAMPP

1. Open `C:\xampp\apache\conf\httpd.conf`
2. Find line ~169 and uncomment:
   ```apache
   LoadModule rewrite_module modules/mod_rewrite.so
   ```
3. Find the `<Directory>` section for htdocs (~line 244) and change:
   ```apache
   <Directory "C:/xampp/htdocs">
       Options Indexes FollowSymLinks
       AllowOverride All    <!-- Change from None to All -->
       Require all granted
   </Directory>
   ```
4. Restart Apache in XAMPP Control Panel

### 2. Copy Project to XAMPP

Copy your entire `php-app` folder to:
```
C:\xampp\htdocs\php-app\
```

### 3. Test Routes

1. Visit: `http://localhost/php-app/public/test-routes.php`
   - This will show all routes and test if routing is working
   
2. Test main routes:
   - Home: `http://localhost/php-app/public/`
   - Products: `http://localhost/php-app/public/produits`
   - Login: `http://localhost/php-app/public/auth/login`

### 4. Verify Configuration

The `.htaccess` file has been updated with:
```
RewriteBase /php-app/public/
```

The `config.php` already has:
```php
define('BASE_URL', '/php-app/public/');
```

### 5. Common Issues

**404 Errors:**
- Check mod_rewrite is enabled
- Verify `.htaccess` exists in `public/` folder
- Ensure Apache was restarted

**Routes don't work:**
- Check `test-routes.php` output
- Verify `BASE_URL` matches your setup
- Check Apache error logs: `C:\xampp\apache\logs\error.log`

**Images/CSS not loading:**
- Check browser console for 404 errors
- Verify files exist in `public/images/` and `public/css/`

## Testing Checklist

- [ ] mod_rewrite enabled
- [ ] AllowOverride All set
- [ ] Apache restarted
- [ ] Project copied to htdocs
- [ ] test-routes.php accessible
- [ ] Homepage loads
- [ ] Navigation links work
- [ ] Forms submit correctly

