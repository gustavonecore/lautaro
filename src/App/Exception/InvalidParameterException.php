<?php namespace Leftaro\App\Exception;

use Leftaro\App\Exception\ApiException;

/**
 * Class for invalid parameter errors
 */
class InvalidParameterException extends ApiException
{
	/**
	 * Constructs the exception
	 *
	 * @param string $param  Name of the parameter
	 */
	public function __construct(string $param)
	{
		parent::__construct('Invalid parameter: ' . $param, ApiException::INVALID_PARAMETER);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getHttpCode() : int
	{
		return 400;
	}
}