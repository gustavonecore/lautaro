<?php namespace Leftaro\Core;

use Psr\Log\LoggerInterface;

trait LoggerAwareTrait
{
	/**
	 * @var  \Psr\Log\LoggerInterface  $logger  Logger instance
	 */
	protected $logger;

	/**
	 * {@inheritDoc}
	 */
	public function setLogger(LoggerInterface $logger)
	{
		$this->logger = $logger;
	}
}