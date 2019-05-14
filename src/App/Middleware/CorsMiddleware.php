<?php namespace Leftaro\App\Middleware;

use Leftaro\Core\Middleware\MiddlewareInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class CorsMiddleware implements MiddlewareInterface
{
	/**
	 * {@inheritDoc}
	 */
	public function __invoke(RequestInterface $request, ResponseInterface $response, callable $next = null) : ResponseInterface
	{
		error_log("__invoke CorsMiddleware");

		$requiredHeaders = $request->hasHeader('Access-Control-Request-Headers') ? $request->getHeader('Access-Control-Request-Headers')[0] : '*';

		$response = $response->withHeader('Access-Control-Allow-Methods', 'HEAD, GET, POST, PUT, PATCH, DELETE, OPTIONS');
		$response = $response->withHeader('Access-Control-Allow-Headers', $requiredHeaders);
		$response = $response->withHeader('Access-Control-Allow-Origin', '*');

		return $next($request, $response);
	}
}