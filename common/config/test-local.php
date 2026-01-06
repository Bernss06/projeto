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
    ],
];