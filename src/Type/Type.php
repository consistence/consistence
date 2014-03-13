<?php

namespace Consistence\Type;

class Type extends \Consistence\ObjectPrototype
{

	const SUBTYPES_ALLOW = true;
	const SUBTYPES_DISALLOW = false;

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

	/**
	 * Tests if the $value has one of expected types
	 *
	 * Supported syntax:
	 *  - integer
	 *  - integer|string
	 *  - integer|string|float
	 *  - integer|null
	 *  - DateTime (do not use leading \)
	 *  - stdClass|DateTime
	 *  - integer|DateTime
	 *  - integer[]
	 *  - integer[]|string[]
	 *  - integer[]|DateTime
	 *  - integer[][]
	 *
	 * @param mixed $value
	 * @param string $expectedTypes
	 * @param boolean $allowSubtypes decides if subtypes of given expected types should be considered a valid value
	 * @return boolean
	 */
	public static function hasType($value, $expectedTypes, $allowSubtypes = self::SUBTYPES_ALLOW)
	{
		$types = explode('|', $expectedTypes);
		foreach ($types as $type) {
			$typeLength = strlen($type);
			if ($type[$typeLength - 1] === ']' && $type[$typeLength - 2] === '[') {
				if (!is_array($value)) {
					continue; // skip to next type
				}
				foreach ($value as $item) {
					if (!self::hasType($item, substr($type, 0, $typeLength - 2))) {
						continue 2; // skip to next type
					}
				}

				return true;
			}
			if (strcasecmp(self::getType($value), $type) === 0 || ($allowSubtypes && is_a($value, $type))) {
				return true;
			}
		}

		return false;
	}

}
