<?php namespace Leftaro\App\Controller\Api;

use Leftaro\App\Controller\Api\ApiController;
use Leftaro\App\Exception\AuthorizedException;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequest;

/**
 * AuthenticatedController
 */
class AuthenticatedController extends ApiController
{
	/**
	 * {@inheritDoc}
	 */
	public function before(ServerRequest $request, Response $response) : Response
	{
		$response = parent::before($request, $response);

		if (!$this->authenticatedUser)
		{
			throw new AuthorizedException;
		}

		return $response;
	}
}