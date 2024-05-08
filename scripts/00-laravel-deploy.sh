#!/usr/bin/env bash
echo "Running composer"
composer install --working-dir=/var/www/html
mv /var/www/html/.env.deploy /var/www/html/.env

echo "update env permissions...";
chown www-data:www-data /var/www/html/.env
chmod 664 /var/www/html/.env


echo "generating application key..."
php artisan key:generate

echo "Caching config..."
php artisan config:cache

echo "Caching routes..."
php artisan route:cache