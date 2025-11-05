# Quick Deployment Guide - Sultan Library

Deploy your PHP website so your client can see updates consistently (like Vercel).

## 🔍 Quick Reference: Finding Database Connection Details

**Where to find DB_HOST, DB_NAME, DB_USER, DB_PASS in Render:**

1. Go to your Render Dashboard
2. Click on your database service (e.g., `sultan-library-db`)
3. Look for one of these sections:
   - **"Connections"** tab → Shows Internal Database URL
   - **"Info"** tab → Shows connection details
   - **"Connect"** button → Shows connection string
4. The connection string format is:
   ```
   mysql://username:password@host:port/database
   ```
5. Extract each part:
   - `DB_HOST` = everything after `@` and before `:port`
   - `DB_PORT` = the number after the second `:` (usually `3306` for MySQL)
   - `DB_USER` = the part before the first `:`
   - `DB_PASS` = the part between `:` and `@`
   - `DB_NAME` = everything after the last `/`

**Example:**
```
mysql://sultan_user:abc123@dpg-abc123-a.oregon-postgres.render.com:3306/sultan_library
```
Becomes:
- `DB_HOST` = `dpg-abc123-a.oregon-postgres.render.com`
- `DB_PORT` = `3306`
- `DB_USER` = `sultan_user`
- `DB_PASS` = `abc123`
- `DB_NAME` = `sultan_library`

## 🚀 Option 1: Render (Recommended - Easiest)

**Why Render?**
- ✅ Free tier available
- ✅ Automatic Git deployments
- ✅ Every push = new deployment
- ✅ MySQL database included
- ✅ Preview deployments for PRs

### Steps:

1. **Push your code to GitHub**
   ```bash
   git add .
   git commit -m "Ready for deployment"
   git push origin main
   ```

2. **Sign up at [render.com](https://render.com)** (free account)

3. **Create New Web Service**
   - Click "New +" → "Web Service"
   - Connect your GitHub repository
   - Select your repository

4. **Configure the service:**
   - **Name:** `sultan-library`
   - **Environment:** Select `Docker` (if PHP not available, Docker will work)
   - **Build Command:** (leave empty - Render will use Dockerfile)
   - **Start Command:** (leave empty - Dockerfile handles this)
   - **Root Directory:** Leave this **empty** (or use `.` if required)
     - ✅ This means Render will use the repository root where your `Dockerfile` is located
     - ✅ Your `Dockerfile` is at the root, so this is correct
     - ❌ Don't set it to `public/` or any subdirectory
   
   **OR if you see PHP option:**
   - **Environment:** `PHP`
   - **Build Command:** (leave empty)
   - **Start Command:** `php -S 0.0.0.0:$PORT -t public`
   - **Root Directory:** Leave **empty** (same as above)

5. **Add Environment Variables:**
   - `APP_ENV` = `production`
   - `APP_DEBUG` = `false`
   - `BASE_URL` = (will be auto-set by Render)

6. **Create Database:**
   - Click "New +" → "MySQL" (or "PostgreSQL" if MySQL not available)
   - Choose "Free" plan
   - Name it: `sultan-library-db`
   - Click "Create Database"
   - Wait for it to be provisioned (usually 1-2 minutes)

7. **Get Database Connection Details:**
   
   Once your database is created, you'll see the database dashboard. Here's how to get the connection info:
   
   **Method 1: From Database Dashboard (Easiest)**
   - Click on your database name (`sultan-library-db`)
   - Look for the "Connections" section or "Internal Database URL"
   - You'll see connection details like:
     ```
     Host: dpg-xxxxx-a.oregon-postgres.render.com
     Port: 5432 (for PostgreSQL) or 3306 (for MySQL)
     Database: sultan_library
     User: sultan_user
     Password: (shown in the dashboard)
     ```
   
   **Method 2: From Connection String**
   - In the database dashboard, find "Connection String" or "Internal Database URL"
   - It will look like:
     - MySQL: `mysql://user:password@host:port/database`
     - PostgreSQL: `postgresql://user:password@host:port/database`
   - Parse it to extract:
     - `DB_HOST` = the host part (e.g., `dpg-xxxxx-a.oregon-postgres.render.com`)
     - `DB_PORT` = the port (usually `5432` for PostgreSQL, `3306` for MySQL)
     - `DB_NAME` = the database name (e.g., `sultan_library`)
     - `DB_USER` = the username
     - `DB_PASS` = the password
   
   **Method 3: Using Environment Variables (Auto-linking)**
   - In your **Web Service** dashboard, go to "Environment" tab
   - Click "Link Database" or "Add Database"
   - Select your `sultan-library-db`
   - Render will automatically create environment variables:
     - `DATABASE_URL` (connection string)
     - Or individual variables if available
   - You can then extract values from `DATABASE_URL` or use them directly

8. **Add Environment Variables to Web Service:**
   
   Go to your **Web Service** → "Environment" tab and add these variables:
   
   **Required Variables:**
   - `APP_ENV` = `production`
   - `APP_DEBUG` = `false`
   - `DB_HOST` = The host from step 7 (e.g., `dpg-xxxxx-a.oregon-postgres.render.com`)
   - `DB_NAME` = The database name (e.g., `sultan_library`)
   - `DB_USER` = The username (e.g., `sultan_user`)
   - `DB_PASS` = The password (copy from database dashboard)
   - `BASE_URL` = Click "Generate" button or leave empty (will auto-detect)
   - `TIMEZONE` = `Europe/Paris`
   
   **Optional Variables (can leave empty - they have defaults):**
   - `ASSETS_URL` = (leave empty - auto-generated)
   - `UPLOADS_URL` = (leave empty - auto-generated)
   - `SESSION_LIFETIME` = `2592000` (optional)
   - `SESSION_NAME` = `SULTAN_SESSION` (optional)
   - `ADMIN_EMAIL` = `test@sultanlibrary.com` (⚠️ change this to your email!)
   - `CSRF_TOKEN_NAME` = `csrf_token` (optional)
   - `UPLOAD_MAX_SIZE` = `5242880` (optional)
   - `FREE_SHIPPING_THRESHOLD` = `100` (optional)
   - `DEFAULT_SHIPPING_COST` = `9.99` (optional)
   - `PRODUCTS_PER_PAGE` = `16` (optional)
   - `DB_PORT` = (Optional) Usually `3306` for MySQL or `5432` for PostgreSQL
   
   **💡 Tip:** See `RENDER_ENV_VARS.md` for a complete reference guide!
   
   **Note:** If using PostgreSQL, you might need to update your `src/config/database.php` to use `pgsql:` instead of `mysql:` in the DSN.

9. **Deploy Database Schema:**
   - Once deployed, go to your service
   - Click "Shell" tab
   - Run: `mysql -h $DB_HOST -u $DB_USER -p$DB_PASS $DB_NAME < database/schema.sql`
   - Or use the Render MySQL console

10. **Update config.php for production:**
   - The `BASE_URL` will be your Render URL (e.g., `https://sultan-library.onrender.com/`)
   - Update `src/config/config.php` if needed

11. **Deploy!**
    - Click "Create Web Service"
    - Wait for deployment (~2-3 minutes)
    - Your site will be live at: `https://sultan-library.onrender.com`

### Continuous Deployment:
- Every time you push to `main` branch → automatic deployment
- Your client will see updates within 2-3 minutes
- Preview URLs for pull requests

---

## 🚀 Option 2: Railway (Alternative)

1. Sign up at [railway.app](https://railway.app)
2. Click "New Project" → "Deploy from GitHub repo"
3. Select your repository
4. Railway auto-detects PHP
5. Add MySQL database from the dashboard
6. Set environment variables
7. Deploy!

---

## 🚀 Option 3: Vercel (PHP Runtime)

Vercel supports PHP via their PHP Runtime.

1. Create `vercel.json` in project root:
```json
{
  "version": 2,
  "builds": [
    {
      "src": "public/index.php",
      "use": "@vercel/php"
    }
  ],
  "routes": [
    {
      "src": "/(.*)",
      "dest": "public/$1"
    }
  ]
}
```

2. Connect GitHub repo to Vercel
3. Deploy!

**Note:** Vercel uses serverless functions, so you may need to adapt your database connection for serverless MySQL.

---

## 📝 Quick Setup Checklist

Before deploying:

- [ ] Push code to GitHub
- [ ] Update `src/config/config.php` with production settings
- [ ] Ensure `APP_DEBUG = false` in production
- [ ] Set proper `BASE_URL` for production
- [ ] Database credentials configured
- [ ] Database schema imported
- [ ] Test locally first

---

## 🔧 Production Configuration

Update `src/config/config.php`:

```php
define('APP_ENV', 'production');
define('APP_DEBUG', false);
define('BASE_URL', 'https://your-app-url.com/'); // Your Render/Railway URL
```

---

## 🎯 After Deployment

Your client can:
- ✅ See live updates within minutes of your git push
- ✅ Access the site 24/7
- ✅ Test all features
- ✅ Share the URL with others

---

## 💡 Pro Tips

1. **Use Render's free tier** for testing - it's perfect for client demos
2. **Enable auto-deploy** from main branch
3. **Use preview deployments** for testing before merging
4. **Set up database backups** in production
5. **Monitor logs** in Render dashboard

---

Need help? Check Render docs: https://render.com/docs

