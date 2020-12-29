<?php

$db     = require(__DIR__ . '/../../config/db.php');
$params = require(__DIR__ . '/../../config/params.php');

$config = [
	'id' => 'basic',
	'name' => 'TimeTracker',
	// Need to get one level up:
	'basePath' => dirname(__DIR__).'/..',
	'bootstrap' => ['log'],
	'components' => [
		'request' => [
			'cookieValidationKey' => 'Fr1NB7GZbHsm-VH9B-Pk1nhx37vtPLZA',
			// Enable JSON Input:
			'parsers' => [
				'application/json' => 'yii\web\JsonParser',
			]
		],
		'response' => [
			'formatters' => [
				\yii\web\Response::FORMAT_JSON => [
					'class' => 'yii\web\JsonResponseFormatter',
					'prettyPrint' => YII_DEBUG, // используем "pretty" в режиме отладки
					'encodeOptions' => JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE,
				],
			],
		],
		'log' => [
			'traceLevel' => YII_DEBUG ? 3 : 0,
			'targets' => [
				[
					'class' => 'yii\log\FileTarget',
					'levels' => ['error', 'warning'],
					// Create API log in the standard log dir
					// But in file 'api.log':
					'logFile' => '@app/runtime/logs/api.log',
				],
			],
		],
		'urlManager' => [
			'enablePrettyUrl' => true,
			'enableStrictParsing' => true,
			'showScriptName' => false,
			'rules' => [
				// Контроллер в соответсвии с заданием
				'GET,POST v1' => 'v1/currency-rate/select',

				// Альтернативный вариант реализаци
				'GET v1/rates' => 'v1/currency/rates',
				'POST v1/convert' => 'v1/currency/convert',
			],
		],
		'db' => $db,
		'user' => [
			'identityClass' => 'app\models\User',
			'enableAutoLogin' => false,
		],
	],
	'modules' => [
		'v1' => [
			'class' => 'app\api\modules\v1\Module',
		],
	],
	'params' => $params,
];

return $config;