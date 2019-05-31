<?php
declare(strict_types = 1);
require __DIR__ . '/bootstrap.php';

if (preg_match('/\.(?:png|jpg|jpeg|gif|css|js|map|woff2|woff|ttf|pdf)$/', $_SERVER["REQUEST_URI"]))
{
	return false;
}

$container = require __DIR__ . '/../config/local/container.php';

$errorHandler = new Leftaro\App\ExceptionHandler($container);
$application = new \Leftaro\App\Application($container, $errorHandler);

$application->initOrm();

$application->run(\Zend\Diactoros\ServerRequestFactory::fromGlobals(
    $_SERVER,
    $_GET,
    $_POST,
    $_COOKIE,
    $_FILES
));