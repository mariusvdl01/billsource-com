FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    git curl zip unzip libzip-dev libpng-dev libonig-dev \
    && docker-php-ext-install pdo pdo_mysql zip mbstring gd

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy composer files first for better cache layering
COPY composer.json ./

# Run composer - this layer re-runs whenever composer.json changes
RUN COMPOSER_ALLOW_SUPERUSER=1 composer update \
    --no-dev --optimize-autoloader --no-scripts --no-interaction

# Copy rest of application after composer
COPY . .

ENV APACHE_DOCUMENT_ROOT=/var/www/html/web

RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/web|g' \
    /etc/apache2/sites-available/000-default.conf

RUN a2enmod rewrite

RUN sed -i 's|AllowOverride None|AllowOverride All|g' \
    /etc/apache2/apache2.conf

EXPOSE 80
