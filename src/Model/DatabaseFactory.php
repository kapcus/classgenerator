<?php

namespace Kapcus\ClassGenerator\Model;

use Dibi\NotImplementedException;
use Kapcus\ClassGenerator\Model\Database\Oracle;

class DatabaseFactory
{

	public function getDatabase(ConnectionConfiguration $connectionConfiguration) {
		switch ($connectionConfiguration->getDriver()) {
			case 'oci8':
				return new Oracle($connectionConfiguration);
			default:
				throw new NotImplementedException();
		}


	}
}