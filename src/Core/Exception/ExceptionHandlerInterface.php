<?php namespace Leftaro\Core\Exception;

use Exception;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Interface to define a protocol to handle exceptions and convert to http responses
 */
interface ExceptionHandlerInterface
{
	/**
	 * Get a PSR response instance from the given exception
	 *
	 * @param Exception $e
	 * @param \Psr\Http\Message\RequestInterface  $request
	 * @param \Psr\Http\Message\ResponseInterface $response
	 * @return ResponseInterface
	 */
	public function getResponse(Exception $e, RequestInterface $request, ResponseInterface $response) : ResponseInterface;
}