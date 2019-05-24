<?php

return [
    'propel' => [
        'database' => [
            'connections' => [
                'default' => [
                    'adapter' => 'mysql',
                    'dsn' => 'mysql:host=' . getenv('DB_HOST') . ';port=' . getenv('DB_PORT') . ';dbname=' . getenv('DB_NAME') . ';charset=utf8',
                    'user' => getenv('DB_USERNAME'),
                    'password' => getenv('DB_PASSWORD'),
                    'settings' => [
                        'charset' => 'utf8'
                    ]
                ]
            ]
		],
		'runtime' => [
            'log' => [
                'defaultLogger' => [
                    'type' => 'stream',
                    'path' => __DIR__ . '/../log/leftaro.log',
                    'level' => 300
                ],
            ]
		],
    ]
];