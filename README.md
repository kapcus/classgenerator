ClassGenerator
=================
Simple tool for generating classes representing tables and views in given schema.

Currently supported databases: 
* `ORACLE` 

Installation
---------
1] Install ClassGenerator with all necessary dependencies with
```
composer require kapcus/classgenerator
```

2] Move [config.local.neon.example](misc/config.local.neon.example) into `classgenerator/config.local.neon` and setup classgenerator.database section, output directory and namespaces.

3] run this to verify if ClassGenerator is properly installed and configured
```
php bin/console.php classgenerator:check
``` 

Usage
---------

``` 
php bin/console.php classgenerator:generate
```

This command will generate all classes into `outputDirectory` with proper `classNamespace` and `datatypeNamespace`.

========================================

TODO
---------
* remove COLUMN_EXCEPTIONS from Manager class
* remove $typeMapping from Manager class
* resolve abstract Table
* resolve DataTypes
* introduce kapcus/core and remove some classes

