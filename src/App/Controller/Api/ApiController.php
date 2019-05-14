<?php namespace Leftaro\App\Controller\Api;

use Leftaro\App\Controller\BaseController;
use Leftaro\App\Exception\ResourceNotFoundException;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequest;

/**
 * API base controller
 */
class ApiController extends BaseController
{
	const API_VERSION = 'v1';

	/**
	 * @var User data
	 */
	protected $authenticatedUser;

	/**
	 * @var array List of inflators
	 */
	protected $inflators;

	/**
	 * {@inheritDoc}
	 */
	public function before(ServerRequest $request, Response $response) : Response
	{
		$response = parent::before($request, $response);

		$apiVersion = $request->getAttribute('api_id');

		if (!$apiVersion || $apiVersion !== self::API_VERSION)
		{
			throw new ResourceNotFoundException('api version [' . (string)$apiVersion . ']');
		}

		$this->authenticatedUser = $request->getAttribute('authenticated_user');
		$this->inflators = $request->getAttribute('inflators');

		return $response;
	}
}