<?php

namespace Consistence\Type;

class Type extends \Consistence\ObjectPrototype
{

	final public function __construct()
	{
		throw new \Consistence\StaticClassException();
	}

	/**
	 * Returns type of given value, in case of objects returns class name, normalizes all scalar values to lowercase
	 *
	 * @param mixed $value
	 * @return string
	 */
	public static function getType($value)
	{
		if (is_object($value)) {
			return get_class($value);
		}

		$type = gettype($value);
		switch ($type) {
			case 'double':
				return 'float';
			case 'NULL':
				return 'null';
		}

		return $type;
	}

}
