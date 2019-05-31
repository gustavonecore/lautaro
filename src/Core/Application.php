<?php namespace Leftaro\Core;

use Exception;
use Leftaro\Core\Exception\ExceptionHandlerInterface;
use Leftaro\Core\Middleware\MiddlewareList;
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
	 * @var \Leftaro\Core\Middleware\MiddlewareList  MiddlewareList
	 */
	protected $middlewareList;

	/**
	 * @var string Routing policy
	 */
	protected $routingPolicy;


	/**
	 * Constructs the main application
	 *
	 * @param \Psr\Container\ContainerInterface $container
	 */
	public function __construct(ContainerInterface $container, ExceptionHandlerInterface $errorHandler)
	{
		$this->container = $container;

		$this->middlewareList = new MiddlewareList($errorHandler);

		$this->emitter = new SapiEmitter;

		$this->setupMiddlewares();
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

			$this->middlewareList->add($middlewareInstance);
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
		return $this->middlewareList->run($request, new Response);
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