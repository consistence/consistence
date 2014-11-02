<?php

namespace Consistence\Type\ArrayType;

class ArrayType extends \Consistence\ObjectPrototype
{

	const STRICT_TRUE = true;
	const STRICT_FALSE = false;

	final public function __construct()
	{
		throw new \Consistence\StaticClassException();
	}

	/**
	 * Wrapper for PHP in_array, provides safer default parameter
	 *
	 * @param mixed[] $haystack
	 * @param mixed $needle
	 * @param boolean $strict
	 * @return boolean
	 */
	public static function inArray(array $haystack, $needle, $strict = self::STRICT_TRUE)
	{
		return in_array($needle, $haystack, $strict);
	}

	/**
	 * Wrapper for PHP array_search, provides safer default parameter. Returns null when value is not found.
	 *
	 * @param mixed[] $haystack
	 * @param mixed $needle
	 * @param boolean $strict
	 * @return integer|string|null
	 */
	public static function findKey(array $haystack, $needle, $strict = self::STRICT_TRUE)
	{
		$result = array_search($needle, $haystack, $strict);
		if ($result === false) {
			return null;
		}

		return $result;
	}

	/**
	 * @param mixed[] $haystack
	 * @param mixed $needle
	 * @param boolean $strict
	 * @return integer|string
	 */
	public static function getKey(array $haystack, $needle, $strict = self::STRICT_TRUE)
	{
		$result = static::findKey($haystack, $needle, $strict);
		if ($result === null) {
			throw new \Consistence\Type\ArrayType\ElementDoesNotExistException();
		}

		return $result;
	}

	/**
	 * @param mixed[] $haystack
	 * @param integer|string $key
	 * @return mixed|null
	 */
	public static function findValue(array $haystack, $key)
	{
		if (!array_key_exists($key, $haystack)) {
			return null;
		}

		return $haystack[$key];
	}

}
