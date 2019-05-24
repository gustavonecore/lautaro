<?php
return [
	'host' => 'http://0.0.0.0:8080/',
	'database' => [
		'dbname' => getenv('DB_NAME'),
		'user' => getenv('DB_USERNAME'),
		'password' => getenv('DB_PASSWORD'),
		'host' => getenv('DB_HOST'),
		'driver' => 'pdo_mysql',
	],
	'paths' => [
		'logs' => __DIR__ . '/../../log/',
		'views' => __DIR__ . '/../../resource/views/',
		'views_cache' => __DIR__ . '/../../resource/cache/',
		'uploads' => __DIR__ . '/../../app/uploads/',
	],
	'middlewares' => [
		// This is executed from bottom to the top
		\Leftaro\App\Middleware\LoggerMiddleware::class,
		\Leftaro\App\Middleware\CorsMiddleware::class,
		\Leftaro\Core\Middleware\RouteMiddleware::class,
		\Leftaro\App\Middleware\InflatorsMiddleware::class,
		\Leftaro\App\Middleware\AuthMiddleware::class,
		\Leftaro\App\Middleware\ContentTypeMiddleware::class,
		\Leftaro\App\Middleware\OptionsHeaderMiddleware::class,
	],
	'command_namespaces' => [
		'Leftaro\\App\\Command\\',
		'Leftaro\\App\\Command\\Util\\',
	],
];