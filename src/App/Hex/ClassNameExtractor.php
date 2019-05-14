<?php namespace Leftaro\App\Hex;

use League\Tactician\Handler\CommandNameExtractor\CommandNameExtractor;

/**
 * Extract the name from the class
 */
class ClassNameExtractor implements CommandNameExtractor
{
	/**
	 * {@inheritdoc}
	 */
	public function extract($command)
	{
		return get_class($command);
	}
}
