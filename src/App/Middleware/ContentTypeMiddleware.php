<?php namespace Leftaro\App\Middleware;

use Leftaro\Core\Middleware\MiddlewareInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class ContentTypeMiddleware implements MiddlewareInterface
{
	/**
	 * {@inheritDoc}
	 */
	public function __invoke(RequestInterface $request, ResponseInterface $response) : ResponseInterface
	{
		if ($request->hasHeader('content-type') && ($request->getHeaderLine('content-type') === 'application/json' ||
			$request->getHeaderLine('content-type') === 'application/json; charset=utf-8'))
		{
			$input = json_decode($request->getBody(), true);
			$input = !is_array($input) ? [] : $input;

			$request = $request->withParsedBody($input);
			$request = $request->withAttribute('is_ajax', true);
		}

		return $response;
	}
}