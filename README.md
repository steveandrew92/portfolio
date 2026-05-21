# Stevent Andrean — Portfolio (Laravel)

Website portfolio single-page, **tanpa database**. Semua konten di `storage/app/portfolio.json`.

## Stack

- Laravel 13
- Tailwind CSS 4 + Vite
- GSAP + ScrollTrigger (parallax, reveal, magnetic buttons)
- Theme per role: System Analyst (cyan), Software Developer (emerald), Design Editor (purple)

## Lokal

```bash
composer install
cp .env.example .env
php artisan key:generate
npm ci && npm run build
php artisan serve
```

## Deploy Hostinger

Lihat **`HOSTINGER-DEPLOY.md`** (Bahasa Indonesia, step-by-step).

## Konten

Edit `storage/app/portfolio.json` — roles, catalog, case study.
