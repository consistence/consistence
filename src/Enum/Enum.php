<?php

namespace Consistence\Enum;

use Consistence\Reflection\ClassReflection;
use Consistence\Type\ArrayType\ArrayType;

use ReflectionClass;

abstract class Enum extends \Consistence\ObjectPrototype
{

	/** @var mixed */
	private $value;

	/** @var self[] indexed by enum and value */
	private static $instances = [];

	/** @var mixed[] format: enum name (string) => cached values (const name (string) => value (mixed)) */
	private static $availableValues;

	/**
	 * @param mixed $value
	 */
	final private function __construct($value)
	{
		static::checkValue($value);
		$this->value = $value;
	}

	/**
	 * @param mixed $value
	 * @return static
	 */
	public static function get($value)
	{
		$index = sprintf('%s::$%s', get_called_class(), $value);
		if (!isset(self::$instances[$index])) {
			self::$instances[$index] = new static($value);
		}

		return self::$instances[$index];
	}

	/**
	 * @return mixed[] format: const name (string) => value (mixed)
	 */
	public static function getAvailableValues()
	{
		$index = get_called_class();
		if (!isset(self::$availableValues[$index])) {
			self::$availableValues[$index] = self::getEnumConstants();
		}

		return self::$availableValues[$index];
	}

	/**
	 * @return mixed[] format: const name (string) => value (mixed)
	 */
	private static function getEnumConstants()
	{
		$classReflection = new ReflectionClass(get_called_class());
		$declaredConstants = ClassReflection::getDeclaredConstants($classReflection);
		ArrayType::removeKeys($declaredConstants, static::getIgnoredConstantNames());

		return $declaredConstants;
	}

	/**
	 * @param mixed $value
	 * @return boolean
	 */
	public static function isValidValue($value)
	{
		return ArrayType::inArray(static::getAvailableValues(), $value);
	}

	/**
	 * @param mixed $value
	 */
	public static function checkValue($value)
	{
		if (!static::isValidValue($value)) {
			throw new \Consistence\Enum\InvalidEnumValueException($value, static::getAvailableValues());
		}
	}

	/**
	 * @return string[] names of constants which should not be used as valid values of this enum
	 */
	protected static function getIgnoredConstantNames()
	{
		return [];
	}

	/**
	 * @param self $that
	 */
	protected function checkSameEnum(self $that)
	{
		if (get_class($this) !== get_class($that)) {
			throw new \Consistence\Enum\OperationSupportedOnlyForSameEnumException($that, $this);
		}
	}

	/**
	 * @return mixed
	 */
	public function getValue()
	{
		return $this->value;
	}

	/**
	 * @param self $that
	 * @return boolean
	 */
	public function equals(self $that)
	{
		$this->checkSameEnum($that);

		return $this === $that;
	}

	/**
	 * @param mixed $value
	 * @return boolean
	 */
	public function equalsValue($value)
	{
		return $this->getValue() === $value;
	}

}
