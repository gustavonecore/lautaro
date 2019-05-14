<?php namespace Leftaro\Core\Exception;

use Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\RequestInterface;

/**
 * Interface to define a protocol to handle exceptions and convert to http responses
 */
interface ExceptionHandlerInterface
{
	/**
	 * Get a PSR response instance from the given exception
	 *
	 * @param Exception $e
	 * @param RequestInterface $request
	 * @return ResponseInterface
	 */
	public function getResponse(Exception $e, RequestInterface $request = null) : ResponseInterface;

}