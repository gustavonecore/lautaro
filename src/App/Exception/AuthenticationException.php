<?php namespace Leftaro\App\Exception;

use Leftaro\App\Exception\ApiException;

/**
 * Class for auth errors
 */
class AuthenticationException extends ApiException
{
	/**
	 * Constructs the exception
	 *
	 * @param string $param  Name of the parameter
	 */
	public function __construct()
	{
		parent::__construct('Invalid authentication', ApiException::NOT_AUTHORIZED);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getHttpCode() : int
	{
		return 403;
	}
}