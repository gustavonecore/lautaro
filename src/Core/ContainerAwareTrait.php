<?php namespace Leftaro\Core;

use Psr\Container\ContainerInterface;

trait ContainerAwareTrait
{
	/**
	 * @var \Psr\Container\ContainerInterface
	 */
	protected $container;

	/**
	 * {@inheritDoc}
	 */
	public function setContainer(ContainerInterface $container)
	{
		$this->container = $container;
	}
}