# Deploy Portfolio — Hostinger (Git + SSH)

Panduan step-by-step untuk men-deploy project **Portfolios** ke **https://steventandrean.id** (root domain), dengan source code di folder **`public_html/portfolio`**. Pola deploy mengikuti project **Blizanac**.

**Cheat sheet singkat:** `HOSTINGER-DEPLOY-CHEATSHEET.md`

---

## Ringkasan arsitektur

| Lokasi | Fungsi |
|--------|--------|
| `public_html/portfolio/` | Source Laravel (app, vendor, public, storage) |
| `public_html/.htaccess` | Mengarahkan `/` ke `portfolio/public/index.php` |
| `public_html/portfolio/.htaccess` | Front controller Laravel di folder project |
| `storage/app/portfolio.json` | **Semua konten** (roles, katalog, case study) — edit tanpa ubah Blade |

**Tidak perlu database** untuk konten portfolio. `.env` production: `SESSION_DRIVER=file`, `CACHE_STORE=file`.

---

## Bagian A — Setup sekali (laptop)

### A1. Inisialisasi repo Git

```bash
cd /Users/steventandrean/Projects/Portfolios
git init
git add .
git commit -m "Initial portfolio Laravel + JSON"
```

Buat repo kosong di GitHub (mis. `portfolio-steventandrean`), lalu:

```bash
git remote add origin git@github.com:USERNAME/REPO.git
git branch -M main
git push -u origin main
```

### A2. Build asset front-end (wajib sebelum deploy)

```bash
cd /Users/steventandrean/Projects/Portfolios
npm ci
npm run build
```

Pastikan folder **`public/build`** ikut di-commit.

### A3. File `.env` lokal

```bash
cp .env.example .env
php artisan key:generate
php artisan serve
```

Buka http://127.0.0.1:8000 — halaman portfolio harus tampil.

---

## Bagian B — Setup sekali (Hostinger SSH)

### B1. Login SSH

```bash
ssh u485902581@ssh.steventandrean.id
```

*(Ganti user/host sesuai **hPanel → Advanced → SSH Access**.)*

### B2. Clone project ke folder `portfolio`

```bash
cd ~/domains/steventandrean.id/public_html
git clone git@github.com:USERNAME/REPO.git portfolio
cd portfolio
```

### B3. Buat `.env` production (manual, tidak di Git)

```bash
nano .env
```

Isi minimal:

```env
APP_NAME="Stevent Andrean Portfolio"
APP_ENV=production
APP_KEY=base64:...   # salin dari php artisan key:generate di server
APP_DEBUG=false
APP_URL=https://steventandrean.id
ASSET_URL=https://steventandrean.id

SESSION_DRIVER=file
CACHE_STORE=file
QUEUE_CONNECTION=sync
```

Generate key di server:

```bash
/opt/alt/php84/usr/bin/php artisan key:generate
```

### B4. Composer & permission

```bash
cd ~/domains/steventandrean.id/public_html/portfolio
/opt/alt/php84/usr/bin/php $(which composer) install --no-dev --optimize-autoloader
chmod -R ug+rwx storage bootstrap/cache
```

### B5. Pasang `.htaccess` Laravel (folder `portfolio`)

```bash
cp deployment/hostinger-portfolio-app-full.htaccess .htaccess
```

### B6. Pasang `.htaccess` ROOT domain (`public_html`)

**Backup dulu** `.htaccess` yang sudah ada:

```bash
cd ~/domains/steventandrean.id/public_html
cp .htaccess .htaccess.backup-$(date +%Y%m%d) 2>/dev/null || true
cp portfolio/deployment/hostinger-public_html-root.htaccess .htaccess
```

File ini membuat:

- `https://steventandrean.id/` → Portfolio  
- `https://steventandrean.id/blizanac/...` → Blizanac (jika folder masih ada)

### B7. Cache Laravel (tanpa `route:cache`)

```bash
cd ~/domains/steventandrean.id/public_html/portfolio
/opt/alt/php84/usr/bin/php artisan route:clear
/opt/alt/php84/usr/bin/php artisan config:cache
/opt/alt/php84/usr/bin/php artisan view:cache
```

**Jangan** jalankan `php artisan route:cache` di Hostinger shared hosting (risiko error **405**).

### B8. Verifikasi

Buka https://steventandrean.id — harus menampilkan portfolio.  
Jika CSS/JS hilang: cek `APP_URL` dan `ASSET_URL` sama persis `https://steventandrean.id` (tanpa slash di akhir), lalu `config:clear` → `config:cache`.

---

## Bagian C — Deploy rutin (setiap ada perubahan)

### C1. Di MacBook (Cursor) — urutan ini

| # | Langkah | Perintah |
|---|---------|----------|
| 1 | Simpan semua file di editor | — |
| 2 | Build *(hanya jika ubah CSS/JS/vite)* | `cd /Users/steventandrean/Projects/Portfolios && npm run build` |
| 3 | Commit & push | `git add .` → `git commit -m "..."` → `git push origin main` |

### C2. Di Hostinger SSH

```bash
ssh u485902581@ssh.steventandrean.id
cd ~/domains/steventandrean.id/public_html/portfolio
git pull origin main
/opt/alt/php84/usr/bin/php $(which composer) install --no-dev --optimize-autoloader
/opt/alt/php84/usr/bin/php artisan route:clear
/opt/alt/php84/usr/bin/php artisan config:cache
/opt/alt/php84/usr/bin/php artisan view:cache
```

### C3. Hanya ubah konten (JSON)

Edit di server:

`~/domains/steventandrean.id/public_html/portfolio/storage/app/portfolio.json`

Lalu bersihkan cache data:

```bash
/opt/alt/php84/usr/bin/php artisan cache:clear
```

*(Service membaca JSON dengan cache 5 menit.)*

---

## Bagian D — Mengubah konten dari laptop

Edit file:

`storage/app/portfolio.json`

Struktur: `profile`, `theme.roles`, `roles.{system_analyst|software_developer|design_editor}` dengan `catalog[]` per role.

Setelah edit: commit → push → pull di server → `cache:clear` jika perlu.

---

## Troubleshooting

| Gejala | Solusi |
|--------|--------|
| **405** di `/` | `php artisan route:clear` — jangan `route:cache` |
| Halaman putih / 500 | `storage/logs/laravel.log` |
| CSS/JS tidak load | `.env`: `APP_URL` + `ASSET_URL`; pastikan `public/build` terbaru; `config:cache` |
| Root domain tidak ke portfolio | Cek `public_html/.htaccess` → salin ulang dari `deployment/hostinger-public_html-root.htaccess` |
| `/blizanac` rusak setelah ubah root htaccess | Pastikan rule `blizanac` di atas rule portfolio di file root |
| Perubahan JSON tidak tampil | `php artisan cache:clear` |

---

## Opsi alternatif (tanpa root htaccess)

Jika tidak ingin menyentuh `public_html/.htaccess`:

1. Di hPanel, set **Document Root** domain ke `public_html/portfolio/public`  
2. `APP_URL=https://steventandrean.id`  
3. URL publik tetap root, tanpa rewrite di `public_html`

Blizanac tetap di subfolder dengan `.htaccess` sendiri di `public_html/blizanac/`.

---

*Terakhir diperbarui: proyek Portfolios — Laravel + portfolio.json + GSAP.*
