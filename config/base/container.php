<?php

use DI\ContainerBuilder;

$containerBuilder = new ContainerBuilder;
$containerBuilder->addDefinitions(__DIR__ . '/services.php');
return $containerBuilder->build();