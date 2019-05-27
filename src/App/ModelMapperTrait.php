<?php namespace Leftaro\App;

use DateTimeInterface;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\Connection\ConnectionInterface;
use Ramsey\Uuid\Uuid;

/**
 * Trait to ensure date format
 */
trait ModelMapperTrait
{
	/**
	 * Format a given date string
	 *
	 * @param mixed $dt  Datetime
	 * @return void
	 */
	public function toW3cDate($dt)
	{
		if ($dt instanceof DateTimeInterface)
		{
			$dt = $dt->format('Y-m-dTH:i:sZ');
		}
		else
		{
			$dt = $dt === null ? null : substr($dt, 0, 10) . 'T' . substr($dt, 11) . 'Z';
		}

		return $dt;
	}

	/**
	 * Search for nested inflators
	 *
	 * @param array $inflators
	 * @param string $parentName
	 * @return array
	 */
	public function getNestedInflators(array $inflators = [], string $parentName) : array
	{
		$nested = array_values(array_filter(array_flip($inflators), function($inflator) use ($parentName)
		{
			return preg_match("/($parentName.)/", $inflator);
		}));

		return array_flip(array_map(function($inflator) use ($parentName)
		{
			return str_replace($parentName . '.', '', $inflator);
		}, $nested));
	}

	/**
	 * Create a new instance from an array
	 *
	 * @param array $fields
	 * @return ActiveRecordInterface
	 */
	public static function create(array $fields) : ActiveRecordInterface
	{
		$instance = new self;

		if (property_exists($instance, 'created_dt'))
		{
			$fields['created_dt'] = !array_key_exists('created_dt', $fields) ? gmdate('Y-m-d H:i:s') : $fields['created_dt'];
		}

		if (property_exists($instance, 'uuid'))
		{
			$fields['uuid'] = Uuid::uuid5(Uuid::NAMESPACE_DNS, implode('', array_values($fields)) . rand(1, 99999999) . microtime());
		}

		$instance->fromArray($fields, $keyType = TableMap::TYPE_FIELDNAME);
		$instance->save();
		return $instance;
	}

	/**
	 * Update the given instance from an array of key/value
	 *
	 * @param array $fields
	 * @return ActiveRecordInterface
	 */
	public static function update(array $fields) : ActiveRecordInterface
	{
		if (property_exists($this, 'update_dt'))
		{
			$fields['update_dt'] = !array_key_exists('update_dt', $fields) ? gmdate('Y-m-d H:i:s') : $fields['update_dt'];
		}

		$this->fromArray($fields, $keyType = TableMap::TYPE_FIELDNAME);
		$this->save();
		return $this;
	}

	/**
	 * Access the propel instance for transaction purposes
	 *
	 * @return ConnectionInterface
	 */
	public static function database() : ConnectionInterface
	{
		return Propel::getWriteConnection('default');
	}

	/**
	 * Get array from object
	 *
	 * @return array
	 */
	public function asArray() : array
	{
		$item = parent::toArray(TableMap::TYPE_FIELDNAME);

		if (array_key_exists('created_dt', $item))
		{
			$item['created_dt'] = $this->toW3cDate($item['created_dt']);
		}

		if (array_key_exists('updated_dt', $item))
		{
			$item['updated_dt'] = $this->toW3cDate($item['updated_dt']);
		}

		if (array_key_exists('modified_dt', $item))
		{
			$item['modified_dt'] = $this->toW3cDate($item['modified_dt']);
		}

		if (array_key_exists('expire_dt', $item))
		{
			$item['expire_dt'] = $this->toW3cDate($item['expire_dt']);
		}

		return $item;
	}
}