<?php namespace Leftaro\App\Middleware;

use Leftaro\Core\Middleware\MiddlewareInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class CorsMiddleware implements MiddlewareInterface
{
	/**
	 * {@inheritDoc}
	 */
	public function __invoke(RequestInterface $request, ResponseInterface $response) : ResponseInterface
	{
		return $response->
			withHeader('Access-Control-Allow-Origin', '*')->
			withHeader('Access-Control-Allow-Methods', 'POST, GET, OPTIONS, PUT, DELETE, PATCH')->
			withHeader('Allow', 'POST, GET, OPTIONS, PUT, DELETE, PATCH')->
			withHeader('Access-Control-Allow-Headers', 'Origin, Content-Type, Accept, Authorization, X-Requested-With, x-access-token, Access-Control-Request-Method');
	}
}