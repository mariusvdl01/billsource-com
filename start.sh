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
        'request' => [
            'cookieValidationKey' => getenv('COOKIE_VALIDATION_KEY') ?: 'billsource2026securekey_changethis',
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

# Generate params-local.php for all config directories that need it
for DIR in common frontend backend api console; do
    CONFIG_DIR="/var/www/html/${DIR}/config"
    if [ -d "$CONFIG_DIR" ]; then
        cat > "${CONFIG_DIR}/params-local.php" << 'PHPEOF'
<?php
return [];
PHPEOF
        echo "Generated ${CONFIG_DIR}/params-local.php"
    fi
done

# Download bower assets directly into vendor/bower/ at startup
# This bypasses Composer's broken bower routing entirely
echo "Setting up bower assets..."
mkdir -p /var/www/html/vendor/bower

download_bower() {
    local name=$1
    local url=$2
    local dest="/var/www/html/vendor/bower/${name}"
    if [ ! -d "$dest" ]; then
        echo "Downloading $name..."
        mkdir -p "$dest"
        curl -sL "$url" -o /tmp/${name}.zip &&         unzip -q /tmp/${name}.zip -d /tmp/${name}_extracted &&         extracted=$(ls /tmp/${name}_extracted/ | head -1) &&         cp -r /tmp/${name}_extracted/${extracted}/. "$dest/" &&         rm -rf /tmp/${name}.zip /tmp/${name}_extracted
        echo "Installed: $name"
    fi
}

download_bower "jquery"              "https://github.com/jquery/jquery-dist/archive/refs/tags/3.7.1.zip"
download_bower "bootstrap"           "https://github.com/twbs/bootstrap/archive/refs/tags/v3.4.1.zip"
download_bower "jquery-ui"           "https://github.com/jquery/jquery-ui/archive/refs/tags/1.12.1.zip"
download_bower "yii2-pjax"           "https://github.com/yiisoft/jquery-pjax/archive/refs/tags/2.0.8.zip"
download_bower "inputmask"           "https://github.com/RobinHerbots/Inputmask/archive/refs/tags/3.3.11.zip"
download_bower "punycode"            "https://github.com/bestiejs/punycode.js/archive/refs/tags/v1.3.2.zip"
download_bower "select2"             "https://github.com/select2/select2/archive/refs/tags/4.0.13.zip"
download_bower "bootstrap-fileinput" "https://github.com/kartik-v/bootstrap-fileinput/archive/refs/tags/v5.5.4.zip"
download_bower "bootstrap-datepicker" "https://github.com/uxsolutions/bootstrap-datepicker/archive/refs/tags/v1.10.0.zip"
download_bower "bootstrap-touchspin" "https://github.com/istvan-ujjmeszaros/bootstrap-touchspin/archive/refs/tags/4.3.0.zip"
download_bower "bootstrap-switch"    "https://github.com/Bttstrp/bootstrap-switch/archive/refs/tags/v3.3.4.zip"
download_bower "moment"              "https://github.com/moment/moment/archive/refs/tags/2.29.4.zip"
download_bower "spin.js"             "https://github.com/fgnass/spin.js/archive/refs/tags/2.3.2.zip"

echo "Bower assets ready: $(ls /var/www/html/vendor/bower/)"

# Fix Apache MPM conflict at runtime
a2dismod mpm_event mpm_worker 2>/dev/null || true
a2enmod mpm_prefork 2>/dev/null || true

LISTEN_PORT="${PORT:-80}"
echo "Configuring Apache on port ${LISTEN_PORT}"

# yii2-app-practical: repo root IS the web root (index.php at /var/www/html/)
cat > /etc/apache2/sites-available/000-default.conf << APACHEEOF
ServerName billsource.railway.app

<VirtualHost *:${LISTEN_PORT}>
    ServerAdmin webmaster@localhost
    DocumentRoot /var/www/html

    SetEnvIf X-Forwarded-Proto https HTTPS=on

    <Directory /var/www/html>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog \${APACHE_LOG_DIR}/error.log
    CustomLog \${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
APACHEEOF

sed -i "s/Listen 80/Listen ${LISTEN_PORT}/" /etc/apache2/ports.conf

echo "Starting Apache on port ${LISTEN_PORT} with DocumentRoot /var/www/html"
exec apache2-foreground
