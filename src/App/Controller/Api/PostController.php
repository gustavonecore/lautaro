<?php namespace Leftaro\App\Controller\Api;

use Leftaro\App\Controller\Api\ApiController;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequest;
/**
 * Post controller
 */
class PostController extends ApiController
{
	/**
	 * Create a new post
	 *
	 * @param ServerRequest $request
	 * @param Response $response
	 * @return Response
	 */
	public function postCollectionAction(ServerRequest $request, Response $response) : Response
	{
		$input = $this->verify([
			'title' => 'string!',
			'content' => 'string!',
		], $request->getParsedBody());

		return $this->json([
			'data' => [
				'post' => [],
			],
		]);
	}
}