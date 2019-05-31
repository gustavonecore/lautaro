<?php namespace Leftaro\Core\Middleware;

use Leftaro\Core\Middleware\MiddlewareInterface;
use Leftaro\Core\Middleware\MiddlewareContainerInterface;

class MiddlewareContainer implements MiddlewareContainerInterface
{
	/**
	 * @var MiddlewareContainerInterface
	 */
	protected $next;

	/**
	 * @var MiddlewareInterface
	 */
	protected $middleware;

	/**
	 * Constructs the middleware container
	 *
	 * @param MiddlewareInterface $middleware
	 */
	public function __construct(MiddlewareInterface $middleware)
	{
		$this->middleware = $middleware;
		$this->next = null;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getNext() : ?MiddlewareContainerInterface
	{
		return $this->next;
	}

	/**
	 * {@inheritDoc}
	 */
	public function setNext(MiddlewareContainerInterface $mContainer)
	{
		$this->next = $mContainer;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getMiddleware() : MiddlewareInterface
	{
		return $this->middleware;
	}
}