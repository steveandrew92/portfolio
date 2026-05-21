# Cheat sheet — Deploy Portfolio (Hostinger)

## Laptop → GitHub

```bash
cd /Users/steventandrean/Projects/Portfolios
npm run build          # jika ubah CSS/JS
git add .
git commit -m "pesan singkat"
git push origin main
```

## Hostinger SSH

```bash
ssh u485902581@ssh.steventandrean.id
cd ~/domains/steventandrean.id/public_html/portfolio
git pull origin main
/opt/alt/php84/usr/bin/php $(which composer) install --no-dev --optimize-autoloader
/opt/alt/php84/usr/bin/php artisan route:clear
/opt/alt/php84/usr/bin/php artisan config:cache
/opt/alt/php84/usr/bin/php artisan view:cache
```

## Hanya edit JSON

```bash
nano storage/app/portfolio.json
/opt/alt/php84/usr/bin/php artisan cache:clear
```

## Ingat

- **Jangan** `route:cache` di Hostinger  
- `APP_URL` = `https://steventandrean.id` (root, tanpa `/portfolio`)  
- Root htaccess: `deployment/hostinger-public_html-root.htaccess` → `public_html/.htaccess`  
- App htaccess: `deployment/hostinger-portfolio-app-full.htaccess` → `portfolio/.htaccess`

Detail lengkap: **`HOSTINGER-DEPLOY.md`**
