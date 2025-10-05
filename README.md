# Laravel Company & Employee Management System

A CRUD application for managing companies and employees with automated email notifications. Built with Laravel 12, Vue 3, Inertia.js, and Ant Design Vue following TDD methodology.

## Features

- Company management (CRUD with logo upload)
- Employee management (CRUD with company relationships)
- Email notifications to companies when employees are added
- Admin-only access control
- Server-side search and pagination
- File upload with validation (max 2MB, JPEG/PNG)

## Requirements

- PHP 8.2+
- Composer 2.x
- Node.js 20.x LTS
- MySQL 8.0

## Installation

### 1. Clone Repository

```bash
git clone git@gitlab.com:aliphamjah/laravel-company-employee-management.git
cd laravel-company-employee-management
```

### 2. Install Dependencies

```bash
composer install
npm install --legacy-peer-deps
```

### 3. Environment Setup

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env`:

```env
DB_CONNECTION=mysql
DB_DATABASE=company_management
DB_USERNAME=laravel
DB_PASSWORD=secret

QUEUE_CONNECTION=database

MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
```

### 4. Database Setup

```bash
mysql -u root -p

CREATE DATABASE company_management;
CREATE DATABASE company_management_test;
CREATE USER 'laravel'@'localhost' IDENTIFIED BY 'secret';
GRANT ALL PRIVILEGES ON company_management.* TO 'laravel'@'localhost';
GRANT ALL PRIVILEGES ON company_management_test.* TO 'laravel'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### 5. Run Migrations

```bash
php artisan migrate --seed
php artisan storage:link
```

### 6. Build Assets

```bash
npm run build
```

### 7. Start Application

```bash
# Terminal 1
php artisan serve

# Terminal 2 (optional - for hot reload)
npm run dev

# Terminal 3 (for email notifications)
php artisan queue:work
```

Access: http://localhost:8000

## Default Users

| Email | Password | Role |
|-------|----------|------|
| admin@grtech.com | password | Administrator |
| user@grtech.com | password | Regular User |

Only admin can access Companies and Employees management.

## Testing

```bash
# Run all tests
php artisan test

# Run specific test
php artisan test --filter=CompanyCrudTest

# With coverage
php artisan test --coverage
```

Tests: 58 passing

## Project Structure

```
app/
├── Http/Controllers/
│   ├── CompanyController.php
│   └── EmployeeController.php
├── Models/
│   ├── Company.php
│   └── Employee.php
└── Notifications/
    └── NewEmployeeAdded.php

resources/js/Pages/
├── Companies/
│   ├── Index.vue
│   ├── Create.vue
│   └── Edit.vue
└── Employees/
    ├── Index.vue
    ├── Create.vue
    └── Edit.vue

tests/Feature/
├── CompanyCrudTest.php
├── EmployeeCrudTest.php
└── EmployeeNotificationTest.php
```

## Common Issues

**Storage permission denied:**
```bash
chmod -R 775 storage
sudo chown -R $USER:$USER storage
```

**Logo upload 403:**
```bash
php artisan storage:link
chmod -R 775 storage/app/public
```

**Tests failing:**
```bash
php artisan config:clear
php artisan migrate:fresh --env=testing
```

**Frontend not building:**
```bash
rm -rf node_modules package-lock.json
npm install --legacy-peer-deps
npm run build
```

## Tech Stack

- Laravel 12
- Vue 3
- Inertia.js
- Ant Design Vue 4.x
- Tailwind CSS
- MySQL 8.0

## License

MIT