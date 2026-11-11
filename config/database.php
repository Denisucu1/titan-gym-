<?php

return [
    'default' => 'sqlite',
    'connections' => [
        'sqlite' => [
            'database' => ROOT_DIR . '/storage/database.sqlite',
            'options'  => [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ],
        ],
        'mysql' => [
            'host'     => '127.0.0.1',
            'port'     => '3306',
            'database' => 'gym_db',
            'username' => 'root',
            'password' => '',
            'charset'  => 'utf8mb4',
            'options'  => [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false, 
            ],
        ],
    ],
];