<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
	'runtimePath' => dirname(dirname(__DIR__)) . '/var',
    'components' => [
    	'authManager' => [
    		'class' => 'yii\rbac\DbManager',
    	],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'paypal' => [
            'class' => 'common\components\Paypal',
        ],
        'auditManager' => [
            'class' => 'common\models\AuditTrail',
        ],
        'formatter'   => [
            'class'    => 'yii\i18n\Formatter',
            'locale' => 'en_ZA',
            'timeZone' => 'Africa/Johannesburg',
            'decimalSeparator' => ',',
            'thousandSeparator' => ' ',
            'currencyCode' => 'ZAR',
            'numberFormatterOptions' => [
                NumberFormatter::MIN_FRACTION_DIGITS => 2,
                NumberFormatter::MAX_FRACTION_DIGITS => 2,
            ]
        ],
        'i18n' => [
            'translations' => [
                'app*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@app/messages',
                    'sourceLanguage' => 'en_US',
                    'fileMap' => [
                        'app' => 'app.php',
                        'app/error' => 'error.php',
                    ],
                ],
            ],
        ],
        'view' => [
            'theme' => [
                'pathMap' => [
                    '@vendor/thyseus/yii2-message/views/message' => '@app/views/message'
                ]
            ],
        ],
    ],
    'modules' => [
        /*'user' => [
            'class' => 'dektrium\user\Module',
            'controllerMap' => [
                'registration' => 'frontend\controllers\user\ExtendedMessageController'
            ],
        ],*/
        'message' => [
            'class' => 'thyseus\message\Module',
            'userModelClass' => '\common\models\User',// User model.Needs to be ActiveRecord.
            'controllerMap' => [
                'message' => 'frontend\controllers\ExtendedMessageController',
            ],
            'modelMap' => [
                'Message' => 'common\models\message\Message',
                'MessageSearch' => 'common\models\message\MessageSearch'
            ]
        ],
    ],
];
