FROM richarvey/nginx-php-fpm:latest

COPY . .

# Image config
ENV SKIP_COMPOSER 1
ENV WEBROOT /var/www/html/public
ENV PHP_ERRORS_STDERR 1
ENV RUN_SCRIPTS 1
ENV REAL_IP_HEADER 1

# Laravel config
ENV APP_ENV production
ENV APP_DEBUG false
ENV LOG_CHANNEL stderr

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


CMD ["/start.sh"]