<?php
namespace Kapcus\ClassGenerator\Model;

class Column {
	/**
	 * @var string
	 */
	private $name;

	/**
	 * @var string
	 */
	private $type;

	/**
	 * @var int
	 */
	private $length;

	/**
	 * @var int
	 */
	private $scale;

	/**
	 * @var int
	 */
	private $precision;

	/**
	 * @var boolean
	 */
	private $isNullable;

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @param string $name
	 */
	public function setName($name)
	{
		$this->name = $name;
	}

	/**
	 * @return string
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 * @param string $type
	 */
	public function setType($type)
	{
		$this->type = $type;
	}

	/**
	 * @return int
	 */
	public function getLength()
	{
		return $this->length;
	}

	/**
	 * @param int $length
	 */
	public function setLength($length)
	{
		$this->length = $length;
	}

	/**
	 * @return int
	 */
	public function getScale()
	{
		return $this->scale;
	}

	/**
	 * @param int $scale
	 */
	public function setScale($scale)
	{
		$this->scale = $scale;
	}

	/**
	 * @return int
	 */
	public function getPrecision()
	{
		return $this->precision;
	}

	/**
	 * @param int $precision
	 */
	public function setPrecision($precision)
	{
		$this->precision = $precision;
	}

	/**
	 * @return bool
	 */
	public function isNullable()
	{
		return $this->isNullable;
	}

	/**
	 * @param bool $isNullable
	 */
	public function setIsNullable($isNullable)
	{
		$this->isNullable = $isNullable;
	}


}