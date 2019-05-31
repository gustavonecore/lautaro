<?php namespace Leftaro\Core\Middleware;

interface CanStopExecutionInterface
{
	/**
	 * Determine if the middleware should stop the stack execution before him
	 *
	 * @return boolean
	 */
    public function shouldStop() : bool;
}