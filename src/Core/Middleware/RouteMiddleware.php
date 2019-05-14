<?php namespace Leftaro\Core\Middleware;

use Exception;
use Leftaro\Core\Middleware\MiddlewareInterface;
use Leftaro\Core\Exception\LeftaroException;
use Leftaro\Core\ContainerAwareInterface;
use Leftaro\Core\ContainerAwareTrait;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use RuntimeException;

class RouteMiddleware implements MiddlewareInterface, ContainerAwareInterface
{
	use ContainerAwareTrait;

	/**
	 * Handle the middleware call for request and response approach
	 *
	 * @param  \Psr\Http\Message\RequestInterface    $request   Request instance
	 * @param  \Psr\Http\Message\ResponseInterface   $response  Response instance
	 * @param  callable                              $next      Next callable Middleware
	 *
	 * @return \Psr\Http\Message\ResponseInterface
	 */
	public function __invoke(RequestInterface $request, ResponseInterface $response, callable $next = null) : ResponseInterface
	{
		$response = $this->getResponseByRoutingPolicies($request, $response);

		return $next($request, $response);
	}

	/**
	 * Get a response using the different Routing policies existing in Leftaro framework
	 *
	 * @param RequestInterface $request
	 * @param ResponseInterface $response
	 * @return ResponseInterface
	 */
	private function getResponseByRoutingPolicies(RequestInterface $request, ResponseInterface $response) : ResponseInterface
	{
		try
		{
			$middleware = $this->buildMiddleware(RouteFixedMiddleware::class);

			$response = $middleware($request, $response);
		}
		catch (LeftaroException $e)
		{
			$middleware = $this->buildMiddleware(RouteSmartMiddleware::class);

			$response = $middleware($request, $response);
		}

		return $response;
	}

	/**
	 * Build an hydrated middleware with the aware dependencies
	 *
	 * @param string $middleware
	 * @return void
	 */
	private function buildMiddleware(string $middlewareClass) : MiddlewareInterface
	{
		$middleware = $this->container->make($middlewareClass);

		if (!($middleware instanceof MiddlewareInterface))
		{
			throw new RuntimeException('Middleware ' . $middlewareClass . ' must implement MiddlewareInterface');
		}

		if (($middleware instanceof LoggerAwareInterface))
		{
			$middleware->setLogger($this->container->get('logger'));
		}

		if (($middleware instanceof ContainerAwareInterface))
		{
			$middleware->setContainer($this->container->get('container'));
		}

		return $middleware;
	}
}