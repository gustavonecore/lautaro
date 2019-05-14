<?php namespace Leftaro\App\Mapper;

use DI\Container;

abstract class AbstractMapper
{
	protected $bus;

	protected $container;

	public function __construct(Container $container)
	{
		$this->container = $container;
		$this->bus = $container->get('bus');
	}

	/**
	 * Map method
	 *
	 * @param mixed $item
	 * @param array $options
	 * @return array
	 */
	abstract function map($item, array $options = []) : array;

	/**
	 * Map a collection
	 *
	 * @param mixed $collection
	 * @param array $options
	 * @return array
	 */
	public function mapCollection($collection, array $options = []) : array
	{
		return array_map(function($item) use($options)
		{
			return $this->map($item, $options);
		}, $collection);
	}
}