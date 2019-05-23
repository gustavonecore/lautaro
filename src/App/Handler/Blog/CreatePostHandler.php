<?php namespace Leftaro\App\Handler\Blog;

use Leftaro\App\Command\Blog\CreatePostCommand;
use Leftaro\App\Model\Post;

class CreatePostHandler
{
	/**
	 * Process command
	 *
	 * @param CreatePostCommand $command
	 * @return Post
	 */
	public function handle(CreatePostCommand $command)
	{
		return Post::create([
			'title' => $command->getTitle(),
			'content' => $command->getContent(),
		]);
	}
}