<?php

namespace Kapcus\ClassGenerator\Model;

use Kapcus\ClassGenerator\Model\Exception\ConfigurationException;

class Configurator implements IConfigurator
{
	/**
	 * @var \Kapcus\ClassGenerator\Model\ConnectionConfiguration
	 */
	private $connectionConfiguration;

	public function __construct($configData)
	{
		if (!isset($configData['driver'])) {
			throw new ConfigurationException('database.driver parameter must be defined.');
		}

		if (!isset($configData['host'])) {
			throw new ConfigurationException('database.host parameter must be defined.');
		}

		if (!isset($configData['user'])) {
			throw new ConfigurationException('database.user parameter must be defined.');
		}

		if (!isset($configData['password'])) {
			throw new ConfigurationException('database.password parameter must be defined.');
		}

		if (!isset($configData['servicename'])) {
			throw new ConfigurationException('database.servicename parameter must be defined.');
		}

		if (!isset($configData['dbname'])) {
			throw new ConfigurationException('database.dbname parameter must be defined.');
		}

		if (!isset($configData['port'])) {
			throw new ConfigurationException('database.port parameter must be defined.');
		}

		$connectionConfiguration = new ConnectionConfiguration();
		$connectionConfiguration->setDriver($configData['driver']);
		$connectionConfiguration->setHostname($configData['host']);
		$connectionConfiguration->setUsername($configData['user']);
		$connectionConfiguration->setPassword($configData['password']);
		$connectionConfiguration->setDatabaseName($configData['servicename']);
		$connectionConfiguration->setPort($configData['port']);
		$this->connectionConfiguration = $connectionConfiguration;
	}

	/**
	 * @return \Kapcus\ClassGenerator\Model\ConnectionConfiguration
	 */
	public function getConnectionConfiguration()
	{
		return $this->connectionConfiguration;
	}
}