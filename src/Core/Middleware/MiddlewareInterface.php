<?php namespace Leftaro\Core\Middleware;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

interface MiddlewareInterface
{
	/**
	 * Handle the middleware call for request and response approach
	 *
	 * @param  \Psr\Http\Message\RequestInterface    $request   Request instance
	 * @param  \Psr\Http\Message\ResponseInterface   $response  Response instance
	 * @return \Psr\Http\Message\ResponseInterface | [\Psr\Http\Message\RequestInterface, \Psr\Http\Message\ResponseInterface]
	 */
	public function __invoke(RequestInterface $request, ResponseInterface $response);
}