<?php

require __DIR__ . '/vendor/autoload.php';

use Symfony\Component\Console\Application;

$loader = new Nette\DI\ContainerLoader(__DIR__ . '/temp', IS_DEBUG_MODE);
if (!is_file(__DIR__ . '/config.local.neon')) {
	echo 'Move classgenerator/misc/config.local.neon.example into classgenerator/config.local.neon first.';
	exit(1);
}
/* NETTE DI >= 2.4
$class = $loader->load(function($compiler) {
	$compiler->loadConfig(__DIR__ . '/config/config.neon');
	$compiler->loadConfig(__DIR__ . '/config.local.neon');
});
*/

// NETTE DI == 2.3
$class = $loader->load('', function($compiler) {
	$compiler->loadConfig(__DIR__ . '/config/config.neon');
	$compiler->loadConfig(__DIR__ . '/config.local.neon');
});
$container = new $class;

$application = new Application('ClassGenerator', '0.1.0');
$application->add($container->getByType(Kapcus\ClassGenerator\Command\CheckCommand::class));
$application->add($container->getByType(Kapcus\ClassGenerator\Command\GenerateCommand::class));
exit($application->run());

