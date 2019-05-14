<?php namespace Leftaro\App\Handler\Util;

use InvalidArgumentException;
use Leftaro\App\Handler\HandlerInterface;
use Leftaro\App\Command\Util\GetEntityOrFailCommand;
use Leftaro\App\Exception\ResourceNotFoundException;
use Propel\Runtime\Exception\EntityNotFoundException;

/**
 * Handle the command
 */
class GetEntityOrFailHandler implements HandlerInterface
{
	/**
	 * Process command
	 *
	 * @param GetEntityOrFailCommand $command
	 * @return void
	 */
	public function handle(GetEntityOrFailCommand $command)
	{
		$className = GetEntityByHandler::getClassName($command->getEntity());

		if (!class_exists($className))
		{
			throw new InvalidArgumentException('Class for ' . $className . ' does not exists');
		}

		try
		{
			$requireOneBy = 'requireOneBy' . ucfirst($command->getField());

			$query = $className::create();

			return $query->$requireOneBy($command->getValue());
		}
		catch (EntityNotFoundException $e)
		{
			throw new ResourceNotFoundException($query->getTableMap()->getName() . ' with ' . $command->getField() . ' ' . $command->getValue());
		}
	}
}