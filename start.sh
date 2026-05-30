#!/bin/bash
set -e

# Generate main-local.php for each config directory from Railway env vars
for DIR in common frontend backend api console; do
    CONFIG_DIR="/var/www/html/${DIR}/config"
    if [ -d "$CONFIG_DIR" ]; then
        cat > "${CONFIG_DIR}/main-local.php" << 'PHPEOF'
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

# Generate params-local.php
if [ -d "/var/www/html/common/config" ]; then
    cat > /var/www/html/common/config/params-local.php << 'PHPEOF'
<?php
return [];
PHPEOF
fi

# Fix Apache MPM conflict at runtime
a2dismod mpm_event mpm_worker 2>/dev/null || true
a2enmod mpm_prefork 2>/dev/null || true

# Fix redirect loop — write a clean vhost config that handles Railway's reverse proxy
LISTEN_PORT="${PORT:-80}"
echo "Configuring Apache on port ${LISTEN_PORT}"

cat > /etc/apache2/sites-available/000-default.conf << APACHEEOF
<VirtualHost *:${LISTEN_PORT}>
    ServerAdmin webmaster@localhost
    DocumentRoot /var/www/html/web

    # Trust Railway's reverse proxy headers
    SetEnvIf X-Forwarded-Proto https HTTPS=on

    <Directory /var/www/html/web>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted

        # Prevent redirect loops from .htaccess rewrites
        RewriteEngine On
        RewriteCond %{REQUEST_FILENAME} !-f
        RewriteCond %{REQUEST_FILENAME} !-d
        RewriteRule . index.php [L]
    </Directory>

    ErrorLog \${APACHE_LOG_DIR}/error.log
    CustomLog \${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
APACHEEOF

sed -i "s/Listen 80/Listen ${LISTEN_PORT}/" /etc/apache2/ports.conf

echo "Starting Apache..."
exec apache2-foreground
