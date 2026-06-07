# Zeven Marketplace - Frontend Laravel

![Zeven Logo](https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg)

Web Frontend utama untuk platform **Zeven Social E-Commerce Marketplace**. Dibangun menggunakan Laravel 10 dengan Vite dan TailwindCSS untuk melayani interface Admin dan Seller dalam mengelola operasional marketplace secara efisien.

## 🚀 Fitur Utama
- **Admin Panel**: Pengelolaan user, kategori, dan pemantauan transaksi global.
- **Seller Dashboard**: Manajemen produk, inventaris stok, dan laporan penjualan.
- **Dynamic UI**: Interface yang responsif dan modern dengan integrasi TailwindCSS.
- **Vite Integration**: Pengembangan frontend yang super cepat dengan hot-module replacement.
- **Blade Templating**: Sistem templating yang modular untuk kemudahan skalabilitas.

## 🛠 Tech Stack
- **Framework**: Laravel 10
- **Frontend Build**: Vite
- **Styling**: TailwindCSS
- **Dependency**: Composer & NPM
- **Templating**: Laravel Blade

## 📦 Instalasi Lokal

1. Clone repository:
   ```bash
   git clone <url-repo-frontend-anda>
   ```
2. Install PHP dependencies:
   ```bash
   composer install
   ```
3. Install JS dependencies:
   ```bash
   npm install
   ```
4. Copy file `.env`:
   ```bash
   cp .env.example .env
   ```
5. Generate App Key:
   ```bash
   php artisan key:generate
   ```
6. Build frontend assets:
   ```bash
   npm run build
   ```
7. Jalankan server:
   ```bash
   php artisan serve
   ```

---
Developed with by **ZevenDev**
