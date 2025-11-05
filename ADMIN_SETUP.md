# How to Make Another Account Admin

There are two methods to make an account an admin:

## Method 1: Change ADMIN_EMAIL (Quick Method)

**Steps:**
1. Find the email of the user you want to make admin
2. Open `php-app/src/config/config.php`
3. Change line 35:
   ```php
   define('ADMIN_EMAIL', 'newadmin@example.com'); // Change to the new admin's email
   ```
4. Save the file
5. The user with that email will now have admin access

**Limitations:**
- Only one admin email at a time
- Not scalable for multiple admins

## Method 2: Use Role Field (Recommended - Better Method)

The database has a `role` field that can be set to `'admin'`. Here's how to update it:

### Option A: Using SQL Query

1. **Connect to your database** (phpMyAdmin, MySQL Workbench, or command line)

2. **Run this SQL query:**
   ```sql
   UPDATE users 
   SET role = 'admin' 
   WHERE email = 'newadmin@example.com';
   ```

3. **Verify it worked:**
   ```sql
   SELECT email, role FROM users WHERE email = 'newadmin@example.com';
   ```

### Option B: Using Admin Panel (if implemented)

1. Log in as current admin
2. Go to `/admin/utilisateurs`
3. Find the user and change their role dropdown to "admin"

### Update Auth::isAdmin() to Use Role

After updating the role in database, update the `isAdmin()` method to check role instead of email:

**File:** `php-app/src/lib/Auth.php`

**Change from:**
```php
public static function isAdmin(): bool {
    if (!self::check()) {
        return false;
    }

    $email = $_SESSION['user_email'] ?? '';
    return strtolower($email) === strtolower(ADMIN_EMAIL);
}
```

**Change to:**
```php
public static function isAdmin(): bool {
    if (!self::check()) {
        return false;
    }

    // Check role from session (updated on login)
    $role = $_SESSION['user_role'] ?? '';
    if ($role === 'admin') {
        return true;
    }
    
    // Fallback: check email for backward compatibility
    $email = $_SESSION['user_email'] ?? '';
    return strtolower($email) === strtolower(ADMIN_EMAIL);
}
```

## Quick SQL Commands

**Make a user admin:**
```sql
UPDATE users SET role = 'admin' WHERE email = 'user@example.com';
```

**Make multiple users admin:**
```sql
UPDATE users SET role = 'admin' WHERE email IN ('admin1@example.com', 'admin2@example.com');
```

**Remove admin status:**
```sql
UPDATE users SET role = 'user' WHERE email = 'user@example.com';
```

**List all admins:**
```sql
SELECT id, email, name, role FROM users WHERE role = 'admin';
```

## Important Notes

- After changing a user's role, they need to **log out and log back in** for the change to take effect
- The session stores the role when the user logs in
- Make sure the user exists in the database before updating
- Always verify admin access after making changes

