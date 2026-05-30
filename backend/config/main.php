<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-backend',
    'name' => 'Administrator Console',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'defaultRoute' => '/site',
    'bootstrap' => ['log'],
    'components' => [
        'user' => [
            'identityClass' => 'backend\models\user\AdminUser',
            'enableAutoLogin' => true,
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
        'errorHandler' => [
            'errorAction' => 'admin/site/error',
        ],
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
        /*'user' => [
            // following line will restrict access to profile, recovery, registration and settings controllers from backend
            'as backend' => 'dektrium\user\filters\BackendFilter',
        ],*/
    ],
    'params' => $params,
];
