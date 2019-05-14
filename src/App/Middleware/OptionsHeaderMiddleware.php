<?php namespace Leftaro\App\Middleware;

use Leftaro\Core\Middleware\MiddlewareInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class OptionsHeaderMiddleware implements MiddlewareInterface
{
	/**
	 * {@inheritDoc}
	 */
	public function __invoke(RequestInterface $request, ResponseInterface $response, callable $next = null) : ResponseInterface
	{
		if ($request->getMethod() === 'OPTIONS')
		{
			$requiredHeaders = $request->hasHeader('Access-Control-Request-Headers') ? $request->getHeader('Access-Control-Request-Headers')[0] : '*';
			$response = $response->withHeader('Access-Control-Allow-Methods', 'HEAD, GET, POST, PUT, PATCH, DELETE, OPTIONS');
			$response = $response->withHeader('Access-Control-Allow-Headers', $requiredHeaders);
			$response = $response->withHeader('Access-Control-Allow-Origin', '*');
			return $response;
		}

		return $next($request, $response);
	}
}