<?php

namespace Kapcus\ClassGenerator\Model;

class DataTypes
{
	const UNKNOWN = 0;

	const SMALLINT = 1;

	const INTEGER = 2;

	// oracle
	const NUMBER = 2;

	const INT = 2;

	const BIGINT = 3;

	const DECIMAL = 4;

	const NUMERIC = 5;

	const DECFLOAT = 6;

	const REAL = 7;

	// oracle
	const FLOAT = 8;

	const DOUBLE = 8;

	const CHARACTER = 9;

	const CHAR = 9;

	const VARCHAR = 10;

	// oracle
	const VARCHAR2 = 10;

	const CLOB = 11;

	const GRAPHIC = 12;

	const VARGRAPHIC = 13;

	const DBCLOB = 14;

	const BINARY = 15;

	const VARBINARY = 16;

	const BLOB = 17;

	const DATE = 18;

	const TIME = 19;

	const TIMESTAMP = 20;

	public static $RANGES = [
		self::SMALLINT => [-32768, 32767],
		self::INTEGER => [-2147483648, 2147483647],
		self::BIGINT => ['-9223372036854775808', '9223372036854775807'],  //has to be string!!
	];

	public static $INT_TYPES = [
		self::SMALLINT,
		self::INTEGER,
		self::NUMBER,
		self::INT,
		self::BIGINT,
		self::DECIMAL,
		self::NUMERIC,
	];

	public static $FLOAT_TYPES = [
		self::REAL,
		self::DOUBLE,
		self::DECFLOAT,
		self::FLOAT,
	];
}