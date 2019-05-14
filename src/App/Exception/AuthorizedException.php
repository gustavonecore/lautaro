<?php namespace Leftaro\App\Exception;

use Leftaro\App\Exception\ApiException;

/**
 * Class for not authorized errors
 */
class AuthorizedException extends ApiException
{
	/**
	 * Constructs the exception
	 *
	 * @param string $param  Name of the parameter
	 */
	public function __construct(string $info = null)
	{
		$message = is_null($info) ? 'Not authorized' : 'Not authorized action. ' . $info;

		parent::__construct($message, ApiException::NOT_AUTHORIZED);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getHttpCode() : int
	{
		return 403;
	}
}