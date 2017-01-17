<?php

namespace Consistence\Type;

use Traversable;

class Type extends \Consistence\ObjectPrototype
{

	const SEPARATOR_KEY_TYPE = ':';

	const SUBTYPES_ALLOW = true;
	const SUBTYPES_DISALLOW = false;

	const TYPE_MIXED = 'mixed';
	const TYPE_OBJECT = 'object';

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

		$type = self::normalizeType($type);

		return $type;
	}

	/**
	 * Tests if the $value has one of expected types
	 *
	 * Supported syntax:
	 *  - integer
	 *  - mixed (allow every type)
	 *  - object
	 *  - int
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
	 *  - mixed[]|Collection
	 *
	 * Optional validation of keys in traversable types:
	 *  - integer:string[]
	 *  - missing keys can be omitted on right - string:mixed:string[][] is the same as string:string[][]
	 *  - key types cannot contain | sign to separate more accepted types
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
				if (!is_array($value) && !($value instanceof Traversable)) {
					continue; // skip to next type
				}
				$itemsType = substr($type, 0, $typeLength - 2);
				$firstKeyTypeSeparatorPosition = strpos($itemsType, self::SEPARATOR_KEY_TYPE);
				if ($firstKeyTypeSeparatorPosition !== false) {
					$keysType = substr($itemsType, 0, $firstKeyTypeSeparatorPosition);
					$itemsType = substr($itemsType, $firstKeyTypeSeparatorPosition + 1);
				}
				foreach ($value as $key => $item) {
					if (!self::hasType($item, $itemsType) || (isset($keysType) && !self::hasType($key, $keysType))) {
						continue 2; // skip to next type
					}
				}

				return true;
			}

			$type = self::normalizeType($type);

			if (
				$type === self::TYPE_MIXED
				|| ($type === self::TYPE_OBJECT && is_object($value))
				|| strcasecmp(self::getType($value), $type) === 0
				|| ($allowSubtypes && is_a($value, $type))
			) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Checks if the $value has one of expected types, throws exception if not
	 *
	 * @see hasType for syntax rules
	 *
	 * @param mixed $value
	 * @param string $expectedTypes
	 * @param boolean $allowSubtypes decides if subtypes of given expected types should be considered a valid value
	 */
	public static function checkType($value, $expectedTypes, $allowSubtypes = self::SUBTYPES_ALLOW)
	{
		if (!self::hasType($value, $expectedTypes, $allowSubtypes)) {
			throw new \Consistence\InvalidArgumentTypeException($value, $expectedTypes);
		}
	}

	/**
	 * @param string $type
	 * @return string
	 */
	private static function normalizeType($type)
	{
		switch ($type) {
			case 'double':
				return 'float';
			case 'int':
				return 'integer';
			case 'bool':
				return 'boolean';
			case 'NULL':
				return 'null';
			default:
				return $type;
		}
	}

}
