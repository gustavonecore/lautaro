<?php namespace Leftaro\Core\Exception;

use BadMethodCallException;

/**
 * Exception to handle proper middleware bad definition
 */
class InvalidMiddlewareException extends BadMethodCallException
{
	public function __construct(string $middlewareName)
	{
		parent::__construct('The given middleware ' . $middlewareName . ' is not returning a proper value as response or [request, response]');
	}
}