<?php

require __DIR__ . '/../app/bootstrap.php';

$propel = __DIR__ . '/../propel';
$etc = __DIR__ . '/../etc/';
$configDir = __DIR__ . '/../config/local/';
$settings = require __DIR__ . '/../config/local/settings.php';
$user = $settings['database']['user'];
$pass = $settings['database']['password'];
$dbname = $settings['database']['dbname'];
$host = $settings['database']['host'];

// Update schema sql
shell_exec("mysqldump -u$user -p$pass -h$host --no-data $dbname > $etc/schema.sql");

// Update schema xml from database
shell_exec("$propel database:reverse --config-dir=$configDir --namespace=\"Leftaro\App\Model\" --output-dir=$etc");

// Remove extra namespace from classes by database node
file_put_contents($etc . 'schema.xml', str_replace(
	'<database name="default" defaultIdMethod="native" namespace="Leftaro\App\Model" defaultPhpNamingMethod="underscore">',
	'<database name="default" defaultIdMethod="native" defaultPhpNamingMethod="underscore">',
	file_get_contents($etc . 'schema.xml')
));

// Update orm classes with new schema
shell_exec("$propel build --schema-dir=$etc --output-dir=src/App/Model --disable-namespace-auto-package  --config-dir=$configDir");