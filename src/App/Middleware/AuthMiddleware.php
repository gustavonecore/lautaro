<?php namespace Leftaro\App\Middleware;

use Leftaro\Core\Middleware\MiddlewareInterface;
use Leftaro\App\Exception\AuthorizedException;
use Leftaro\App\Model\TokenQuery;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class AuthMiddleware implements MiddlewareInterface
{
	/**
	 * {@inheritDoc}
	 */
	public function __invoke(RequestInterface $request, ResponseInterface $response, callable $next = null) : ResponseInterface
	{
		error_log("__invoke AuthMiddleware");

		$accessToken = null;

		if (isset($request->getQueryParams()['access_token']) === true)
		{
			$accessToken = $request->getQueryParams()['access_token'];
		}
		else if ($request->hasHeader('x-access-token') === true)
		{
			$accessToken = $request->getHeader('x-access-token')[0];
		}

		if ($accessToken)
		{
			$request = $request->withAttribute('access_token', $accessToken);

			$token = TokenQuery::create()->requireOneByToken($accessToken);

			if ($token->isExpired())
			{
				throw new AuthorizedException;
			}

			$expireDt = clone $token->getExpireDt();

			$token->setExpireDt($expireDt->modify('+2 weeks'));
			$token->save();

			$user = $token->getUser();
			$user->computed_token_id = $token->getId();
			$user->computed_token_hash = $accessToken;

			$request = $request->withAttribute('authenticated_user', $user);
		}

		return $next($request, $response);
	}
}