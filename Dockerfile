FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    git curl zip unzip libzip-dev libpng-dev libonig-dev \
    && docker-php-ext-install pdo pdo_mysql zip mbstring gd

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy composer files first — cache busts when composer.json changes
COPY composer.json ./

RUN COMPOSER_ALLOW_SUPERUSER=1 composer update \
    --no-dev --optimize-autoloader --no-scripts --no-interaction

# Copy rest of application
COPY . .

# Point Apache to Yii2 web/ folder
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/web|g' \
    /etc/apache2/sites-available/000-default.conf

# Enable mod_rewrite for Yii2 pretty URLs
RUN a2enmod rewrite

# Allow .htaccess overrides
RUN sed -i 's|AllowOverride None|AllowOverride All|g' \
    /etc/apache2/apache2.conf

# Startup script:
# 1. Generates main-local.php from Railway environment variables
# 2. Sets Apache port from $PORT
# 3. Launches Apache
RUN cat > /usr/local/bin/start.sh << 'STARTSCRIPT'
#!/bin/bash
set -e

# Generate all main-local.php files from environment variables
for DIR in common frontend backend api console; do
    CONFIG_DIR="/var/www/html/${DIR}/config"
    if [ -d "$CONFIG_DIR" ]; then
        cat > "${CONFIG_DIR}/main-local.php" << PHPEOF
<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=' . getenv('DB_HOST') . ';port=' . (getenv('DB_PORT') ?: '3306') . ';dbname=' . getenv('DB_NAME'),
            'username' => getenv('DB_USER'),
            'password' => getenv('DB_PASSWORD'),
            'charset' => 'utf8',
            'enableSchemaCache' => true,
            'schemaCacheDuration' => 3600,
            'schemaCache' => 'cache',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => getenv('SMTP_HOST') ?: 'smtp.billsource.com',
                'username' => getenv('SMTP_USER') ?: 'noreply@billsource.com',
                'password' => getenv('SMTP_PASSWORD') ?: '',
                'port' => getenv('SMTP_PORT') ?: '465',
                'encryption' => 'ssl',
                'streamOptions' => [
                    'ssl' => [
                        'allow_self_signed' => true,
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                    ],
                ],
            ],
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'session' => [
            'class' => 'yii\web\Session',
        ],
    ],
    'modules' => [
        'payment' => [
            'class' => 'common\modules\payment\PaymentPluginHandler',
            'paymentPlugins' => [
                'payumea' => 'PayU Secure Payments',
            ],
        ],
        'sms' => [
            'class' => 'common\modules\sms\SmsGateway',
        ],
        'payumea' => [
            'class' => 'common\modules\payu\PayUMEA',
            'apiUsername' => getenv('PAYU_API_USERNAME') ?: '203681',
            'apiPassword' => getenv('PAYU_API_PASSWORD') ?: '',
            'safeKey' => getenv('PAYU_SAFE_KEY') ?: '',
            'checkoutMode' => getenv('PAYU_MODE') ?: 'LIVE',
            'transactionType' => 'PAYMENT',
            'paymentMethods' => 'CREDITCARD,EFT_PRO',
        ],
    ],
];
PHPEOF
        echo "Generated ${CONFIG_DIR}/main-local.php"
    fi
done

# Also generate params-local.php for common
cat > /var/www/html/common/config/params-local.php << PHPEOF
<?php
return [];
PHPEOF

# Set Apache port from Railway \$PORT variable
PORT="${PORT:-80}"
sed -i "s/Listen 80/Listen ${PORT}/" /etc/apache2/ports.conf
sed -i "s/:80>/:${PORT}>/" /etc/apache2/sites-available/000-default.conf

echo "Starting Apache on port ${PORT}"
exec apache2-foreground
STARTSCRIPT

RUN chmod +x /usr/local/bin/start.sh

EXPOSE 80

CMD ["/usr/local/bin/start.sh"]
