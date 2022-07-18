<?php

use yii\helpers\ArrayHelper;

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'name' => 'Иркутский детский дом-интернат №1 для умственно отсталых детей',
    'sourceLanguage' => 'en-US',
    'language' => 'ru-RU',
    'timeZone' => 'Etc/GMT-8',
    'defaultRoute' => 'main/index',
    'modules' => [
        'admin' => [
            'class' => 'mrssoft\engine\Module',
            'elfinderMaxImageWidth' => 690,
            'elfinderMaxImageHeight' => 600,
        ]
    ],
    'components' => [
        'i18n' => [
            'translations' => [
                '*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@app/messages',
                    'sourceLanguage' => 'en',
                    'fileMap' => [
                        'main' => 'main.php',
                        'site' => 'site.php',
                    ],
                ],
            ],
        ],
        'urlManager' => [
            //'class' => 'mrssoft\multilang\LangUrlManager',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'admin' => 'admin',
                'page/<url:.*>/' => 'page/view',
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>' => '<controller>/index',
            ]
        ],
        'request' => [
            //'class' => 'mrssoft\multilang\LangRequest',
            'cookieValidationKey' => 'lNX0R2Ak8WtHqCLuOkGIQuDZyQGteGs9',
        ],
        'user' => [
            'class' => 'app\components\User',
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
            'loginUrl' => '/user',
        ],
        'errorHandler' => [
            'errorAction' => 'main/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.mail.ru',
                'username' => 'ssl.net@mail.ru',
                'password' => 'VY98DFZm4sx28N3L8MKG',
                'port' => '587',
                'encryption' => 'tls'
            ]
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        'session' => [
            'class' => 'yii\web\DbSession',
            'timeout' => 7200
        ],
        'formatter' => [
            'dateFormat' => 'dd.MM.yyyy',
            'decimalSeparator' => ',',
            'thousandSeparator' => ' ',
            'currencyCode' => 'RU',
            'sizeFormatBase' => 1000,
            'timeZone' => 'UTC'
        ],
        'assetManager' => [
            'class' => 'mrssoft\engine\AssetManager',
            'bundles' => array(
                'yii\web\JqueryAsset' => array(
                    'basePath' => '@webroot',
                    'baseUrl' => '@web',
                    'js' => array(
                        'js/jquery-1.11.3.min.js',
                    ),
                ),
            )
        ],
        'db' => require(__DIR__ . '/db.php'),
    ],
    'params' => $params
];

return ArrayHelper::merge($config, require(__DIR__ . '/local.php'));
