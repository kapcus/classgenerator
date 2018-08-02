<?php

namespace Kapcus\ClassGenerator\Model;

interface IExecutor
{
	/**
	 * @param \Kapcus\ClassGenerator\Model\ConnectionConfiguration $connectionConfiguration
	 *
	 * @throws \Kapcus\ClassGenerator\Model\Exception\ConnectionException
	 */
	public function setupConnection(ConnectionConfiguration $connectionConfiguration);

	/**
	 * @param string $sqlQuery
	 * @return \Dibi\Result|int
	 *
	 * @throws \Kapcus\ClassGenerator\Model\Exception\ExecutionException
	 */
	public function runQuery($sqlQuery);

	/**
	 * @throws \Kapcus\ClassGenerator\Model\Exception\ExecutionException
	 */
	public function testConnection();

	/**
	 * @return string[]
	 */
	public function getTables();

	/**
	 * @return string[]
	 */
	public function getViews();

	/**
	 * @param string $tableName
	 *
	 * @return \Kapcus\ClassGenerator\Model\Column[]
	 */
	public function getColumns($tableName);
}