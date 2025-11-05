-- SQL Script to Make a User Admin
-- Replace 'user@example.com' with the actual email address

-- Make a single user admin
UPDATE users 
SET role = 'admin' 
WHERE email = 'user@example.com';

-- Verify the change
SELECT id, email, name, role, active 
FROM users 
WHERE email = 'user@example.com';

-- List all admins
SELECT id, email, name, role, createdAt 
FROM users 
WHERE role = 'admin';

-- Remove admin status (set back to user)
-- UPDATE users SET role = 'user' WHERE email = 'user@example.com';

