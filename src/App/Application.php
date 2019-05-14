<?php namespace Leftaro\App;

use Leftaro\Core\Application as LeftaroApplication;
use Propel\Runtime\Connection\ConnectionManagerSingle;
use Propel\Runtime\Propel;

class Application extends LeftaroApplication
{
	/**
	 * Load the orm classes using the configuration values
	 * This should run after the main application was loaded
	 *
	 * @return void
	 */
	public function initOrm()
	{
		$config = $this->container->get('config');

		$serviceContainer = Propel::getServiceContainer();
		$serviceContainer->checkVersion('2.0.0-dev');
		$serviceContainer->setAdapterClass('default', 'mysql');

		$manager = new ConnectionManagerSingle();
		$manager->setConfiguration(
			[
				'dsn' => 'mysql:host=' . $config->get('database.host') . ';port=3306;dbname=' . $config->get('database.dbname') . ';charset=utf8',
				'user' => $config->get('database.user'),
				'password' => $config->get('database.password'),
				'settings' =>
				[
					[
						'charset' => 'utf8',
						'queries' => [],
					],
					'classname' => '\\Propel\\Runtime\\Connection\\ConnectionWrapper',
					'model_paths' =>
					[
						0 => 'src',
						1 => 'vendor',
					],
				]
			]
		);

		$manager->setName('default');
		$serviceContainer->setConnectionManager('default', $manager);
		$serviceContainer->setDefaultDatasource('default');
		$serviceContainer->setLogger('defaultLogger', $this->container->get('logger'));

		Propel::getConnection()->useDebug(true);
	}
}