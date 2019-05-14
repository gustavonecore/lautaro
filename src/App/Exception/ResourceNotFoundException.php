<?php namespace Leftaro\App\Exception;

use Leftaro\App\Exception\ApiException;

/**
 * Class for not found errors
 */
class ResourceNotFoundException extends ApiException
{
	/**
	 * Constructs the exception
	 *
	 * @param string $resource  Resource description
	 */
	public function __construct(string $resource)
	{
		parent::__construct('Resource not found for ' . $resource, ApiException::RESOURCE_NOT_FOUND);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getHttpCode() : int
	{
		return 404;
	}
}