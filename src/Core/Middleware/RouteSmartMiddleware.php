<?php namespace Leftaro\Core\Middleware;

use Exception;
use Leftaro\Core\Controller\AbstractController;
use Leftaro\Core\Middleware\MiddlewareInterface;
use Leftaro\Core\Exception\NotFoundException;
use Leftaro\Core\ContainerAwareInterface;
use Leftaro\Core\ContainerAwareTrait;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use RuntimeException;
use Zend\Diactoros\Uri;

class RouteSmartMiddleware implements MiddlewareInterface, ContainerAwareInterface
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
		error_log("__invoke RouteSmartMiddleware");

		$prefix = '/';
		$path = $request->getUri()->getPath();
		$rootPath = (new Uri($this->container->get('config')->get('host')))->getPath();

		if ($rootPath !== '/')
		{
			$path = str_replace($rootPath, '', $path);
			$path = $path !== '' ? '/' . $path : $path;
		}

		if (substr($path, 0, strlen($prefix)) !== $prefix)
		{
			throw new NotFoundException($request);
		}

		$path = substr($path, strlen($prefix));
		$controller = [];
		$params = [];
		$action = null;

		foreach (array_chunk(explode('/', $path), 2) as $piece)
		{
			$name = explode('-', $piece[0]);
			if ($name !== [])
			{
				$controller[] = implode('', array_map(function($value)
				{
					return ucfirst($value);
				}, $name));
			}
			else
			{
				$controller[] = ucfirst($piece[0]);
			}

			if (isset($piece[1]))
			{
				$piece[0] = str_replace('-', '_', $piece[0]);
				$params[$piece[0] . '_id'] = $piece[1];
				$action = 'resource';
			}
			else
			{
				$action = 'collection';
			}
		}

		foreach ($params as $key => $value)
		{
			$request = $request->withAttribute($key, $value);
		}

		$controller = 'Leftaro\\App\\Controller\\' . implode('\\', $controller) . 'Controller';

		$action = strtolower($request->getMethod()) . ucfirst($action) . 'Action';

		$controllerInstance = $this->container->make($controller);

		$controllerInstance->setRequest($request);

		if ($controllerInstance instanceof AbstractController === false)
		{
			throw new RuntimeException('Invalid controller signature');
		}

		if (!method_exists($controllerInstance, $action))
		{
			throw new NotFoundException($request);
		}

		$response = $controllerInstance->before($request, $response);

		$response = $controllerInstance->$action($request, $response);

		$response = $controllerInstance->after($request, $response);

		if (!$next)
		{
			return $response;
		}

		return $next($request, $response);
	}
}