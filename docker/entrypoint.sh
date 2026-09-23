#!/bin/sh

# Tetap jalankan skrip meskipun ada command yang warning/error
set +e

echo "==> Setting permissions..."
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache 2>/dev/null || true
chmod -R 775 /var/www/storage /var/www/bootstrap/cache 2>/dev/null || true

echo "==> Running Laravel Optimizations..."
php artisan package:discover --ansi || true
php artisan config:clear || true
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true
php artisan storage:link || true

echo "==> Running Database Migrations..."
php artisan migrate --force || true

echo "==> Starting PHP-FPM..."
exec "$@"