# Quick Deployment Guide - Sultan Library

Deploy your PHP website so your client can see updates consistently (like Vercel).

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
   - **Environment:** `PHP`
   - **Build Command:** (leave empty)
   - **Start Command:** `php -S 0.0.0.0:$PORT -t public`
   - **Root Directory:** (leave empty, or use `.`)

5. **Add Environment Variables:**
   - `APP_ENV` = `production`
   - `APP_DEBUG` = `false`
   - `BASE_URL` = (will be auto-set by Render)

6. **Create Database:**
   - Click "New +" → "PostgreSQL" or "MySQL"
   - Choose "Free" plan
   - Name it: `sultan-library-db`
   - Copy the connection details

7. **Update Database Environment Variables:**
   - `DB_HOST` = (from database connection)
   - `DB_NAME` = (from database connection)
   - `DB_USER` = (from database connection)
   - `DB_PASS` = (from database connection)

8. **Deploy Database Schema:**
   - Once deployed, go to your service
   - Click "Shell" tab
   - Run: `mysql -h $DB_HOST -u $DB_USER -p$DB_PASS $DB_NAME < database/schema.sql`
   - Or use the Render MySQL console

9. **Update config.php for production:**
   - The `BASE_URL` will be your Render URL (e.g., `https://sultan-library.onrender.com/`)
   - Update `src/config/config.php` if needed

10. **Deploy!**
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

