<?php namespace Leftaro\App\Exception;

use Leftaro\App\Exception\ApiException;

/**
 * Class for transaction between entities
 */
class TransactionBetweenEntitiesException extends ApiException
{
	/**
	 * Constructs the exception
	 */
	public function __construct()
	{
		parent::__construct('Not allowed transactions between entities ', ApiException::TRANSACTION_BETWEEN_ENTITIES);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getHttpCode() : int
	{
		return 403;
	}
}