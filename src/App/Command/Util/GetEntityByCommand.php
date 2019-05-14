<?php namespace Leftaro\App\Command\Util;

use Leftaro\App\Command\CommandInterface;

/**
 * Command to fetching one record of any entity
 */
class GetEntityByCommand implements CommandInterface
{
	/**
	 * @var string  Entity name
	 */
	protected $entity;

	/**
	 * @var string  Field
	 */
	protected $field;

	/**
	 * @var mixed   Value
	 */
	protected $value;

	/**
	 * Constructs the command
	 *
	 * @param string $entity   Entity name
	 * @param mixed  $field    Field name
	 * @param string $value    Value
	 */
	public function __construct(string $entity, string $field, string $value)
	{
		$this->entity = $entity;
		$this->field = $field;
		$this->value = $value;
	}

	/**
	 * Get entity
	 *
	 * @return string
	 */
	public function getEntity() : string
	{
		return $this->entity;
	}

	/**
	 * Get field
	 *
	 * @return string
	 */
	public function getField() : string
	{
		return $this->field;
	}

	/**
	 * Get value
	 *
	 * @return mixed
	 */
	public function getValue()
	{
		return $this->value;
	}
}