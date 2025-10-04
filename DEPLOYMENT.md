# Deployment Guide

## Production Deployment Steps

1. **Server Requirements**
   - PHP >= 8.1
   - Composer
   - Node.js & npm
   - MySQL or PostgreSQL database
   - Web server (Nginx or Apache)

2. **Environment Configuration**
   - Set up `.env` with production database, mail, and cache settings.
   - Set `APP_ENV=production` and `APP_DEBUG=false`.
   - Set secure keys for JWT, session, and encryption.

3. **Install Dependencies**
   ```bash
   composer install --optimize-autoloader --no-dev
   npm install && npm run build
   ```

4. **Database Migration & Seeding**
   ```bash
   php artisan migrate --force
   php artisan db:seed --force
   ```

5. **Storage & Permissions**
   - Set correct permissions for `storage/` and `bootstrap/cache/`:
     ```bash
     chmod -R 775 storage bootstrap/cache
     chown -R www-data:www-data storage bootstrap/cache
     ```

6. **Caching & Optimization**
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   php artisan optimize
   ```

7. **Queue & Scheduler**
   - Start queue worker:
     ```bash
     php artisan queue:work --daemon
     ```
   - Set up cron for scheduler:
     ```bash
     * * * * * php /path/to/artisan schedule:run >> /dev/null 2>&1
     ```

8. **SSL & Security**
   - Enable HTTPS and configure SSL certificates.
   - Set up firewall and security rules.
   - Regularly update dependencies.

9. **Monitoring & Logging**
   - Monitor logs in `storage/logs/`.
   - Set up external monitoring and alerting as needed.

---
For troubleshooting, refer to the USER_GUIDE.md and README.md.

