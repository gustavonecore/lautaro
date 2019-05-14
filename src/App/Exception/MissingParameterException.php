<?php namespace Leftaro\App\Exception;

use Leftaro\App\Exception\ApiException;

/**
 * Class for missing parameter errors
 */
class MissingParameterException extends ApiException
{
	/**
	 * @var array List of fields
	 */
	protected $params = [];

	/**
	 * Constructs the exception
	 *
	 * @param string|array $param  Name or list of missing parameter(s)
	 */
	public function __construct($param)
	{
		if (is_array($param))
		{
			$this->params = $param;
		}
		else if (is_string($param))
		{
			$this->params[] = $param;
		}
		else
		{
			throw new \InvalidArgumentException('Invalid value for MissingParameterException class');
		}

		parent::__construct('Missing parameter(s): ' . implode(',', $this->params), ApiException::INVALID_PARAMETER);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getHttpCode() : int
	{
		return 400;
	}

	/**
	 * Get the params list
	 *
	 * @return array
	 */
	public function getParams() : array
	{
		return $this->params;
	}
}