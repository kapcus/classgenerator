<?php

namespace Kapcus\ClassGenerator\Model;

class Manager
{
	/**
	 * @var \Kapcus\ClassGenerator\Model\IExecutor
	 */
	private $executor;

	/**
	 * @var string
	 */
	private $outputDirectory;

	/**
	 * @var string
	 */
	private $classNamespace;

	/**
	 * @var string
	 */
	private $datatypeNamespace;

	const COLUMN_EXCEPTIONS = [
		'TESTAPI' => ['GROUP', 'VALUE'],
		'AUDIT_LOG' => ['LEVEL'],
		'CYCCNT' => ['MODE'],
		'SCREENING' => ['COMMENT'],
		'DBCHANGE' => ['NUMBER'],
		'V_AUDIT' => ['LEVEL'],
		'V_MYWHS' => ['LEVEL'],
		'LISTRGMAP' => ['TRIGGER', 'GROUP'],
		'LIS_FLIGHT' => ['NUMBER'],
		'LIS_HB' => ['REFERENCES'],
		'LIS_MB' => ['NUMBER'],
	];

	private $typeMapping = [
		'TIMESTAMP(6)' => ['TIMESTAMP', 6],
	];

	public function __construct($outputDirectory, $classNamespace, $datatypeNamespace, IExecutor $executor)
	{
		$this->outputDirectory = $outputDirectory;
		$this->executor = $executor;
		$this->classNamespace = $classNamespace;
		$this->datatypeNamespace = $datatypeNamespace;
	}

	/**
	 * @param \Kapcus\ClassGenerator\Model\ConnectionConfiguration $connectionConfiguration
	 *
	 * @throws \Kapcus\ClassGenerator\Model\Exception\ConnectionException
	 * @throws \Kapcus\ClassGenerator\Model\Exception\ExecutionException
	 */
	public function checkConnection(ConnectionConfiguration $connectionConfiguration)
	{
		$this->executor->setupConnection($connectionConfiguration);
		$this->executor->testConnection();
	}

	/**
	 * @param \Kapcus\ClassGenerator\Model\ConnectionConfiguration $connectionConfiguration
	 *
	 * @throws \Kapcus\ClassGenerator\Model\Exception\ConnectionException

	 */
	public function generateClasses(ConnectionConfiguration $connectionConfiguration)
	{
		$this->executor->setupConnection($connectionConfiguration);
		$this->prepareOutputDir();
		$tableNames = $this->executor->getTables();
		foreach($tableNames as $tableName) {
			$this->generateClass($tableName);
		}
		$viewNames = $this->executor->getViews();
		foreach($viewNames as $viewName) {
			$this->generateClass($viewName);
		}

	}

	private function generateClass($className)
	{
		$code = "<?php\n";
		$code .= "\n";
		$code .= "namespace " . $this->classNamespace . ";\n";
		$code .= "\n";
		$code .= "use ". $this->datatypeNamespace .";\n";
		$code .= "\n";
		$code .= "/**\n";
		$code .= " * This class was automatically generated by ClassGenerator\n";
		$code .= " */\n";
		$code .= "class " . $className . " extends Table\n";
		$code .= "{\n";
		$code .= "\tconst TABLENAME = '" . $className . "';\n";
		$code .= "\n";
		$code .= "\tconst ALIAS = '" . $className . "';\n";
		$code .= "\n";

		$columns = $this->executor->getColumns($className);

		foreach ($columns as $column) {
			$this->addColumnConst(
				$code,
				$this->getColumnConstant($className, $column->getName()),
				$column->getName()
			);
		}

		foreach ($columns as $column) {
			$this->addColumnFunction($code,	$className,	$column);
		}

		$this->addColumnSizes($className, $code, $columns);

		$this->addColumnDataTypes($className, $code, $columns);


		$code .= "}\n";

		file_put_contents($this->outputDirectory . '/' . $className . '.php', $code);
	}

	/**
	 * @param string $tableName
	 * @param string $columnName
	 *
	 * @return bool|string
	 */
	private function getColumnConstant($tableName, $columnName)
	{
		if (!array_key_exists($tableName, self::COLUMN_EXCEPTIONS)) {
			return $columnName;
		}
		$columnBaseName = substr($columnName, strlen($tableName) + 1);
		if ($columnBaseName !== false && in_array(
				$columnBaseName,
				self::COLUMN_EXCEPTIONS[$tableName]
			)) {
			return $columnBaseName;
		}

		return $columnName;
	}

	private function addColumnConst(&$code, $constantName, $colname)
	{
		$code .= "\tconst F_" . $constantName . " = '" . $colname . "';\n\n";
	}

	private function addColumnFunction(&$code, $className, Column $column)
	{

		$colsize = $column->getLength();
		$colname = $this->getColumnConstant($className, $column->getName());
		$coltype = $column->getType();
		$nullTxt = $column->isNullable() ? "" : "NOT NULL";
		if (isset($this->typeMapping[$column->getType()])) {
			$coltype = $this->typeMapping[$column->getType()][0];
			if (isset($this->typeMapping[$column->getType()][1])) {
				$colsize = $this->typeMapping[$column->getType()][1];
			}
		}
		$size = in_array($coltype, ['NUMBER', 'TIMESTAMP(6)']) ? '' : $colsize;
		if ($column->getPrecision() != null) {
			$size = $column->getPrecision();
			if (isset($scale) && $scale != 0) {
				$size .= ',' . $scale;
			}
		}
		if ($size != '') {
			$size = "($size)";
		}
		$code .= "\t/** $coltype$size $nullTxt */\n";
		$code .= "\tpublic static function " . $colname . "()\n";
		$code .= "\t{\n";
		$code .= "\t\treturn static::ALIAS . '.' . static::F_" . $colname . ";\n";
		$code .= "\t}\n";
		$code .= "\n";
	}

	/**
	 * @param string $tableName
	 * @param string $code
	 * @param \Kapcus\ClassGenerator\Model\Column[] $columns
	 */
	private function addColumnSizes($tableName, &$code, $columns)
	{
		$code .= "\tpublic static \$SIZES = [\n";
		/**
		 * @var \Kapcus\ClassGenerator\Model\Column[] $columns
		 */
		foreach ($columns as $column) {

			if (in_array($column->getType(), ['VARCHAR', 'VARCHAR2', 'CHAR'])) {
				$size = $column->getLength();
				$code .= "\t\tself::F_" . $this->getColumnConstant($tableName, $column->getName()) . " => $size,\n";
			}
		}
		$code .= "\t];\n";
	}

	/**
	 * @param string $tablename
	 * @param string $code
	 * @param \Kapcus\ClassGenerator\Model\Column[] $columns
	 */
	private function addColumnDataTypes($tablename, &$code, array $columns)
	{
		$code .= "\n\tpublic static \$TYPES = [\n";
		/**
		 * @var \Kapcus\ClassGenerator\Model\Column[] $columns
		 */
		foreach ($columns as $column) {
			$dataType = $column->getType();
			if (isset($this->typeMapping[$column->getType()])) {
				$dataType = $this->typeMapping[$column->getType()][0];
			} elseif ($column->getType() == 'NUMBER' && $column->getScale() !== null && $column->getScale() != '0') {
				$dataType = 'FLOAT';
			} elseif (!defined('\Kapcus\ClassGenerator\Model\DataTypes::' . $dataType)) {
				$dataType = 'UNKNOWN';
			}
			$code .= "\t\tself::F_" . $this->getColumnConstant(
					$tablename,
					$column->getName()
				) . " => DataTypes::" . $dataType . ",\n";
		}
		$code .= "\t];\n";
	}

	private function prepareOutputDir()
	{
		if (!is_dir($this->outputDirectory)) {
			mkdir($this->outputDirectory);
		}
	}
}