# Alma HR System

Internal Human Resources management system for **Alma Travel** — built with Laravel.

## Features

### 👥 Employee Management
- Employee profiles with departments and roles
- Dynamic custom fields (form builder managed by admins)
- Document management per employee
- Excel import / export with downloadable template

### 🏖️ Leave Management
- Leave requests with multi-stage approval (HR → Manager)
- Annual leave balances per year, leave types, public holidays, weekend settings
- Leave calculation engine mirroring the company's Excel "Analyzer" sheet (21 days/year, 1.75 days/month accrual)
- Historical leave import

### 📊 Reports
- Leave balance, leave history, department, employee and vacation history reports
- Excel exports (including year-by-year)

### 🔐 Administration
- Role-based access: `super_admin`, `hr_manager`, `manager`, `dm`, `employee`
- User management, activity logs, dynamic form builder
- Brute-force protection on login (rate limited: 5 attempts/minute)

## Tech Stack
- Laravel 11, Blade, MySQL
- Laravel Excel (import/export)

## Installation

```bash
cp .env.example .env
composer install
php artisan key:generate
php artisan migrate --seed
npm install && npm run build
php artisan serve
```

## Security Notes
- Keep `.env` out of version control
- Never commit database backups, cookies, or session files to the repo
- Run `php artisan config:clear` and `php artisan view:clear` after deploying changes
