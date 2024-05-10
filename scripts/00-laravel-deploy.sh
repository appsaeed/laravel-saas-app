#!/usr/bin/env bash
echo "Running composer"
composer install --working-dir=/var/www/html

echo "update permissions to storage and bootstrap";
sudo chown -R www-data:www-data /var/www/html/bootstrap /var/www/html/storage

echo "generating application key..."
php artisan key:generate

echo "Caching config..."
php artisan config:cache

echo "Caching routes..."
php artisan route:cache