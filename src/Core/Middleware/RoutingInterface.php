<?php namespace Leftaro\Core\Middleware;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

interface RoutingInterface
{
	/**
	 * Handle the middleware call for request and response approach
	 *
	 * @param  \Psr\Http\Message\RequestInterface    $request   Request instance
	 * @param  \Psr\Http\Message\ResponseInterface   $response  Response instance
	 *
	 * @return \Psr\Http\Message\ResponseInterface
	 */
	public function getResponse(RequestInterface $request, ResponseInterface $response) : ResponseInterface;
}