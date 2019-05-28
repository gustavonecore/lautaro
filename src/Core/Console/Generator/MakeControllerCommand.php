<?php namespace Leftaro\Core\Console\Generator;

use Leftaro\Core\Console\Generator\LeftaroTwigGenerator;
use Leftaro\Core\Console\Generator\GeneratorTrait;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class MakeControllerCommand extends Command
{
	use GeneratorTrait;

	/**
	 * @var Leftaro\Core\Console\Generator\LeftaroTwigGenerator
	 */
	protected $twig;

	const BASE_NAMESPACE = "Leftaro\\App\\Controller\\Api";

	const BASE_PATH = __DIR__ . '/../../../App/Controller/Api/';

	protected static $defaultName = 'make:controller';

	/**
	 * Creates the console command
	 *
	 * @param \Leftaro\App\Hex\CommandBus $bus
	 */
	public function __construct(LeftaroTwigGenerator $twig)
	{
		parent::__construct();
		$this->twig = $twig;
	}

	/**
	 * Configure the command
	 *
	 * @return void
	 */
	protected function configure()
	{
		$this->setDescription('Creates a new controller.')
			->setHelp('This command allows you to create a new controller')
			->addArgument("name", InputArgument::REQUIRED, 'Controller name');
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
		$info = $this->buildPath($input->getArgument('name'), self::BASE_PATH, self::BASE_NAMESPACE);

		$nameParts = explode("\\", $input->getArgument('name'));
		$name = end($nameParts);

		$content = $this->twig->render('controller.twig', [
			'name' => $name,
			'namespace' => $info['namespace'],
			'slugname' => $this->camelCaseToSlug($name),
			'varname' => lcfirst($name),
		]);

		$file = $info['path'] . $name . 'Controller.php';

		file_put_contents($file, $content);

		if (file_exists($file))
		{
			$output->writeln('<info>' . $info['namespace'] . "\\" . $name . 'Controller was build successfuly</info>');
		}
		else
		{
			$output->writeln('<error>File was not created: ' . $file . '</error>');
		}
	}
}