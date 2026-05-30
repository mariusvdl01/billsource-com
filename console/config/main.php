<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-console',
    'name' => 'Billsource',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'console\controllers',
    'components' => [
        'auditManager' => [
            'class' => 'common\models\AuditTrail'
        ],
        'smsManager' => [
            'class' => 'common\models\sms\SmsManager'
        ],
        'mailManager' => [
            'class' => 'common\models\email\MailManager'
        ],
        'mailer' => [
            'class' => 'common\helpers\Mailer',
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'urlManager'  => [
            'baseUrl' => '',
            'enablePrettyUrl' => true,
            'showScriptName'  => false,
            'rules'           => [
                '<controller:\w+>'                       => '<controller>',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>'          => '<controller>/<action>',
            ],
        ],
    ],
    'params' => $params,
];
