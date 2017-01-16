<?php

namespace Consistence\Enum;

use Consistence\Reflection\ClassReflection;
use Consistence\Type\ArrayType\ArrayType;
use Consistence\Type\Type;

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
		$index = sprintf('%s::%s', get_called_class(), self::getValueIndex($value));
		if (!isset(self::$instances[$index])) {
			self::$instances[$index] = new static($value);
		}

		return self::$instances[$index];
	}

	/**
	 * @param mixed $value
	 * @return string
	 */
	private static function getValueIndex($value)
	{
		$type = Type::getType($value);
		return $value . sprintf('[%s]', $type);
	}

	/**
	 * @return mixed[] format: const name (string) => value (mixed)
	 */
	public static function getAvailableValues()
	{
		$index = get_called_class();
		if (!isset(self::$availableValues[$index])) {
			$availableValues = self::getEnumConstants();
			static::checkAvailableValues($availableValues);
			self::$availableValues[$index] = $availableValues;
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
	 * @param mixed[] $availableValues
	 */
	protected static function checkAvailableValues(array $availableValues)
	{
		$index = [];
		foreach ($availableValues as $value) {
			Type::checkType($value, 'integer|string|float|boolean|null');
			$key = self::getValueIndex($value);
			if (isset($index[$key])) {
				throw new \Consistence\Enum\DuplicateValueSpecifiedException($value, static::class);
			}
			$index[$key] = true;
		}
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
