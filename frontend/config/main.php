<?php

use kartik\mpdf\Pdf;
use yii\queue\LogBehavior;
use yii\queue\redis\Queue;

$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-frontend',
    'name' => 'Billsource',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', 'queue'],
    'controllerNamespace' => 'frontend\controllers',
    'defaultRoute' => 'default',
    'components' => [
      'user' => [
            'loginUrl' => ['account/login'],   // wrap in array, safer
            'class' => promocat\twofa\User::class,
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
            // ✅ Use afterLogin, not beforeLogin
            'on afterLogin' => function ($event) {
                $user = $event->identity;

                if ($user->isTrialExpired() && !$user->client->is_subscribed) {
                    // Force redirect to upgrade page after successful login
                    Yii::$app->response->redirect(['/business/profile/upgrade'])->send();
                    Yii::$app->end();
                }
            },
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => true,
            'rules' => [
                '<controller:\w+>' => '<controller>',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'default/error',
        ],
        'pdf' => [
            'class' => Pdf::class,
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'destination' => Pdf::DEST_BROWSER,
            'tempPath' => Yii::getAlias('@var') . '/runtime/pdf',
            'cssFile' => Yii::getAlias('@frontend') . '/assets/invoice/css/style.css',
        ],
        'assetManager' => [
            'linkAssets' => false,
            'appendTimestamp' => false,
        ],
        'queue' => [
            'class' => Queue::class,
            'as log' => LogBehavior::class,
            'redis' => 'redis',
            'channel' => 'queue',
        ]
    ],
    'modules' => [
        'treemanager' => [
            'class' => '\kartik\tree\Module',
            // other module settings, refer detailed documentation
        ],
        'gridview' => [
            'class' => '\kartik\grid\Module',
        ],
        //'mobisite' => [
        //    'class' => 'common\modules\mobisite\Mobisite',
        //],
        'ticket' => [
            'class' => 'common\modules\ticket\Module',
        ],
        'user' => [
            'class' => 'dektrium\user\Module',
            'modelMap' => [
                'User' => 'frontend\models\marketplace\users\User',
            ],
            'controllerMap' => [
                'dashboard' => 'frontend\controllers\marketplace\AdminController',
                'registration' => 'frontend\controllers\marketplace\RegistrationController',
                'profile' => 'frontend\controllers\marketplace\ProfileController',
                'recovery' => 'frontend\controllers\marketplace\RecoveryController',
                'security' => 'frontend\controllers\marketplace\SecurityController',
                'settings' => 'frontend\controllers\marketplace\SettingsController',
                'marketplace' => 'frontend\controllers\MarketplaceController'
            ],
        ],
    ],
    'params' => $params,
];
