<?php namespace Leftaro\App\Exception;

use Leftaro\App\Exception\ApiException;

/**
 * Class for already existing accounts
 */
class AccountAlreadyExistException extends ApiException
{
	/**
	 * Constructs the exception
	 *
	 * @param string $accountInfo  Account info
	 */
	public function __construct(string $accountInfo)
	{
		parent::__construct('Account already exists for: ' . $accountInfo, ApiException::ACCOUNT_ALREADY_EXIST);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getHttpCode() : int
	{
		return 403;
	}
}