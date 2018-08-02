<?php

namespace Kapcus\ClassGenerator\Model;

use Dibi\Connection;
use Dibi\Exception;
use Kapcus\ClassGenerator\Model\Exception\ConnectionException;
use Kapcus\ClassGenerator\Model\Exception\ExecutionException;

class DibiExecutor implements IExecutor
{
	/**
	 * @var \Kapcus\ClassGenerator\Model\DatabaseFactory
	 */
	private $databaseFactory;

	/**
	 * @var \Kapcus\ClassGenerator\Model\IDatabase
	 */
	private $database;

	/**
	 * @var bool
	 */
	private $isDebug = true;

	/**
	 * @var \Dibi\Connection
	 */
	private $connection;

	/**
	 * @var string
	 */
	private $logDirectory;

	public function __construct($logDirectory, DatabaseFactory $databaseFactory)
	{
		$this->databaseFactory = $databaseFactory;
		$this->logDirectory = $logDirectory;

		if ($this->isDebug) {
			if (!is_dir($this->logDirectory)) {
				mkdir($this->logDirectory);
			}
		}
	}

	/**
	 * @param \Kapcus\ClassGenerator\Model\ConnectionConfiguration $connectionConfiguration
	 *
	 * @throws \Kapcus\ClassGenerator\Model\Exception\ConnectionException
	 */
	public function setupConnection(ConnectionConfiguration $connectionConfiguration)
	{
		$this->writeLog(sprintf('---------------'));
		$this->writeLog(date('d.m.Y H:i:s'));
		$this->writeLog(sprintf('---------------'));
		try {
			$database = $this->databaseFactory->getDatabase($connectionConfiguration);
			$this->setDatabase($database);
			$this->setConnection(new Connection($database->getConnectionOptions()));
		} catch (Exception $e) {
			$this->writeLog(sprintf('Unable to connect.'));
			throw new ConnectionException('Unable to connect.', 0, $e);
		}
		$this->writeLog(
			sprintf('Connected as user %1$s (host %2$s).', $connectionConfiguration->getUsername(), $connectionConfiguration->getHostname())
		);
	}

	/**
	 * @param string $sqlQuery
	 *
	 * @return \Dibi\Result|int
	 * @throws \Kapcus\ClassGenerator\Model\Exception\ExecutionException
	 */
	public function runQuery($sqlQuery)
	{
		try {
			$start = new \DateTime();
			$result = $this->getConnection()->query($sqlQuery);
			$end = new \DateTime();

			if ($this->isDebug) {
				$this->writeLog(sprintf('OK (%s) : %s', $start->diff($end)->format("%H:%I:%S"), $sqlQuery));
			}

			return $result;
		} catch (Exception $e) {
			$this->writeLog(sprintf('FAILED : %s', $sqlQuery));
			throw new ExecutionException(sprintf('Unable to execute following query: %s', $sqlQuery), 0, $e);
		}
	}

	/**
	 * @param $message
	 */
	private function writeLog($message)
	{
		file_put_contents($this->logDirectory . DIRECTORY_SEPARATOR . 'classgenerator.log', $message . PHP_EOL, FILE_APPEND);
	}

	/**
	 * @return \Dibi\Connection
	 */
	public function getConnection()
	{
		return $this->connection;
	}

	/**
	 * @param \Dibi\Connection $connection
	 */
	public function setConnection($connection)
	{
		$this->connection = $connection;
	}

	/**
	 * @return \Kapcus\ClassGenerator\Model\IDatabase
	 */
	public function getDatabase()
	{
		return $this->database;
	}

	/**
	 * @param \Kapcus\ClassGenerator\Model\IDatabase $database
	 */
	public function setDatabase($database)
	{
		$this->database = $database;
	}



	/**
	 * @throws \Kapcus\ClassGenerator\Model\Exception\ExecutionException
	 */
	public function testConnection()
	{
		$this->runQuery($this->getDatabase()->getTestQuery())->fetch();
	}

	/**
	 * @throws \Kapcus\ClassGenerator\Model\Exception\ExecutionException
	 */
	public function getTables()
	{
		return array_keys($this->runQuery($this->getDatabase()->getTablesQuery())->fetchAssoc('TABLENAME'));
	}

	/**
	 * @throws \Kapcus\ClassGenerator\Model\Exception\ExecutionException
	 */
	public function getViews()
	{
		return array_keys($this->runQuery($this->getDatabase()->getViewsQuery())->fetchAssoc('VIEWNAME'));
	}

	/**
	 * @param string $tableName
	 *
	 * @return \Kapcus\ClassGenerator\Model\Column[]
	 * @throws \Kapcus\ClassGenerator\Model\Exception\ExecutionException
	 */
	public function getColumns($tableName) {
		$result = $this->runQuery($this->getDatabase()->getColumnsQuery($tableName))->fetchAll();
		$columns = [];
		foreach($result as $row) {
			$column = new Column();
			$column->setName($row['COLUMNNAME']);
			$column->setType($row['TYPE_NAME']);
			$column->setLength($row['COLUMN_SIZE']);
			$column->setScale($row['DATA_SCALE']);
			$column->setPrecision($row['DATA_PRECISION']);
			$column->setIsNullable($row['NULLABLE'] == 'Y');
			$columns[] = $column;
		}
		return $columns;
	}
}