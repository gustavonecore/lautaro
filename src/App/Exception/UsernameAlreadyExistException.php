<?php namespace Leftaro\App\Exception;

use Leftaro\App\Exception\ApiException;

/**
 * Class for username already exists errors
 */
class UsernameAlreadyExistException extends ApiException
{
	/**
	 * Constructs the exception
	 *
	 * @param string $username  username
	 */
	public function __construct(string $username)
	{
		parent::__construct('Username already exist: ' . $username, ApiException::USERNAME_ALREADY_EXIST);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getHttpCode() : int
	{
		return 403;
	}
}