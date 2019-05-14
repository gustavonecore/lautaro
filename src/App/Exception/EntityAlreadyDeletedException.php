<?php namespace Leftaro\App\Exception;

use Leftaro\App\Exception\ApiException;

/**
 * Class for already deleted entity errors
 */
class EntityAlreadyDeletedException extends ApiException
{
	/**
	 * Constructs the exception
	 *
	 * @param string $entity  entity
	 */
	public function __construct(string $entity)
	{
		parent::__construct('Entity already deleted for: ' . $entity, ApiException::ENTITY_ALREADY_DELETED);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getHttpCode() : int
	{
		return 403;
	}
}