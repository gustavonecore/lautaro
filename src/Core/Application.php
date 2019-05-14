<?php namespace Leftaro\Core;

use Exception;
use Leftaro\Core\Exception\ExceptionHandlerInterface;
use Leftaro\Core\Exception\ExceptionHandler;
use Leftaro\Core\Middleware\MiddlewareQueue;
use Leftaro\Core\LoggerAwareInterface;
use Leftaro\Core\ContainerAwareInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response;
use Zend\Diactoros\Response\SapiEmitter;

class Application
{
	/**
	 * @var \Psr\Container\ContainerInterface  Container
	 */
	protected $container;

	/**
	 * @var \Leftaro\Core\Middleware\MiddlewareQueue  MiddlewareQueue
	 */
	protected $middlewareQueue;

	/**
	 * @var string Routing policy
	 */
	protected $routingPolicy;

	/**
	 * @var Leftaro\Core\Exception\ExceptionHandlerInterface
	 */
	protected $errorHandler;

	/**
	 * Constructs the main application
	 *
	 * @param \Psr\Container\ContainerInterface $container
	 */
	public function __construct(ContainerInterface $container)
	{
		$this->container = $container;

		$this->middlewareQueue = new MiddlewareQueue;

		$this->emitter = new SapiEmitter;

		$this->setupMiddlewares();
	}

	/**
	 * Set an error handler
	 *
	 * @param Leftaro\Core\Exception\ExceptionHandlerInterface $errorHandler  Error handler
	 * @return void
	 */
	public function setErrorHandler(ExceptionHandlerInterface $errorHandler)
	{
		$this->errorHandler = $errorHandler;
	}

	/**
	 * If no error handler provided, use this method.
	 *
	 * @return Leftaro\Core\Exception\ExceptionHandlerInterface
	 */
	private function getDefaultErrorHandler() : ExceptionHandlerInterface
	{
		$errorHandler = new ExceptionHandler;

		$errorHandler->setContainer($this->container);

		return $errorHandler;
	}

	public function setRoutingPoligy(string $type)
	{
		$this->routingPolicy = $type;
	}

	/**
	 * Run the full request processing
	 *
	 * @param \Psr\Http\Message\RequestInterface  $request  Request to be handled
	 * @return void
	 */
	public function run(RequestInterface $request)
	{
		$response = $this->runMiddlewares($request);

		$this->emitter->emit($response);
	}

	/**
	 * Configure the existing middlewares
	 */
	private function setupMiddlewares()
	{
		$this->addMiddlewares($this->container->get('config')->get('middlewares'));
	}

	private function addMiddlewares(array $middlewareNames)
	{
		foreach ($middlewareNames as $middlewareClassName)
		{
			$middlewareInstance = $this->container->make($middlewareClassName);

			if (($middlewareInstance instanceof LoggerAwareInterface))
			{
				$middlewareInstance->setLogger($this->container->get('logger'));
			}

			if (($middlewareInstance instanceof ContainerAwareInterface))
			{
				$middlewareInstance->setContainer($this->container->get('container'));
			}

			$this->middlewareQueue->add($middlewareInstance);
		}
	}

	/**
	 * Run the middleware stack with the received request
	 * @param  ServerRequestInterface $request   Request
	 *
	 * @return ResponseInterface Response object
	 */
	private function runMiddlewares(RequestInterface $request) : ResponseInterface
	{
		try
		{
			$response = $this->middlewareQueue->process($request, new Response);
		}
		catch (Exception $e)
		{
			$handler = $this->errorHandler ? $this->errorHandler : $this->getDefaultErrorHandler();

			$response = $handler->getResponse($e, $request);
		}

		return $response;
	}

	/**
	 * Get container instance
	 *
	 * @return ContainerInterface
	 */
	public function getContainer() : ContainerInterface
	{
		return $this->container;
	}
}