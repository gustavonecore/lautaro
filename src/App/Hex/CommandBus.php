<?php namespace Leftaro\App\Hex;

use Interop\Container\ContainerInterface;
use League\Tactician\CommandBus as TacticianCommandBus;
use Leftaro\App\Command\CommandInterface;
use RuntimeException;
use ReflectionClass;


/**
 * Command bus
 */
class CommandBus extends TacticianCommandBus
{
    /**
     * @var  \Interop\Container\ContainerInterface Container
     */
	private $container;

	/**
     * {@inheritDoc}
     */
    public function __construct(array $middlewares, ContainerInterface $container)
    {
		parent::__construct($middlewares);

		$this->container = $container;
	}

	/**
	 * Handles a command
	 *
	 * @param CommandInterface $command
	 * @return mixed
	 */
	public function handle(CommandInterface $command)
	{
		return $this->handle($command);
	}

	/**
	 * Magic method to call any command
	 *
	 * @param string $name      Command name
	 * @param array  $params    List of params
	 * @return void
	 */
	public function __call(string $name, array $params)
	{
		$command = $this->createCommand($name, $params);

		$this->container->get('logger')->debug('INPUT '  . $name . ' :' . print_r($params, true));

		$output = $this->handle($command);

		//$this->container->get('logger')->debug('OUTPUT '  . $name . ' :' . print_r($output, true));

		return $output;
	}

	/**
	 * Method to create an instance of the requested command by its name
	 *
	 * @param  string $name       Command name
	 * @param  array  $params     List of parameters needed by the command
	 */
	public function createCommand(string $name, array $params = [])
	{
		$namespaces = $this->container->get('config')->get('command_namespaces');

		$reflector = null;

		$partialCommandName = ucfirst($name) . 'Command';

		$commandName = '';

		foreach ($namespaces as $namespace)
		{
			$commandName = $namespace . $partialCommandName;

			if (class_exists($commandName))
			{
				$reflector = new ReflectionClass($commandName);
				break;
			}
		}

		if ($reflector === null)
		{
			throw new RuntimeException('Command not found: ' . $commandName);
		}

		return $reflector->newInstanceArgs($params);
	}
}
