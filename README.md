<p align="center">
  <a href="https://laravel.com" target="_blank">
    <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
  </a>
</p>

<p align="center">
  <a href="https://github.com/laravel/laravel/actions"><img src="https://github.com/laravel/laravel/workflows/tests/badge.svg" alt="Build Status"></a>
  <a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
  <a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Version"></a>
  <a href="https://opensource.org/licenses/MIT"><img src="https://img.shields.io/badge/license-MIT-brightgreen.svg" alt="License"></a>
</p>

---

# Laravel 11 – Task Manager

A modern and minimal **task manager application** built with Laravel 11, PHP 8.2+, and Blade templating.

Manage tasks, assign teammates, track daily schedules, and visualize weekly workload — all in a clean Laravel stack.

---

## ⚙️ Requirements

- PHP `^8.2`
- Composer
- Node.js & npm (for frontend assets)
- MySQL
- Laravel 11\
- Blade (UI)

---

## 🚀 Installation

```bash
# Clone the repo
git clone https://github.com/tvarga94/task-management-system.git
cd your-project

# Install PHP dependencies
composer install

# Copy .env and generate key
cp .env.example .env
php artisan key:generate

# Configure your database in .env, then run:
php artisan migrate

# Install frontend assets (Blade + Tailwind via Vite)
npm install && npm run dev

# Start local dev server
php artisan serve
