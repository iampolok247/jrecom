# 🚀 Multi-Tenant Live Deployment & GitHub CI/CD Setup Guide

Follow this guide to deploy your **JR-Ecom** white-label e-commerce system to **cPanel (e.g. nextfly.online, Bongomart, BikroBD24, Deshioo, Ravelis)** and set up automatic GitHub Actions CI/CD.

---

## 📌 STEP 1: cPanel Server Setup (Fast Deployment)

### 1. Create MySQL Database in cPanel
1. Log into your cPanel (`https://your-domain.com:2083`).
2. Go to **Databases > MySQL Database Wizard**.
3. Create Database: `youruser_jrecom`
4. Create User: `youruser_dbuser` with a strong password.
5. Grant **ALL PRIVILEGES** to the database user.

### 2. Upload Code or Clone via Git
Option A: **Via cPanel Git Version Control**
1. Go to **Files > Git Version Control**.
2. Click **Create** and paste your GitHub Repo URL.
3. Clone path: `/home/youruser/jrecom`

Option B: **Via File Manager (Zip Upload)**
1. Zip your project files (excluding `node_modules` and `vendor`).
2. Upload to `/home/youruser/jrecom` and extract.

### 3. Create `.env` File
In `/home/youruser/jrecom`, create `.env` and configure:
```env
APP_NAME="Bongomart"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=youruser_jrecom
DB_USERNAME=youruser_dbuser
DB_PASSWORD=your_db_password
```

### 4. Point Domain to `public` Folder
In cPanel **Domains > Domains**:
Set Document Root for your domain to:
`/home/youruser/jrecom/public`

*Or add an `.htaccess` file in `public_html`:*
```htaccess
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^$ public/ [L]
    RewriteRule (.*) public/$1 [L]
</IfModule>
```

### 5. Run Initial Setup Command
Via SSH or Terminal in cPanel:
```bash
cd /home/youruser/jrecom
composer install --no-dev --optimize-autoloader
php artisan key:generate
php artisan migrate:fresh --seed
php artisan tenant:setup --name="Bongomart" --email="admin@bongomart.com" --password="password123"
```

---

## 🔄 STEP 2: Multi-Site GitHub Actions CI/CD Setup

To enable 1-push deployment for all 4 hostings (**Bongomart, BikroBD24, Deshioo, Ravelis**):

1. Go to your GitHub Repository > **Settings > Secrets and variables > Actions**.
2. Click **New repository secret** and add FTP details for each site:

| Secret Name | Value |
|---|---|
| `BONGOMART_FTP_HOST` | `ftp.bongomart.com` (or IP) |
| `BONGOMART_FTP_USER` | `bongomart_ftp_username` |
| `BONGOMART_FTP_PASS` | `bongomart_ftp_password` |
| `BIKROBD24_FTP_HOST` | `ftp.bikrobd24.com` |
| `BIKROBD24_FTP_USER` | `bikrobd24_ftp_username` |
| `BIKROBD24_FTP_PASS` | `bikrobd24_ftp_password` |
| `DESHIOO_FTP_HOST` | `ftp.deshioo.com` |
| `DESHIOO_FTP_USER` | `deshioo_ftp_username` |
| `DESHIOO_FTP_PASS` | `deshioo_ftp_password` |
| `RAVELIS_FTP_HOST` | `ftp.ravelis.com` |
| `RAVELIS_FTP_USER` | `ravelis_ftp_username` |
| `RAVELIS_FTP_PASS` | `ravelis_ftp_password` |

Whenever you push to `main` branch, GitHub Actions will automatically push code updates to all hostings in parallel without overwriting their `.env` database and payment credentials!

---

## 🎨 STEP 3: Customize Site Identity per Client

Each client can customize their store logo, primary colors, Paymently API Key, and contact info from:
1. **Admin Panel:** `https://your-domain.com/admin/settings`
2. **Or CLI Command:** `php artisan tenant:setup`
