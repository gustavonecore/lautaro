<?php namespace Leftaro\App\Twig;

use Psr\Container\ContainerInterface;
use Twig_SimpleFunction;

/**
 * Class to add custom extension to twig template engine
 */
class AppExtension extends \Twig_Extension
{
	protected $container;

	/**
	 * Constructs the class
	 *
	 * @param ContainerInterface $container
	 */
	public function __construct(ContainerInterface $container)
	{
		$this->container = $container;
	}

	/**
	 * Get the defined filters
	 *
	 * @return void
	 */
    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction('www', function ($url = '')
			{
				$url = ltrim((string)$url, '/');

				return $this->container->get('config')->get('host') . $url;
			}),
		];
    }
}