FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    git curl zip unzip libzip-dev libpng-dev libonig-dev \
    && docker-php-ext-install pdo pdo_mysql zip mbstring gd

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

RUN COMPOSER_ALLOW_SUPERUSER=1 composer update \
    --no-dev --optimize-autoloader --no-scripts --no-interaction

# Fix ENV format (legacy "ENV key value" → modern "ENV key=value")
ENV APACHE_DOCUMENT_ROOT=/var/www/html/web

# Point Apache document root to Yii2's web/ folder
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/web|g' \
    /etc/apache2/sites-available/000-default.conf

# Enable mod_rewrite for Yii2 pretty URLs
RUN a2enmod rewrite

# Allow .htaccess overrides
RUN sed -i 's|AllowOverride None|AllowOverride All|g' \
    /etc/apache2/apache2.conf

EXPOSE 80
