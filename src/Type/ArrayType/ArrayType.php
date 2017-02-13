<?php

namespace Consistence\Type\ArrayType;

use Closure;

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
	 * Returns true when callback(\Consistence\Type\ArrayType\KeyValuePair) is at least once trueish
	 *
	 * @param mixed[] $haystack
	 * @param \Closure $callback
	 * @return boolean
	 */
	public static function inArrayByCallback(array $haystack, Closure $callback)
	{
		$result = self::findByCallback($haystack, $callback);
		return $result !== null;
	}

	/**
	 * Returns true when callback(value) is at least once trueish
	 *
	 * @param mixed[] $haystack
	 * @param \Closure $callback
	 * @return boolean
	 */
	public static function inArrayByValueCallback(array $haystack, Closure $callback)
	{
		$result = self::findValueByCallback($haystack, $callback);
		return $result !== null;
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
	 * Returns key when callback(\Consistence\Type\ArrayType\KeyValuePair) is at least once trueish or returns null
	 *
	 * @param mixed[] $haystack
	 * @param \Closure $callback
	 * @return integer|string|null
	 */
	public static function findKeyByCallback(array $haystack, Closure $callback)
	{
		$result = self::findByCallback($haystack, $callback);
		if ($result === null) {
			return null;
		}

		return $result->getKey();
	}

	/**
	 * Returns key when callback(value) is at least once trueish or returns null
	 *
	 * @param mixed[] $haystack
	 * @param \Closure $callback
	 * @return integer|string|null
	 */
	public static function findKeyByValueCallback(array $haystack, Closure $callback)
	{
		foreach ($haystack as $key => $value) {
			if ($callback($value)) {
				return $key;
			}
		}

		return null;
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
	 * Returns key when callback(\Consistence\Type\ArrayType\KeyValuePair) is at least once trueish or throws exception
	 *
	 * @param mixed[] $haystack
	 * @param \Closure $callback
	 * @return integer|string
	 */
	public static function getKeyByCallback(array $haystack, Closure $callback)
	{
		$result = self::findKeyByCallback($haystack, $callback);
		if ($result === null) {
			throw new \Consistence\Type\ArrayType\ElementDoesNotExistException();
		}

		return $result;
	}

	/**
	 * Returns key when callback(value) is at least once trueish or throws exception
	 *
	 * @param mixed[] $haystack
	 * @param \Closure $callback
	 * @return integer|string
	 */
	public static function getKeyByValueCallback(array $haystack, Closure $callback)
	{
		$result = self::findKeyByValueCallback($haystack, $callback);
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

	/**
	 * @param mixed[] $haystack
	 * @param integer|string $key
	 * @return mixed
	 */
	public static function getValue(array $haystack, $key)
	{
		$result = static::findValue($haystack, $key);
		if ($result === null) {
			throw new \Consistence\Type\ArrayType\ElementDoesNotExistException();
		}

		return $result;
	}

	/**
	 * Stops on first occurrence when callback(\Consistence\Type\ArrayType\KeyValuePair) is trueish or returns null
	 *
	 * @param mixed[] $haystack
	 * @param \Closure $callback
	 * @return \Consistence\Type\ArrayType\KeyValuePair|null
	 */
	public static function findByCallback(array $haystack, Closure $callback)
	{
		$keyValuePair = new KeyValuePairMutable(0, 0);
		foreach ($haystack as $key => $value) {
			$keyValuePair->setPair($key, $value);
			if ($callback($keyValuePair)) { // not strict comparison to be consistent with array_filter behavior
				return new KeyValuePair($key, $value);
			}
		}

		return null;
	}

	/**
	 * Stops on first occurrence when callback(\Consistence\Type\ArrayType\KeyValuePair) is trueish or throws exception
	 *
	 * @param mixed[] $haystack
	 * @param \Closure $callback
	 * @return \Consistence\Type\ArrayType\KeyValuePair
	 */
	public static function getByCallback(array $haystack, Closure $callback)
	{
		$result = static::findByCallback($haystack, $callback);
		if ($result === null) {
			throw new \Consistence\Type\ArrayType\ElementDoesNotExistException();
		}

		return $result;
	}

	/**
	 * Stops on first occurrence when callback(value) is trueish or returns null
	 *
	 * @param mixed[] $haystack
	 * @param \Closure $callback
	 * @return mixed|null
	 */
	public static function findValueByCallback(array $haystack, Closure $callback)
	{
		foreach ($haystack as $key => $value) {
			if ($callback($value)) {
				return $value;
			}
		}

		return null;
	}

	/**
	 * Stops on first occurrence when callback(value) is trueish or throws exception
	 *
	 * @param mixed[] $haystack
	 * @param \Closure $callback
	 * @return mixed
	 */
	public static function getValueByCallback(array $haystack, Closure $callback)
	{
		$result = static::findValueByCallback($haystack, $callback);
		if ($result === null) {
			throw new \Consistence\Type\ArrayType\ElementDoesNotExistException();
		}

		return $result;
	}

	/**
	 * Filters arrays by callback(\Consistence\Type\ArrayType\KeyValuePair)
	 *
	 * @param mixed[] $haystack
	 * @param \Closure $callback
	 * @return mixed[] new filtered array
	 */
	public static function filterByCallback(array $haystack, Closure $callback)
	{
		$filtered = [];
		$keyValuePair = new KeyValuePairMutable(0, 0);
		foreach ($haystack as $key => $value) {
			$keyValuePair->setPair($key, $value);
			if ($callback($keyValuePair)) { // not strict comparison to be consistent with array_filter behavior
				$filtered[$key] = $value;
			}
		}

		return $filtered;
	}

	/**
	 * Wrapper for PHP array_filter, executes loose comparison
	 *
	 * @param mixed[] $haystack
	 * @param \Closure $callback
	 * @return mixed[] new filtered array
	 */
	public static function filterValuesByCallback(array $haystack, Closure $callback)
	{
		return array_filter($haystack, $callback);
	}

	/**
	 * Map array by callback(\Consistence\Type\ArrayType\KeyValuePair)
	 *
	 * @param mixed[] $haystack
	 * @param \Closure $callback
	 * @return mixed[] new mapped array
	 */
	public static function mapByCallback(array $haystack, Closure $callback)
	{
		$result = [];
		$keyValuePair = new KeyValuePairMutable(0, 0);
		foreach ($haystack as $key => $value) {
			$keyValuePair->setPair($key, $value);
			$mappedKeyValuePair = $callback($keyValuePair);
			$result[$mappedKeyValuePair->getKey()] = $mappedKeyValuePair->getValue();
		}

		return $result;
	}

	/**
	 * Maps array by callback(value)
	 *
	 * @param mixed[] $haystack
	 * @param \Closure $callback
	 * @return mixed[] new mapped array
	 */
	public static function mapValuesByCallback(array $haystack, Closure $callback)
	{
		return array_map($callback, $haystack);
	}

	/**
	 * @param mixed[] $haystack reference to array
	 * @param mixed $value
	 * @return boolean returns true if the array was modified
	 */
	public static function removeValue(array &$haystack, $value)
	{
		$key = static::findKey($haystack, $value);
		if ($key === null) {
			return false;
		}
		unset($haystack[$key]);

		return true;
	}

	/**
	 * Removes key=>value pairs from given array if the key is present in $keys as value
	 *
	 * @param mixed[] $haystack reference to array
	 * @param mixed[] $keys keys to be removed from $haystack
	 * @return boolean returns true if the array was modified
	 */
	public static function removeKeys(array &$haystack, array $keys)
	{
		$modified = false;
		foreach ($keys as $key) {
			if (isset($haystack[$key])) {
				unset($haystack[$key]);
				$modified = true;
			}
		}

		return $modified;
	}

	/**
	 * Removes key=>value pairs from given array if the key is present in $arrayWithKeysToRemove also as key
	 *
	 * @param mixed[] $haystack reference to array
	 * @param mixed[] $arrayWithKeysToRemove
	 * @return boolean returns true if the array was modified
	 */
	public static function removeKeysByArrayKeys(array &$haystack, array $arrayWithKeysToRemove)
	{
		$modified = false;
		foreach ($arrayWithKeysToRemove as $key => $value) {
			if (isset($haystack[$key])) {
				unset($haystack[$key]);
				$modified = true;
			}
		}

		return $modified;
	}

	/**
	 * Mimics the behaviour of array_unique, but makes strict comparisons by default
	 *
	 * @param mixed[] $haystack
	 * @param boolean $strict
	 * @return mixed[] new array with unique values
	 */
	public static function uniqueValues(array $haystack, $strict = self::STRICT_TRUE)
	{
		$result = [];
		foreach ($haystack as $key => $value) {
			if (!self::inArray($result, $value, $strict)) {
				$result[$key] = $value;
			}
		}

		return $result;
	}

	/**
	 * Returns new array with unique values using callback(valueA, valueB),
	 * values are same if callback returns trueish value
	 *
	 * @param mixed[] $haystack
	 * @param \Closure $callback
	 * @return mixed[] new array with unique values
	 */
	public static function uniqueValuesByCallback(array $haystack, Closure $callback)
	{
		$result = [];
		foreach ($haystack as $newKey => $newValue) {
			foreach ($result as $existingValue) {
				if ($callback($existingValue, $newValue)) {
					continue 2; // skip to next $value
				}
			}

			$result[$newKey] = $newValue;
		}

		return $result;
	}

}
