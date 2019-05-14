<?php namespace Leftaro\Core;

use Psr\Container\ContainerInterface;

interface ContainerAwareInterface
{
	/**
	 * Set the container
	 *
	 * @param \Psr\Container\ContainerInterface $container
	 * @return void
	 */
	public function setContainer(ContainerInterface $container);
}