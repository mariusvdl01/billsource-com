FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    git curl zip unzip libzip-dev libpng-dev libonig-dev \
    libicu-dev \
    && docker-php-ext-install pdo pdo_mysql zip mbstring gd intl

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY composer.json ./

RUN COMPOSER_ALLOW_SUPERUSER=1 composer update \
    --no-dev --optimize-autoloader --no-scripts --no-interaction

COPY . .

# Point Apache to repo root (yii2-app-practical structure)
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html|g' \
    /etc/apache2/sites-available/000-default.conf

# Enable mod_rewrite for Yii2 pretty URLs
RUN a2enmod rewrite

# Fix MPM conflict
RUN a2dismod mpm_event mpm_worker || true \
    && a2enmod mpm_prefork

# Allow .htaccess overrides
RUN sed -i 's|AllowOverride None|AllowOverride All|g' \
    /etc/apache2/apache2.conf

# Fix directory permissions for Apache (www-data)
RUN mkdir -p /var/www/html/assets \
    && mkdir -p /var/www/html/var/runtime/pdf \
    && mkdir -p /var/www/html/var/runtime/logs \
    && mkdir -p /var/www/html/frontend/runtime \
    && mkdir -p /var/www/html/frontend/web/assets \
    && chown -R www-data:www-data /var/www/html/assets \
    && chown -R www-data:www-data /var/www/html/var \
    && chown -R www-data:www-data /var/www/html/frontend/runtime \
    && chown -R www-data:www-data /var/www/html/frontend/web/assets \
    && chmod -R 775 /var/www/html/assets \
    && chmod -R 775 /var/www/html/var \
    && chmod -R 775 /var/www/html/frontend/runtime \
    && chmod -R 775 /var/www/html/frontend/web/assets

COPY start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

EXPOSE 80

CMD ["/usr/local/bin/start.sh"]
