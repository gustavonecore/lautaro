<?php namespace Leftaro\App\Middleware;

use Gcore\Sanitizer\Template\TemplateSanitizer;
use Leftaro\Core\Middleware\MiddlewareInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class InflatorsMiddleware implements MiddlewareInterface
{
	const INFLATOR_NAME = 'inflators';

	/**
	 * {@inheritDoc}
	 */
	public function __invoke(RequestInterface $request, ResponseInterface $response, callable $next = null) : ResponseInterface
	{
		$query = (new TemplateSanitizer(['inflators' => 'string']))->sanitize($request->getQueryParams());

		$query['inflators'] = ($query['inflators'] === null) ? '' : $query['inflators'];

		$inflators = array_flip(array_values(array_filter(explode(',', $query['inflators']))));

		$request = $request->withAttribute('inflators', $inflators);

		return $next($request, $response);
	}
}