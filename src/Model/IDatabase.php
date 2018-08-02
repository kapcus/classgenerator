<?php

namespace Kapcus\ClassGenerator\Model;

interface IDatabase
{

	/**
	 *
	 * @return string[]
	 */
	function getConnectionOptions();

	/**
	 * @return string
	 */
	function getTestQuery();

	/**
	 *
	 * @return string
	 */
	function getTablesQuery();

	/**
	 * @return string
	 */
	function getViewsQuery();

	/**
	 * @param string $tableName
	 *
	 * @return string
	 */
	function getColumnsQuery($tableName);
}