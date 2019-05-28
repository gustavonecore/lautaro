<?php namespace Leftaro\Core\Exception;

use Exception;
use DI\NotFoundException as DINotFoundException;
use Leftaro\Core\Exception\MethodNotAllowedException;
use Leftaro\Core\Exception\NotFoundException;
use Leftaro\Core\Exception\LeftaroException;
use Leftaro\Core\ContainerAwareInterface;
use Leftaro\Core\ContainerAwareTrait;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\RequestInterface;
use Zend\Diactoros\Response\TextResponse;
use Zend\Diactoros\Response\JsonResponse;

/**
 * Base class to global handle exceptions
 */
class ExceptionHandler implements ExceptionHandlerInterface, ContainerAwareInterface
{
	use ContainerAwareTrait;

	/**
	 * {@inheritDoc}
	 */
	public function getResponse(Exception $e, RequestInterface $request = null) : ResponseInterface
	{
		if ($e instanceof MethodNotAllowedException)
		{
            return new TextResponse('Method not allowed', 405);
		}
		elseif ($e instanceof NotFoundException)
		{
			return new TextResponse('Resource not found for ' . $e->getRequest()->getUri()->getPath(), 404);
		}
		elseif ($e instanceof DINotFoundException)
		{
			return new TextResponse('Path not found for ' . $request->getUri()->getPath(), 404);
		}
		elseif ($e instanceof LeftaroException)
		{
            return new TextResponse($e->getMessage(), 501);
		}
		else
		{
			// For non production environments we just throw the error
			if ($this->container->get('config')->get('env') !== 'production')
			{
				throw $e;
			}

			$verbose = $this->container->get('config')->get('unknown_error_verbose') === 'verbose' ? '. Trace: ' . $e->getTraceAsString() : '';

			$this->container->get('logger')->error('Unhandled ' . get_class($e) . '. Detail ' . $e->getMessage() . $verbose);

			return new JsonResponse(['error' => 'Unknown error'], 500);
		}
	}
}