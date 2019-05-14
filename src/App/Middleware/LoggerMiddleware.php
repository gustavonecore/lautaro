<?php namespace Leftaro\App\Middleware;

use Leftaro\Core\Middleware\MiddlewareInterface;
use Leftaro\Core\LoggerAwareInterface;
use Leftaro\Core\LoggerAwareTrait;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class LoggerMiddleware implements MiddlewareInterface, LoggerAwareInterface
{
	use LoggerAwareTrait;

	/**
	 * {@inheritDoc}
	 */
	public function __invoke(RequestInterface $request, ResponseInterface $response, callable $next = null) : ResponseInterface
	{
		error_log("__invoke LoggerMiddleware");

		$this->logger->error("Request " . $request->getMethod() . " " . (string)$request->getUri() . " in: " . (string)$request->getBody() . ', query: ' . print_r($request->getQueryParams(), true));
		$this->logger->error("Response " . (string)$response->getBody());

		return $next($request, $response);
	}
}