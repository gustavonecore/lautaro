<?php

namespace Leftaro\Core\Console\Generator;

trait GeneratorTrait
{
	/**
	 * Make the related directory if not exists
	 * Return the final path and the related namespace
	 *
	 * @param string $name
	 * @param string $basePath
	 * @param string $baseNamespace
	 * @return array
	 */
	private function buildPath(string $name, string $basePath, string $baseNamespace) : array
	{
		$path = substr($basePath, 0, strlen($basePath) -1);

		$names = explode("\\", $name);

		$finalNamespace = '';

		if (count($names) > 1)
		{
			$namespaces = [];

			for ($i = 0; $i < count($names) - 1; $i++)
			{
				$name = ucfirst($names[$i]);
				$path .= "/" . $name;
				if (!file_exists($path))
				{
					mkdir($path);
				}
				$namespaces[] = $name;
			}

			$finalNamespace = "\\" . implode("\\", $namespaces);
			$path .= "/";
		}
		else
		{
			$path .= "/";
		}

		return [
			'namespace' => $baseNamespace . $finalNamespace,
			'path' => $path,
		];
	}

	/**
	 * Convert camcelcase to slug type
	 *
	 * @param string $text
	 * @return string
	 */
	private function camelCaseToSlug(string $text) : string
	{
		return strtolower(preg_replace('/(?<=\\w)(?=[A-Z])/',"_$1", $text));
	}
}