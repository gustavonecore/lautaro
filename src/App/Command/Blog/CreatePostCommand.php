<?php namespace Leftaro\App\Command\Blog;

class CreatePostCommand
{
	protected $title;
	protected $content;

	public function __construct(string $title, string $content)
	{
		$this->title = $title;
		$this->content = $content;
	}

	public function getTitle() : string
	{
		return $this->title;
	}

	public function getContent() : string
	{
		return $this->content;
	}
}