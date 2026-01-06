<?php
return [
    'components' => [
        'db' => [
            // Muda 'projetoteste' para o nome da tua BD de teste
            'dsn' => 'mysql:host=localhost;dbname=projetoteste', 
            'username' => 'root',
            'password' => 'bernss', // Coloca a tua password do MySQL se tiveres
            'charset' => 'utf8',
        ],
        'request' => [
            'cookieValidationKey' => 'test',
            // Fix for "Unable to determine the path info of the current request"
            'scriptFile' => __DIR__ . '/../web/index-test.php',
            'scriptUrl' => '/projeto/backend/web/index-test.php',
            'baseUrl' => '/projeto/backend/web',
            'hostInfo' => 'http://localhost',
        ],
        'urlManager' => [
            'showScriptName' => true,
            'enablePrettyUrl' => false,
            'rules' => [],
        ],
    ],
];