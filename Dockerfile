FROM richarvey/nginx-php-fpm:latest

COPY . .

# Image config
ENV SKIP_COMPOSER 1
ENV WEBROOT /var/www/html/public
ENV PHP_ERRORS_STDERR 1
ENV RUN_SCRIPTS 1
ENV REAL_IP_HEADER 1

# Laravel config
ENV APP_ENV local
ENV APP_DEBUG true
ENV LOG_CHANNEL stderr
ENV APP_STAGE demo
ENV APP_KEY=base64:P4SbjaUm/Yhk6lmMV50Zi02z9mVpL7DKYj3X5HDj0dI=

# Allow composer to run as root
ENV COMPOSER_ALLOW_SUPERUSER 1

## new 

RUN apk update

# Install the `npm` package
RUN apk add --no-cache npm

# Install required packages
RUN apk add --no-cache autoconf g++ make libmemcached-dev libpng-dev libjpeg-turbo-dev freetype-dev libbz2

# Install the `gd` PHP extension
RUN docker-php-ext-configure gd --with-jpeg --with-freetype \
    && docker-php-ext-install -j$(nproc) gd

# Install the `bcmatch` PHP extension
RUN docker-php-ext-install -j$(nproc) bcmath

# start base docker script
# https://github.com/richarvey/nginx-php-fpm/blob/main/scripts/start.sh#L222
CMD ["/00-laravel-deploy.sh"]