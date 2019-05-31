<?php namespace Leftaro\App\Middleware;

use Leftaro\Core\Middleware\MiddlewareInterface;
use Leftaro\Core\Middleware\CanStopExecutionInterface;
use Leftaro\Core\LoggerAwareInterface;
use Leftaro\Core\LoggerAwareTrait;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class OptionsHeaderMiddleware implements MiddlewareInterface, LoggerAwareInterface, CanStopExecutionInterface
{
	use LoggerAwareTrait;

	protected $shouldStop = false;

	/**
	 * {@inheritDoc}
	 */
	public function shouldStop() : bool
	{
		return $this->shouldStop;
	}

	/**
	 * {@inheritDoc}
	 */
	public function __invoke(RequestInterface $request, ResponseInterface $response) : ResponseInterface
	{
		if ($request->getMethod() === 'OPTIONS')
		{
			$this->logger->debug('Request/Response', [
				'request' => \Zend\Diactoros\Request\ArraySerializer::toArray($request),
				'response' => \Zend\Diactoros\Response\ArraySerializer::toArray($response),
			]);

			$response = $response->
				withHeader('Access-Control-Allow-Origin', '*')->
				withHeader('Access-Control-Allow-Methods', 'POST, GET, OPTIONS, PUT, DELETE, PATCH')->
				withHeader('Allow', 'POST, GET, OPTIONS, PUT, DELETE, PATCH')->
				withHeader('Access-Control-Allow-Headers', 'Origin, Content-Type, Accept, Authorization, X-Requested-With, x-access-token, Access-Control-Request-Method');

			$this->shouldStop = true;
		}

		return $response;
	}
}