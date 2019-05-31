<?php namespace Leftaro\Core\Middleware;

use Exception;
use Leftaro\Core\Exception\ExceptionHandlerInterface;
use Leftaro\Core\Middleware\MiddlewareInterface;
use Leftaro\Core\Middleware\MiddlewareContainer;
use Leftaro\Core\Middleware\CanStopExecutionInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class MiddlewareList
{
	/**
	 * @var \Leftaro\Core\Exception\ExceptionHandlerInterface
	 */
	protected $exceptionHandler;

	/**
	 * @var \Leftaro\Core\Middleware\MiddlewareContainer
	 */
	protected $rootNode;

	/**
	 * @var int
	 */
	protected $count;

	/**
	 * Constructs the class
	 *
	 * @param \Leftaro\Core\Exception\ExceptionHandlerInterface $exceptionHandler
	 */
	public function __construct(ExceptionHandlerInterface $exceptionHandler)
	{
		$this->exceptionHandler = $exceptionHandler;
		$this->count = 0;
		$this->rootNode = null;
	}

	/**
	 * Add middleware to the stack
	 *
	 * @param \Leftaro\Core\Middleware\MiddlewareInterface $middleware
	 * @return void
	 */
	public function add(MiddlewareInterface $middleware)
	{
		$node = new MiddlewareContainer($middleware);

		$current = $this->rootNode;

		if (!$current)
		{
			$this->rootNode = $node;
		}
		else
		{
			while($current)
			{
				if ($current->getNext() === null)
				{
					$current->setNext($node);
					break;
				}

				$current = $current->getNext();
			}
		}

		$this->count++;
	}

	/**
	 * Run the stack of middlewares
	 *
	 * @param \Psr\Http\Message\RequestInterface  $request
	 * @param \Psr\Http\Message\ResponseInterface $response
	 * @return \Psr\Http\Message\ResponseInterface
	 */
	public function run(RequestInterface $request, ResponseInterface $response) : ResponseInterface
	{
		$current = $this->rootNode;

		while ($current !== null)
		{
			$middleware = $current->getMiddleware();

			try
			{
				$response = $middleware($request, $response);
			}
			catch (Exception $e)
			{
				$response = $this->exceptionHandler->getResponse($e, $request, $response);
			}

			if ($middleware instanceof CanStopExecutionInterface && $middleware->shouldStop())
			{
				$current = null;
				break;
			}

			$current = $current->getNext();
		}

		return $response;
	}

	/**
	 * Get number of items
	 *
	 * @return integer
	 */
	public function getCount() : int
	{
		return $this->count;
	}

	/**
	 * Destruct the class
	 */
	public function __destruct()
	{
		$this->rootNode = null;
	}
}