<?php namespace Leftaro\Core\Console\Generator;

use DI\Container;
use Leftaro\Core\Console\Generator\LeftaroTwigGenerator;
use Leftaro\Core\Console\Generator\GeneratorTrait;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use RuntimeException;

class MakeCommandHandlerCommand extends Command
{
	use GeneratorTrait;

	/**
	 * @var Leftaro\Core\Console\Generator\LeftaroTwigGenerator
	 */
	protected $twig;

	/**
	 * @var DI\Container
	 */
	protected $container;

	const BASE_COMMAND_NAMESPACE = "Leftaro\\App\\Command";
	const BASE_HANDLER_NAMESPACE = "Leftaro\\App\\Handler";

	const BASE_COMMAND_PATH = __DIR__ . '/../../../App/Command/';
	const BASE_HANDLER_PATH = __DIR__ . '/../../../App/Handler/';

	protected static $defaultName = 'make:command';

	/**
	 * Creates the console command
	 *
	 * @param \Leftaro\App\Hex\CommandBus $bus
	 */
	public function __construct(LeftaroTwigGenerator $twig, Container $container)
	{
		parent::__construct();
		$this->twig = $twig;
		$this->container = $container;
	}

	/**
	 * Configure the command
	 *
	 * @return void
	 */
	protected function configure()
	{
		$this->setDescription('Creates a new Command and Handler following a simple hexagonal approach.')
			->setHelp('This command allows you to create a new command/handler')
			->addOption("name", null, InputOption::VALUE_REQUIRED ,'Command name')
			->addOption("description", "d", InputOption::VALUE_REQUIRED ,'Command description')
			->addOption("args", "a", InputOption::VALUE_OPTIONAL, 'Command arguments')
			->addOption("services", "s", InputOption::VALUE_OPTIONAL, 'Handler injected services');
	}

	/**
	 * Interacts with the user.
	 *
	 * This method is executed before the InputDefinition is validated.
	 * This means that this is the only place where the command can
	 * interactively ask for values of missing required arguments.
	 */
	protected function interact(InputInterface $input, OutputInterface $output)
	{
		try
		{
			if (!$input->getOption('name'))
			{
				throw new RuntimeException('Invalid --name option');
			}

			if (!$input->getOption('description'))
			{
				throw new RuntimeException('Invalid --description option');
			}
		}
		catch (RuntimeException $e)
		{
			$output->writeln('<error>' . $e->getMessage());
			exit;
		}
	}

	/**
	 * Execute the command
	 *
	 * @param InputInterface $input
	 * @param OutputInterface $output
	 * @return void
	 */
	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$infoCommand = $this->buildPath($input->getOption('name'), self::BASE_COMMAND_PATH, self::BASE_COMMAND_NAMESPACE);
		$infoHandler = $this->buildPath($input->getOption('name'), self::BASE_HANDLER_PATH, self::BASE_HANDLER_NAMESPACE);

		// Get a map list of type and name from the args list for the command
		$args = array_filter(\explode(',', $input->getOption('args')));
		$args = array_map(function($arg)
		{
			list($type, $name) = explode(':', $arg);
			return [
				'type' => $type,
				'name' => lcfirst($name),
				'function_name' => ucfirst($name),
			];
		}, $args);

		$services = array_filter(\explode(',', $input->getOption('services')));
		$services = array_map(function($service)
		{
			return [
				'namespace' => get_class($this->container->get($service)),
				'varname' => $service,
			];
		}, $services);

		$nameParts = explode("\\", $input->getOption('name'));
		$name = end($nameParts);
		$camelCaseName = $this->camelCaseToSlug($name);
		$varName = lcfirst($name);

		$mergeVars = [
			'name' => $name,
			'namespace' => $infoCommand['namespace'],
			'slugname' => $camelCaseName,
			'varname' => $varName,
			'description' => $input->getOption('description'),
			'args' => $args,
			'args_constructor' => implode(', ', (array_map(function($arg)
			{
				return $arg['type'] . ' $' . $arg['name'];
			}, $args))),
		];

		$commandFile = $infoCommand['path'] . $name . 'Command.php';
		file_put_contents($infoCommand['path'] . $name . 'Command.php', $this->twig->render('command.twig', $mergeVars));

		$mergeVars['namespace'] = $infoHandler['namespace'];
		$mergeVars['command_namespace'] = $infoCommand['namespace'];
		$mergeVars['services'] = $services;
		$mergeVars['services_constructor'] = implode(', ', (array_map(function($service)
		{
			$serviceParts = explode("\\", $service['namespace']);
			$serviceClass = end($serviceParts);
			return $serviceClass . ' $' . $service['varname'];
		}, $services)));

		$handlerFile = $infoHandler['path'] . $name . 'Handler.php';
		file_put_contents($infoHandler['path'] . $name . 'Handler.php', $this->twig->render('handler.twig', $mergeVars));

		if (file_exists($commandFile))
		{
			$output->writeln('<info>' . $infoCommand['namespace'] . "\\" . $name . 'Command was build successfuly</info>');
		}
		else
		{
			$output->writeln('<error>' . $commandFile . ' was not created</error>');
		}

		if (file_exists($handlerFile))
		{
			$output->writeln('<info>' . $infoHandler['namespace'] . "\\" . $name . 'Handler was build successfuly</info>');
		}
		else
		{
			$output->writeln('<error>' . $handlerFile . ' was not created</error>');
		}
	}
}