<?php namespace Leftaro\App\Exception;

use Leftaro\App\Exception\ApiException;

/**
 * Class for not allowed action for the account
 */
class NotAllowedExternalTransactionsException extends ApiException
{
	/**
	 * Constructs the exception
	 *
	 * @param string $account  account
	 */
	public function __construct(string $account)
	{
		parent::__construct('Not allowed external transactions for the account ' . $account, ApiException::NOT_ALLOWED_EXTERNAL_TRANSACTIONS);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getHttpCode() : int
	{
		return 403;
	}
}