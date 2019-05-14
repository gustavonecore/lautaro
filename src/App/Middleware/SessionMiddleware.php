<?php namespace Leftaro\App\Middleware;

use Leftaro\Core\Middleware\MiddlewareInterface;
use Leftaro\Core\ContainerAwareTrait;
use Leftaro\Core\ContainerAwareInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response\RedirectResponse;

class SessionMiddleware implements MiddlewareInterface, ContainerAwareInterface
{
	use ContainerAwareTrait;

	const AUTHORIZED_PATHS = [
		'/auth' => true,
		'/auth/login' => true,
	];

	/**
	 * {@inheritDoc}
	 */
	public function __invoke(RequestInterface $request, ResponseInterface $response, callable $next = null) : ResponseInterface
	{
		error_log("__invoke SessionMiddleware");

		/*
		if (!$this->isApiRequest($request))
		{
			if (!isset(self::AUTHORIZED_PATHS[$request->getUri()->getPath()]))
			{
				if (!$this->container->get('session')->has('authenticated_user'))
				{
					return new RedirectResponse('/auth');
				}
			}
		}

		// TODO: check ACL permissions here

		*/
		return $next($request, $response);
	}

	/**
	 * Test if the given request it's an api call
	 * @param  \Psr\Http\Message\RequestInterface    $request   Request instance
	 *
	 * @return bool
	 */
	private function isApiRequest(RequestInterface $request) : bool
	{
		return strpos($request->getUri()->getPath(), 'api') > 0;
	}
}