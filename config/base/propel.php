<?php
return [
    'propel' => [
        'database' => [
            'connections' => [
                'default' => [
                    'adapter' => 'mysql',
                    'dsn' => 'mysql:host=db;port=3306;dbname=transactions;charset=utf8',
                    'user' => 'root',
                    'password' => 'root',
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