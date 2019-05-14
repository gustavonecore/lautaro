<?php namespace Leftaro\App\Exception;

use Leftaro\App\Exception\ApiException;

/**
 * Class for invalid slug errors
 */
class SlugAlreadyExistsException extends ApiException
{
	/**
	 * Constructs the exception
	 *
	 * @param string $title  title
	 */
	public function __construct(string $title)
	{
		parent::__construct('Slug already exists for: ' . $title, ApiException::SLUG_ALREADY_EXISTS);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getHttpCode() : int
	{
		return 403;
	}
}