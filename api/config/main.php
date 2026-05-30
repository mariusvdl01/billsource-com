<?php
$params = array_merge(
	require (__DIR__ . '/../../common/config/params.php')
);

return [
	'id' => 'billsource-api',
	'basePath' => dirname(__DIR__),
	'runtimePath' => dirname(__DIR__) . '/runtime',
	'bootstrap' => ['log'],
	'modules' => [
		'v1' => [
			'basePath' => '@app/modules/v1',
			'class' => 'api\modules\v1\Module', // here is our v1 modules
		],
	],
	'components' => [
		'user' => [
			'identityClass' => 'common\models\User',
			'enableSession' => false,
			'loginUrl' => '',
			'enableAutoLogin' => false,
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
			'enableStrictParsing' => true,
			'showScriptName' => true,
			'rules' => [
				[
					'class' => 'yii\rest\UrlRule',
					'pluralize' => false,
					'controller' => ['v1/index', 'vi/user', 'v1/invoice', 'v1/payment'],
                    'extraPatterns' => [
                        'POST register' => 'register',
                        'GET fetch-debtors' => 'fetch-debtors',
                        'GET fetch-creditors' => 'fetch-creditors',
                        'GET fetch-invoice' => 'fetch-invoice',
                        'GET methods' => 'methods',
                        'POST submit' => 'submit',
                        'POST set-transaction' => 'set-transaction'
                    ]
				],
				'OPTIONS v1/account/login' => 'v1/user/login',
				'POST v1/account/login' => 'v1/user/login',
			],
		],
		'response' => [
			'format' => yii\web\Response::FORMAT_JSON,
			'charset' => 'UTF-8',
		],
		'request' => [
			'class' => '\yii\web\Request',
			'enableCookieValidation' => false,
			'parsers' => [
				'application/json' => 'yii\web\JsonParser',
			],
		],
	],
	'params' => $params,
];
