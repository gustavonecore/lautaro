<?php namespace Leftaro\App\Controller\Api;

use Leftaro\App\Controller\Api\ApiController;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequest;
use Leftaro\App\Model\Post;
/**
 * Post controller
 */
class PostController extends ApiController
{
	/**
	 * Create a new ot
	 *
	 * @param ServerRequest $request
	 * @param Response $response
	 * @return Response
	 */
	public function postCollectionAction(ServerRequest $request, Response $response) : Response
	{
		$input = $this->verify([
			'title' => 'string',
			'content' => 'string',
		], $request->getParsedBody(), ['title', 'content']);

		$post = Post::create([
			'title' => $input['title'],
			'content' => $input['content'],
		]);

		return $this->json([
			'data' => [
				'post' => $post->asArray(),
			],
		]);
	}
}