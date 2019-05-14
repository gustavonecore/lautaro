<?php namespace Leftaro\App;

use Leftaro\App\Exception\ResourceNotFoundException;
use Propel\Runtime\Exception\EntityNotFoundException;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;

/**
 * Trait to override methods
 */
trait ModelQueryTrait
{
	/**
	 * Function to overwrite the behavior of the orm method to popup a custom exception
	 *
	 * @param string $id
	 * @throws ResourceNotFoundException
	 */
	public function getOneById($id)
	{
		try
		{
			return $this->requireOneById($id);
		}
		catch (EntityNotFoundException $e)
		{
			throw new ResourceNotFoundException(($this->getTableMap())->getName() . ' with id ' . $id);
		}
	}

	/**
	 * Get one record or fail
	 *
	 * @param string $field    Field name to filter
	 * @param mixed  $value    Value
	 * @throws ResourceNotFoundException For not found record
	 * @return ActiveRecordInterface
	 */
	public function getOneBy(string $field, $value) : ActiveRecordInterface
	{
		try
		{
			$requireByField = 'requireOneBy' . ucfirst($field);

			return $this->$requireByField($value);
		}
		catch (EntityNotFoundException $e)
		{
			throw new ResourceNotFoundException(($this->getTableMap())->getName() . ' with id ' . $id);
		}
	}
}