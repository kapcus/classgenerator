<?php

namespace Kapcus\ClassGenerator\Model;

interface IConfigurator
{
	/**
	 * @return \Kapcus\ClassGenerator\Model\ConnectionConfiguration
	 */
	public function getConnectionConfiguration();
}