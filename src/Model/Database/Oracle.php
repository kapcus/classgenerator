<?php

namespace Kapcus\ClassGenerator\Model\Database;

use Kapcus\ClassGenerator\Model\ConnectionConfiguration;
use Kapcus\ClassGenerator\Model\IDatabase;

class Oracle implements IDatabase
{
	/**
	 * @var \Kapcus\ClassGenerator\Model\ConnectionConfiguration
	 */
	private $connectionConfiguration;

	public function __construct(ConnectionConfiguration $connectionConfiguration)
	{
		$this->connectionConfiguration = $connectionConfiguration;
	}

	/**
	 * @return string[]
	 */
	function getConnectionOptions()
	{
		return [
			'driver' => 'oracle',
			'username' => $this->connectionConfiguration->getUsername(),
			'password' => $this->connectionConfiguration->getPassword(),
			'database' => sprintf(
				'%1$s:%2$s/%3$s',
				$this->connectionConfiguration->getHostname(),
				$this->connectionConfiguration->getPort(),
				$this->connectionConfiguration->getDatabaseName()
			),
		];
	}

	/**
	 * @return string
	 */
	function getTestQuery()
	{
		return 'SELECT SYSDATE FROM DUAL';
	}

	/**
	 *
	 * @return string
	 */
	function getTablesQuery()
	{
		return sprintf(
			'SELECT TABLE_NAME AS TABLENAME FROM all_tables WHERE OWNER = \'%1$s\' ORDER BY TABLE_NAME',
			$this->connectionConfiguration->getUsername()
		);
	}

	/**
	 *
	 * @return string
	 */
	function getViewsQuery()
	{
		return sprintf(
			'SELECT VIEW_NAME AS VIEWNAME FROM all_views WHERE OWNER = \'%1$s\' ORDER BY VIEW_NAME',
			$this->connectionConfiguration->getUsername()
		);
	}

	function getColumnsQuery($tableName)
	{
		return sprintf(
			'select COLUMN_NAME AS COLUMNNAME, DATA_TYPE AS TYPE_NAME, DATA_LENGTH AS COLUMN_SIZE, \'\' AS REMARKS, DATA_SCALE, DATA_PRECISION, NULLABLE
   from all_tab_columns 
   where table_name = \'%1$s\' 
   and OWNER = \'%2$s\' 
   order by COLUMN_NAME',
			$tableName,
			$this->connectionConfiguration->getUsername()
		);
	}
}