<?php namespace Leftaro\Core\Middleware;

use Exception;
use Leftaro\Core\Exception\InvalidMiddlewareException;
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
	 * @var \Leftaro\Core\Middleware\MiddlewareContainer
	 */
	protected $lastNode;

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
		$this->rootNode = new MiddlewareContainer(new DefaultMiddleware);
		$this->count = 1;
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
		$pointer = $this->getCount() === 1 ? $this->rootNode : $this->lastNode;
		$pointer->setNext($node);
		$this->lastNode = $node;
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
				$output = $middleware($request, $response);

				if ($output instanceof ResponseInterface)
				{
					$response = $output;
				}
				else if (is_array($output) && count($output) === 2 && $output[0] instanceof RequestInterface && $output[1] instanceof ResponseInterface)
				{
					list($request, $response) = $output;
				}
				else
				{
					throw new InvalidMiddlewareException(get_class($middleware));
				}
			}
			catch (InvalidMiddlewareException $e)
			{
				throw $e;
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