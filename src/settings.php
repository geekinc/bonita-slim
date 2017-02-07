<?php

/** @var \Dotenv\Dotenv $dotenv */
$dotenv = new Dotenv\Dotenv(__DIR__ . "/..", ".env");
$dotenv->load();

return [
    'settings' => [
        // If you put GEEK in production, change it to false
        'displayErrorDetails' => true,

        // Renderer settings: where are the templates???
        'renderer' => [
            'template_path' => __DIR__ . '/../templates/',
        ],

        // Monolog settings: where are the logs???
        'logger' => [
            'name' => 'GEEK',
            'path' => __DIR__ . '/../logs/app.log',
        ],

        // Doctrine settings
        'doctrine' => [
            'meta' => [
                'entity_path' => [
                    'src/Entity'
                ],
                'auto_generate_proxies' => true,
                'proxy_dir' =>  __DIR__.'/../cache/proxies',
                'cache' => null,
            ],
            'connection' => [
                'driver'   => 'pdo_mysql',
                'host'     => getenv('DB_HOST'),
                'dbname'   => getenv('DB_NAME'),
                'user'     => getenv('DB_USER'),
                'password' => getenv('DB_PASS'),
            ],
        ],
        'admin_users' => [
            getenv('ADMIN_USER') => getenv('ADMIN_PASS')
        ],
        'bonita' => [
            'server' => getenv('BONITA_SERVER'),
            'user' => getenv('BONITA_USER'),
            'password' => getenv('BONITA_PASSWORD')
        ],
        'security_token' => getenv('SECURITY_TOKEN'),
        'base_dir' => getenv('BASE_DIR')
    ],
];
