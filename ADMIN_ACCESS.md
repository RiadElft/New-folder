# Admin Dashboard Access Guide

## How to Access Admin Dashboard

### Default Admin Credentials

**Email:** `test@sultanlibrary.com`  
**Password:** `password123`

⚠️ **Important:** Change these credentials immediately after setup!

### Access Steps

1. **Navigate to login page:**
   ```
   http://localhost/php-app/public/auth/login
   ```

2. **Login with admin credentials:**
   - Email: `test@sultanlibrary.com`
   - Password: `password123`

3. **Automatic Redirect:**
   - After successful login, admins are **automatically redirected** to the admin dashboard
   - URL: `http://localhost/php-app/public/admin`

### Admin Dashboard Features

Once logged in as admin, you can access:

- **Dashboard:** `http://localhost/php-app/public/admin`
- **Products Management:** `http://localhost/php-app/public/admin/produits`
- **Categories Management:** `http://localhost/php-app/public/admin/categories`
- **Orders Management:** `http://localhost/php-app/public/admin/commandes`
- **Users Management:** `http://localhost/php-app/public/admin/utilisateurs`

### How Admin Detection Works

The system identifies admins by checking if their email matches the `ADMIN_EMAIL` configured in `src/config/config.php`:

```php
define('ADMIN_EMAIL', 'test@sultanlibrary.com');
```

When a user with this email logs in:
- They are automatically redirected to `/admin` dashboard
- They have access to all admin routes
- Regular users are redirected to `/compte` (account page)

### Admin Routes Protection

All admin routes are protected by `Auth::requireAdmin()` which:
1. Checks if user is logged in
2. Verifies the user's email matches `ADMIN_EMAIL`
3. Redirects non-admins to the home page

### Creating Additional Admins

To create additional admin users, you have two options:

**Option 1: Update ADMIN_EMAIL in config.php**
- Change `ADMIN_EMAIL` to the new admin's email
- Multiple admins can share the same email check (not recommended)

**Option 2: Modify Auth::isAdmin() method**
- Update `src/lib/Auth.php` to check user role or a list of admin emails
- Example: Check if `$user['role'] === 'admin'` instead of email match

### Troubleshooting

**Can't access admin dashboard:**
- Verify you're logged in with the correct email (`test@sultanlibrary.com`)
- Check that `ADMIN_EMAIL` in `config.php` matches your email exactly (case-insensitive)
- Clear browser cookies and try again

**Redirected to home page:**
- You're not recognized as admin
- Check your email matches `ADMIN_EMAIL` exactly
- Verify the user exists in the database with `active = 1`

**Session issues:**
- Clear browser cookies
- Check PHP session directory is writable
- Verify `SESSION_NAME` in config matches your setup

