<?php

use kartik\mpdf\Pdf;

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
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'defaultRoute' => 'default',
    'components' => [
        'user' => [
            'loginUrl' => ['account/login'],
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
            'on afterLogin' => function ($event) {
                $user = $event->identity;
                if (method_exists($user, 'isTrialExpired') && $user->isTrialExpired()
                    && isset($user->client) && !$user->client->is_subscribed) {
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
            'tempPath' => dirname(dirname(__DIR__)) . '/var/runtime/pdf',
            'cssFile' => dirname(__DIR__) . '/assets/invoice/css/style.css',
        ],
        'assetManager' => [
            'linkAssets' => false,
            'appendTimestamp' => false,
        ],
    ],
    'modules' => [
        'treemanager' => [
            'class' => '\kartik\tree\Module',
        ],
        'gridview' => [
            'class' => '\kartik\grid\Module',
        ],
        'ticket' => [
            'class' => 'common\modules\ticket\Module',
        ],
        'user' => [
            'class' => 'dektrium\user\Module',
            'modelMap' => [
                'User' => 'common\models\User',
            ],
            // marketplace controllers removed — directory does not exist
        ],
    ],
    'params' => $params,
];
