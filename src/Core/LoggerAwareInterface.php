<?php namespace Leftaro\Core;

use Psr\Log\LoggerInterface;

interface LoggerAwareInterface
{
	/**
	 * Set the logger
	 *
	 * @param \Psr\Log\LoggerInterface $logger
	 * @return void
	 */
	public function setLogger(LoggerInterface $logger);
}