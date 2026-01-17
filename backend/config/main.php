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

                // Login
                'POST login' => 'auth/login',

                // Logout
                'POST logout' => 'auth/logout',

                // Register
                'POST register' => 'auth/register',

                // Users
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['api/user'],
                    'pluralize' => false,
                    'extraPatterns' => [
                        'GET count' => 'count',
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
                        'GET count/itens/{colecaoid}' => 'countitenscolecao',
                        'GET user/{userid}' => 'colecaoporuser',
                        'GET itens/{colecaoid}' => 'itensporcolecao',
                        'GET publicas' => 'publicas',
                    ],
                    'tokens' => [
                        '{id}' => '<id:\\d+>',
                        '{userid}' => '<userid:\\d+>',
                        '{colecaoid}' => '<colecaoid:\\d+>',
                    ],
                ],

                //Itens Coleção
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['api/item'],
                    'pluralize' => false,
                    'tokens' => [
                        '{id}' => '<id:\\d+>',
                    ],
                ],

                //Categoria
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['api/categoria'],
                    'pluralize' => false,
                    'tokens' => [
                        '{id}' => '<id:\\d+>',
                    ],
                ],

                //Comentario
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['api/comentario'],
                    'pluralize' => false,
                    'extraPatterns' => [
                        'GET item/{itemid}'   => 'poritem',
                        'POST add'            => 'addcomentario',
                    ],
                    'tokens' => [
                        '{id}' => '<id:\\d+>',
                        '{itemid}' => '<itemid:\d+>',
                    ],
                ],

                //Favorito
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['api/favorito'],
                    'pluralize' => false,
                    'extraPatterns' => [
                        'GET user/{userid}'   => 'poruser',
                        'POST add'            => 'add',
                        'POST remover'            => 'remover',
                    ],
                    'tokens' => [
                        '{id}' => '<id:\\d+>',
                        '{userid}' => '<userid:\\d+>',
                    ],
                ],

                //Gosto
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['api/gosto'],
                    'pluralize' => false,
                    'extraPatterns' => [
                        'GET count' => 'count',
                    ],
                    'tokens' => [
                        '{id}' => '<id:\\d+>',
                    ],
                ],

                // Trocas
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['api/agenda'],
                    'pluralize' => false,
                    'extraPatterns' => [
                        'GET user/{userid}' => 'trocasporuser'
                    ],
                    'tokens' => [
                        '{id}' => '<id:\\d+>',
                        '{userid}' => '<userid:\\d+>',
                    ],
                ],

                // Matematica
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['api/matematica'],
                    'pluralize' => false,
                    'extraPatterns' => [
                        'GET raizdois' => 'raizdois',
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
