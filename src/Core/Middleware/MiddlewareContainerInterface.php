<?php namespace Leftaro\Core\Middleware;

use Leftaro\Core\Middleware\MiddlewareInterface;

interface MiddlewareContainerInterface
{
	/**
	 * Get the next middleware in the stack
	 *
	 * @return MiddlewareContainerInterface|null
	 */
	public function getNext() : ?MiddlewareContainerInterface;

	/**
	 * Set the next middleware
	 *
	 * @param MiddlewareContainerInterface $mContainer
	 * @return void
	 */
	public function setNext(MiddlewareContainerInterface $mContainer);

	/**
	 * Get related middleware
	 *
	 * @return MiddlewareInterface
	 */
	public function getMiddleware() : MiddlewareInterface;
}