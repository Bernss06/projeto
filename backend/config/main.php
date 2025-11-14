<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' =>
        ['api' => ['class' => 'backend\modules\api\ModuleAPI',],],
    'components' => [
        'view' => [

        ],
        'request' => [
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
            'csrfParam' => '_csrf-backend',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => \yii\log\FileTarget::class,
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false,
            'rules' => [
                // Users
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['api/user'],
                    'pluralize' => false, // cria /users em vez de /user
                    'extraPatterns' => [
                    ],
                    'tokens' => [
                        '{id}' => '<id:\\d+>',
                    ],
                ],

                //Coleções
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['api/colecao'],
                    'pluralize' => false,
                    'extraPatterns' => [
                        'GET count' => 'count',
                        'GET count/{userid}' => 'countporuser',
                        'GET user/{userid}' => 'colecaoporuser',
                    ],
                    'tokens' => [
                        '{id}' => '<id:\\d+>',
                        '{userid}' => '<userid:\\d+>',
                    ],
                ],

                // Regras normais do backend (mantém estas)
                //'<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                //'<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ],
        ],

    ],
    'params' => $params,
];
