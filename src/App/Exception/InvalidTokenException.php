<?php namespace Leftaro\App\Exception;

use Leftaro\App\Exception\ApiException;

/**
 * Class for invalid token error
 */
class InvalidTokenException extends ApiException
{
	/**
	 * @var string  Token
	 */
	protected $token;

	/**
	 * Constructs the exception
	 *
	 * @param string $token  Token
	 */
	public function __construct(string $token)
	{
		$this->token = $token;

		parent::__construct('Invalid token ' . $token, ApiException::INVALID_TOKEN);
	}

	/**
	 * Return the token
	 *
	 * @return string
	 */
	public function getToken() : string
	{
		return $this->token;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getHttpCode() : int
	{
		return 400;
	}
}