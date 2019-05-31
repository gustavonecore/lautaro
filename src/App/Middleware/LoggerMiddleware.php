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
	public function __invoke(RequestInterface $request, ResponseInterface $response) : ResponseInterface
	{
		$this->logger->debug('Request/Response', [
            'request' => \Zend\Diactoros\Request\ArraySerializer::toArray($request),
            'response' => \Zend\Diactoros\Response\ArraySerializer::toArray($response),
		]);

		return $response;
	}
}