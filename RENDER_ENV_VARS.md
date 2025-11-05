# Render Environment Variables Configuration

Quick reference for setting environment variables in Render dashboard.

## Required Variables (Set These)

### Application
- **APP_ENV** = `production`
- **APP_DEBUG** = `false`
- **BASE_URL** = Click "Generate" or leave empty (auto-detected)
- **TIMEZONE** = `Europe/Paris`

### Database (Get from your MySQL/PostgreSQL database dashboard)
- **DB_HOST** = (from database connection string, e.g., `dpg-xxxxx-a.oregon-postgres.render.com`)
- **DB_NAME** = (from database connection string, e.g., `sultan_library`)
- **DB_USER** = (from database connection string, e.g., `sultan_user`)
- **DB_PASS** = (from database connection string - copy the password)

## Optional Variables (Can Use Defaults)

These have defaults in your code, but you can set them if you want to customize:

- **ASSETS_URL** = Leave empty (auto-generated from BASE_URL)
- **UPLOADS_URL** = Leave empty (auto-generated from BASE_URL)
- **SESSION_LIFETIME** = `2592000` (30 days in seconds)
- **SESSION_NAME** = `SULTAN_SESSION`
- **ADMIN_EMAIL** = `test@sultanlibrary.com` (change this!)
- **CSRF_TOKEN_NAME** = `csrf_token`
- **UPLOAD_MAX_SIZE** = `5242880` (5MB in bytes)
- **FREE_SHIPPING_THRESHOLD** = `100` (free shipping above €100)
- **DEFAULT_SHIPPING_COST** = `9.99`
- **PRODUCTS_PER_PAGE** = `16`

## Quick Setup Steps

1. **Set Required Variables:**
   ```
   APP_ENV = production
   APP_DEBUG = false
   TIMEZONE = Europe/Paris
   ```

2. **Get Database Variables:**
   - Go to your database dashboard
   - Copy connection details from "Connections" or "Info" tab
   - Add: DB_HOST, DB_NAME, DB_USER, DB_PASS

3. **Generate BASE_URL:**
   - Click "Generate" button next to BASE_URL
   - Or leave empty for auto-detection

4. **Leave Optional Variables Empty:**
   - ASSETS_URL, UPLOADS_URL, and others can be left empty
   - They'll use defaults from your code

## Important Notes

- ⚠️ **ADMIN_EMAIL**: Change `test@sultanlibrary.com` to your actual admin email!
- ⚠️ **DB_PASS**: Make sure to copy the password exactly from database dashboard
- ✅ **BASE_URL**: Can be auto-generated or left empty
- ✅ Variables marked with "Generate" can be auto-filled by Render

