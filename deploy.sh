set -e

cd /var/www/ebook-notifications-service

git pull origin main

composer install --no-dev --optimize-autoloader

php artisan migrate --force

# php artisan optimize:
php artisan config:cache
php artisan route:cache
php artisan view:cache

sudo systemctl reload php8.3-fpm
