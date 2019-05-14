<?php namespace Leftaro\App\Handler\Util;

use InvalidArgumentException;
use Leftaro\App\Handler\HandlerInterface;
use Leftaro\App\Command\Util\GetEntityByCommand;

/**
 * Handle the command
 */
class GetEntityByHandler implements HandlerInterface
{
	const NAMESPACE = 'Leftaro\\App\\Model\\';

	/**
	 * Process command
	 *
	 * @param GetEntityByCommand $command
	 * @return void
	 */
	public function handle(GetEntityByCommand $command)
	{
		$className = $self::getClassName($command->getEntity());

		if (!class_exists($className))
		{
			throw new InvalidArgumentException('Class for ' . $className . ' does not exists');
		}

		$findOneByField = 'findOneBy' . ucfirst($command->getField());

		return ($className::create())->$findOneByField($command->getValue());
	}

	/**
	 * Get class name
	 *
	 * @param string $entity  Entity name
	 * @return string
	 */
	public static function getClassName(string $entity) : string
	{
		return self::NAMESPACE . ucfirst(strtolower(str_replace('_', '', $entity))) . 'Query';
	}
}