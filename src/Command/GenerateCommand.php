<?php

namespace Kapcus\ClassGenerator\Command;

use Kapcus\ClassGenerator\Model\IConfigurator;
use Kapcus\ClassGenerator\Model\Manager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateCommand extends Command
{

	/**
	 * @var \Kapcus\ClassGenerator\Model\Manager
	 */
	public $manager;

	/**
	 * @var \Kapcus\ClassGenerator\Model\IConfigurator
	 */
	public $configurator;

	public function __construct(Manager $manager, IConfigurator $configurator) {
		$this->manager = $manager;
		$this->configurator = $configurator;
		parent::__construct();
	}

	protected function configure()
	{
		$this
			->setName('classgenerator:generate')
			->setDescription('Generates classes for tables and views in given schema.');
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		try {
			$this->manager->generateClasses($this->configurator->getConnectionConfiguration());
			$output->writeln(sprintf('OK: Classes generated successfully.'));
		} catch (\Exception $e) {
			$output->writeln(sprintf('FAILURE: %s ', $e->getMessage()));
			throw $e;
		}
	}
}