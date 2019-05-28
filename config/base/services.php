<?php

use Cocur\Slugify\Slugify;
use DI\Container;
use FastRoute\Dispatcher;
use GuzzleHttp\Client as Guzzle;
use Interop\Container\ContainerInterface;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Noodlehaus\Config;
use Leftaro\App\Twig\AppExtension;
use Leftaro\App\Session;
use Leftaro\App\Hex\ClassNameExtractor;
use Leftaro\App\Hex\CommandBus;
use Leftaro\App\ExceptionHandler;
use Leftaro\Core\Exception\ExceptionHandlerInterface;
use Leftaro\Core\Console\Generator\LeftaroTwigGenerator;
use League\Tactician\Handler\CommandHandlerMiddleware;
use League\Tactician\Handler\Locator\CallableLocator;
use League\Tactician\Handler\MethodNameInflector\HandleInflector;
use Psr\Log\LoggerInterface;
use Psr\Log\LoggerAwareInterface;

return [

	// Auto-wiring
	Config::class => function (ContainerInterface $container)
	{
		return $container->get('config');
	},

	Logger::class => function (ContainerInterface $container)
	{
		$log = new Logger('leftaro');
		$log->pushHandler(new StreamHandler($container->get('config')->get('paths.logs') . gmdate('Y-m-d') . '.log', Logger::INFO));
		return $log;
	},

	LoggerInterface::class => function (ContainerInterface $container)
	{
		return $container->get(Logger::class);
	},

	Dispatcher::class => function (ContainerInterface $container)
	{
		return FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) use ($container)
		{
			foreach (require_once __DIR__ . '/routes.php' as $route)
			{
				list($method, $endpoint, $handlerClass, $handlerMethod) = $route;

				$r->addRoute(strtoupper($method), $endpoint, $handlerClass . '::' . $handlerMethod);
			}
		});
	},

	Container::class => function(ContainerInterface $container)
	{
		return $container;
	},

	Session::class => function(ContainerInterface $container)
	{
		return (session_status() === PHP_SESSION_NONE) ? Session::init() : $container->get(Session::class);
	},

	CommandBus::class => function(ContainerInterface $container)
	{
		return $container->get('bus');
	},

	Slugify::class => function(ContainerInterface $container)
	{
		return new Slugify;
	},

	ConnectionInterface::class => function(ContainerInterface $container)
	{
		return Propel::getWriteConnection('default');
	},

	Guzzle::class => function(ContainerInterface $container)
	{
		return $container->get('guzzle');
	},

	LeftaroTwigGenerator::class => function (ContainerInterface $container)
	{
		return new LeftaroTwigGenerator(
			new Twig_Loader_Filesystem($container->get('config')->get('paths.leftaro.templates'))
		);
	},

	// Helper and aliases
	'config' => function ()
	{
        return new Config(__DIR__ . '/settings.php');
	},

	'logger' => function (ContainerInterface $container)
	{
		return $container->get(Logger::class);
	},

	'twig' => function (ContainerInterface $container)
	{
		$loader = new Twig_Loader_Filesystem($container->get('config')->get('paths.views'));

		$twig = new Twig_Environment($loader,
		[
			//'cache' => $container->get('config')->get('paths.views_cache'),
		]);

		$twig->addExtension(new AppExtension($container));

		return $twig;
	},

	'database' => function(ContainerInterface $container)
	{
		return DriverManager::getConnection($container->get('config')->get('database'), new Configuration());
	},

	'dispatcher' => function(ContainerInterface $container)
	{
		return $container->get(Dispatcher::class);
	},

	'session' => function(ContainerInterface $container)
	{
		return $container->get(Session::class);
	},

	'command_handler_middleware' => function($container)
	{
		return new CommandHandlerMiddleware(
			new ClassNameExtractor,
			new CallableLocator(function($className) use ($container)
			{
				$handler = $container->make(str_replace('Command', 'Handler', $className));

				if ($handler instanceof LoggerAwareInterface)
				{
					$handler->setLogger($container->get('logger'));
				}

				return $handler;
			}),
			new HandleInflector
		);
	},

	'bus' => function(ContainerInterface $container)
	{
		return new CommandBus([$container->get('command_handler_middleware')], $container);
	},

	'image_uploader' => function(ContainerInterface $container)
	{
		return function($imageData) use ($container)
		{
			$id = uniqid();

			list($type, $imageData) = explode(';', $imageData);
			list(,$extension) = explode('/',$type);
			list(,$imageData) = explode(',', $imageData);
			$fileName = $id . '.' . $extension;
			$imageData = base64_decode($imageData);
			file_put_contents($container->get('config')->get('paths.uploads') . $fileName, $imageData);

			return [
				'id' => $id,
				'type' => $extension,
			];
		};
	},

	'guzzle' => function($container)
	{
		return new Guzzle;
	},

	'container' => function (ContainerInterface $container)
	{
		return $container;
	},

	ExceptionHandlerInterface::class => function (ContainerInterface $container)
	{
		$handler = new ExceptionHandler;
		$handler->setContainer($container);
		return $handler;
	},
];