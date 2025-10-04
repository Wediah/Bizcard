# BizCard+, a step ahead of the game

## Overview
This application is a business dashboard built with Laravel and Livewire. It allows clients to manage business profiles, services, and reviews, while providing admins with tools for system administration and client management. Analytics and visitor tracking are integrated for business insights.

## Features
- Business profile management
- Service listing and editing
- Review management and approval
- Visitor analytics and page view tracking
- Admin dashboard for client management
- Secure authentication and authorization

## Setup Instructions
1. **Clone the repository:**
   ```bash
   git clone <your-repo-url>
   cd CodingTest
   ```
2. **Install dependencies:**
   ```bash
   composer install
   npm install
   ```
3. **Configure environment:**
   - Copy `.env.example` to `.env` and update database and mail settings.
   - Generate application key:
     ```bash
     php artisan key:generate
     ```
4. **Run migrations:**
   ```bash
   php artisan migrate
   ```
5. **Seed the database (optional):**
   ```bash
   php artisan db:seed
   ```
6. **Build frontend assets:**
   ```bash
   npm run build
   ```
7. **Start the development server:**
   ```bash
   php artisan serve
   ```

## Documentation
- See `ARCHITECTURE.md` for system design
- See `DATABASE_SCHEMA.md` for database details
- See `TECHNICAL_DOCUMENTATION.md` for service and testing docs
- See `USER_GUIDE.md` for user instructions

## Testing
Run all tests with:
```bash
php artisan test
```

---
For deployment instructions, see `DEPLOYMENT.md`.

