#!/usr/bin/env bash
echo "Running composer"
composer global require hirak/prestissimo
composer install --working-dir=/var/www/html/public

echo "generating application key..."
php artisan key:generate

echo "Caching config..."
php artisan config:cache

echo "Caching routes..."
php artisan route:cache