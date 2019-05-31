<?php namespace Leftaro\Core\Middleware;

use FastRoute\Dispatcher;
use Leftaro\Core\Middleware\MiddlewareInterface;
use Leftaro\Core\Exception\MethodNotAllowedException;
use Leftaro\Core\Exception\NotFoundException;
use Leftaro\Core\ContainerAwareInterface;
use Leftaro\Core\ContainerAwareTrait;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response;
use Zend\Diactoros\Uri;

class RouteFixedMiddleware implements MiddlewareInterface, ContainerAwareInterface
{
	use ContainerAwareTrait;

	/**
	 * {@inheritDoc}
	 */
	public function __invoke(RequestInterface $request, ResponseInterface $response) : ResponseInterface
	{
		$response = new Response;

		$rootPath = (new Uri($this->container->get('config')->get('host')))->getPath();

		$path = $request->getUri()->getPath();

		if ($rootPath !== '/')
		{
			$path = str_replace($rootPath, '', $request->getUri()->getPath());
			$path = $path !== '' ? '/' . $path : $path;
		}

		$routeInfo = $this->container->get('dispatcher')->dispatch($request->getMethod(), $path);

		switch ($routeInfo[0])
		{
			case Dispatcher::NOT_FOUND:
				throw new NotFoundException($request);
			case Dispatcher::METHOD_NOT_ALLOWED:
				throw new MethodNotAllowedException($request);
			case Dispatcher::FOUND:

				list($controller, $action) = explode('::', $routeInfo[1]);

				// Add url parameters as request attributes
				foreach ($routeInfo[2] as $key => $value)
				{
					$request = $request->withAttribute($key, $value);
				}

				$controllerInstance = $this->container->make($controller);

				$controllerInstance->setRequest($request);

				$response = $controllerInstance->before($request, $response);

				// This execution method 'action' is ugly AF, use a better way. Check the container options
				$response = $controllerInstance->$action($request, $response);

				$response = $controllerInstance->after($request, $response);

			break;
		}

		return $response;
	}
}