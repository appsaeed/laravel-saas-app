#!/usr/bin/env bash
echo "Running composer"
# composer install --no-dev --working-dir=/var/www/html
composer install --working-dir=/var/www/html
cp /var/www/html/.env.deploy /var/www/html/.env

echo "generating application key..."
php artisan key:generate

echo "Caching config..."
php artisan config:cache

echo "Caching routes..."
php artisan route:cache