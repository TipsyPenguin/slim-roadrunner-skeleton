<?php
declare(strict_types=1);

use DI\ContainerBuilder;
use Monolog\Logger;

return function (ContainerBuilder $containerBuilder) {
    // Global Settings Object
    $containerBuilder->addDefinitions([
        'settings' => [
            'displayErrorDetails' => true, // Should be set to false in production

            // LOGGER
            'logger' => [
                'name' => 'slim-app',
                'path' => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/app.log',
                'level' => Logger::DEBUG,
            ],

            // DOCTRINE
            'doctrine' => [
                'eventmanager' => [
                    'orm_default' => [
                        'subscribers' => [
                            \Gedmo\Tree\TreeListener::class
                        ]
                    ]
                ],

                // if true, metadata caching is forcefully disabled
                'dev_mode' => true,

                // path where the compiled metadata info will be cached
                // make sure the path exists and it is writable
                'cache_dir' => __DIR__ . '/../var/doctrine',

                // you should add any other path containing annotated entity classes
                'metadata_dirs' => [__DIR__ . '/../src'],

                'connection' => [
                    'orm_default' => [
                        'driverClass' => \Doctrine\DBAL\Driver\PDOPgSql\Driver::class,
                        'host'           => $_ENV['APP_DB_HOST'],
                        'port'           => $_ENV['APP_DB_PORT'],
                        'user'           => $_ENV['APP_DB_USER'] ?? '',
                        'password'       => $_ENV['APP_DB_PASSWORD'] ?? '',
                        'dbname'         => $_ENV['APP_DB_DATABASE'] ?? '',
                    ],
                ]
            ]
        ],
    ]);
};
