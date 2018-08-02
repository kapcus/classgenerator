<?php

namespace Kapcus\ClassGenerator\Command;

use Kapcus\ClassGenerator\Model\IConfigurator;
use Kapcus\ClassGenerator\Model\Manager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CheckCommand extends Command
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
			->setName('classgenerator:check')
			->setDescription('Checks whether ClassGenerator is properly installed and configured.');
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		try {
			$this->manager->checkConnection($this->configurator->getConnectionConfiguration());
			$output->writeln(sprintf('OK: ClassGenerator seems to be properly configured.'));
		} catch (\Exception $e) {
			$output->writeln(sprintf('FAILURE: %s ', $e->getMessage()));
			throw $e;
		}
	}
}