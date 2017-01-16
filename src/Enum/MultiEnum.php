<?php

namespace Consistence\Enum;

use ArrayIterator;
use Closure;

use Consistence\Math\Math;
use Consistence\Type\ArrayType\ArrayType;
use Consistence\Type\Type;

abstract class MultiEnum extends \Consistence\Enum\Enum implements \IteratorAggregate
{

	/** @var mixed[] format: enum name (string) => cached values (const name (string) => value (mixed)) */
	private static $availableValues;

	/**
	 * @return string|null class name representing single enum value (has to implement Consistence\Enum\Enum); null if not mapped
	 */
	public static function getSingleEnumClass()
	{
		return null;
	}

	/**
	 * @return integer[] format: const name (string) => value (integer)
	 */
	public static function getAvailableValues()
	{
		$index = get_called_class();
		if (!isset(self::$availableValues[$index])) {
			$singleEnumClass = static::getSingleEnumClass();
			$availableValues = ($singleEnumClass !== null)
				? self::getSingleEnumMappedAvailableValues($singleEnumClass)
				: parent::getAvailableValues();
			static::checkAvailableValues($availableValues);
			self::$availableValues[$index] = $availableValues;
		}

		return self::$availableValues[$index];
	}

	/**
	 * @param integer[] $availableValues
	 */
	protected static function checkAvailableValues(array $availableValues)
	{
		parent::checkAvailableValues($availableValues);
		foreach ($availableValues as $value) {
			if (!Math::isPowerOfTwo($value)) {
				throw new \Consistence\Enum\MultiEnumValueIsNotPowerOfTwoException($value, static::class);
			}
		}
	}

	/**
	 * @param integer ...$values
	 * @return static
	 */
	public static function getMulti(...$values)
	{
		return self::getMultiByArray($values);
	}

	/**
	 * @param integer[] $values enum values
	 * @return static
	 */
	public static function getMultiByArray(array $values)
	{
		$state = 0;
		foreach ($values as $value) {
			self::checkSingleValue($value);
			$state |= $value;
		}
		return self::get($state);
	}

	/**
	 * @param \Consistence\Enum\Enum $singleEnum
	 * @return static
	 */
	public static function getMultiByEnum(Enum $singleEnum)
	{
		return self::get(static::convertSingleEnumToValue($singleEnum));
	}

	/**
	 * @param \Consistence\Enum\Enum[] $singleEnums
	 * @return static
	 */
	public static function getMultiByEnums(array $singleEnums)
	{
		return self::getMultiByArray(ArrayType::mapValuesByCallback($singleEnums, function (Enum $singleEnum) {
			return static::convertSingleEnumToValue($singleEnum);
		}));
	}

	/**
	 * Converts value representing a value from single Enum to MultiEnum counterpart
	 *
	 * @param mixed $singleEnumValue
	 * @return integer
	 */
	protected static function convertSingleEnumValueToValue($singleEnumValue)
	{
		return $singleEnumValue;
	}

	/**
	 * Converts value representing a value from MultiEnum to single Enum counterpart
	 *
	 * @param integer $value
	 * @return mixed
	 */
	protected static function convertValueToSingleEnumValue($value)
	{
		return $value;
	}

	/**
	 * Converts value representing part of MultiEnum to Enum instance
	 *
	 * @param integer $value
	 * @return \Consistence\Enum\Enum
	 */
	protected static function convertValueToSingleEnum($value)
	{
		$singleEnumClass = static::getSingleEnumClass();

		return $singleEnumClass::get(static::convertValueToSingleEnumValue($value));
	}

	/**
	 * Converts Enum instance to value representing part of MultiEnum
	 *
	 * @param \Consistence\Enum\Enum $singleEnum
	 * @return integer
	 */
	protected static function convertSingleEnumToValue(Enum $singleEnum)
	{
		return static::convertSingleEnumValueToValue($singleEnum->getValue());
	}

	/**
	 * @param string $singleEnumClass
	 * @return integer[] format: const name (string) => value (integer)
	 */
	private static function getSingleEnumMappedAvailableValues($singleEnumClass)
	{
		return ArrayType::mapValuesByCallback($singleEnumClass::getAvailableValues(), function ($singleEnumValue) {
			return static::convertSingleEnumValueToValue($singleEnumValue);
		});
	}

	/**
	 * @param \Consistence\Enum\Enum $singleEnum
	 */
	private function checkSingleEnum(Enum $singleEnum)
	{
		$singleEnumClass = static::getSingleEnumClass();
		if ($singleEnumClass === null) {
			throw new \Consistence\Enum\NoSingleEnumSpecifiedException(static::class);
		}
		Type::checkType($singleEnum, $singleEnumClass);
	}

	/**
	 * @param integer $value
	 */
	private static function checkSingleValue($value)
	{
		Type::checkType($value, 'integer');
		parent::checkValue($value);
	}

	/**
	 * @param integer $value
	 */
	public static function checkValue($value)
	{
		Type::checkType($value, 'integer');
		if ($value < 0) {
			throw new \Consistence\Enum\InvalidEnumValueException($value, self::getAvailableValues());
		}
		$check = 1;
		while ($check <= $value) {
			if ($value & $check) {
				if (!static::isValidValue($check)) {
					throw new \Consistence\Enum\InvalidEnumValueException($check, self::getAvailableValues());
				}
			}
			$check <<= 1;
		}
	}

	/**
	 * @return integer[] format: const name (string) => value (integer)
	 */
	public function getValues()
	{
		return ArrayType::filterValuesByCallback(self::getAvailableValues(), function ($value) {
			return $this->containsValue($value);
		});
	}

	/**
	 * @return \Consistence\Enum\Enum[]
	 */
	public function getEnums()
	{
		$singleEnumClass = static::getSingleEnumClass();
		if ($singleEnumClass === null) {
			throw new \Consistence\Enum\NoSingleEnumSpecifiedException(static::class);
		}

		return ArrayType::mapValuesByCallback($this->getValues(), function ($value) {
			return static::convertValueToSingleEnum($value);
		});
	}

	/**
	 * Iterates trough single Enums (if defined)
	 *
	 * @return \ArrayIterator
	 */
	public function getIterator()
	{
		return new ArrayIterator($this->getEnums());
	}

	/**
	 * @param self $that
	 * @return boolean
	 */
	public function contains(self $that)
	{
		$this->checkSameEnum($that);

		return $this->containsBitwise($this->getValue(), $that->getValue());
	}

	/**
	 * @param \Consistence\Enum\Enum $singleEnum
	 * @return boolean
	 */
	public function containsEnum(Enum $singleEnum)
	{
		$this->checkSingleEnum($singleEnum);

		return $this->containsBitwise($this->getValue(), static::convertSingleEnumToValue($singleEnum));
	}

	/**
	 * @param integer $value
	 * @return boolean
	 */
	public function containsValue($value)
	{
		self::checkSingleValue($value);

		return $this->containsBitwise($this->getValue(), $value);
	}

	/**
	 * @param integer $a
	 * @param integer $b
	 * @return boolean
	 */
	private function containsBitwise($a, $b)
	{
		return ($a & $b) === $b;
	}

	/**
	 * @return boolean
	 */
	public function isEmpty()
	{
		return $this->getValue() === 0;
	}

	/**
	 * @param static $that
	 * @return static different instance of enum
	 */
	public function add(self $that)
	{
		$this->checkSameEnum($that);

		return static::get($this->addBitwise($this->getValue(), $that->getValue()));
	}

	/**
	 * @param \Consistence\Enum\Enum $singleEnum
	 * @return static different instance of enum
	 */
	public function addEnum(Enum $singleEnum)
	{
		$this->checkSingleEnum($singleEnum);

		return static::get($this->addBitwise($this->getValue(), static::convertSingleEnumToValue($singleEnum)));
	}

	/**
	 * @param integer $value
	 * @return static different instance of enum
	 */
	public function addValue($value)
	{
		self::checkSingleValue($value);

		return static::get($this->addBitwise($this->getValue(), $value));
	}

	/**
	 * @param integer $a
	 * @param integer $b
	 * @return integer
	 */
	private function addBitwise($a, $b)
	{
		return $a | $b;
	}

	/**
	 * @param static $that
	 * @return static different instance of enum
	 */
	public function remove(self $that)
	{
		$this->checkSameEnum($that);

		return static::get($this->removeBitwise($this->getValue(), $that->getValue()));
	}

	/**
	 * @param \Consistence\Enum\Enum $singleEnum
	 * @return static different instance of enum
	 */
	public function removeEnum(Enum $singleEnum)
	{
		$this->checkSingleEnum($singleEnum);

		return static::get($this->removeBitwise($this->getValue(), static::convertSingleEnumToValue($singleEnum)));
	}

	/**
	 * @param integer $value
	 * @return static different instance of enum
	 */
	public function removeValue($value)
	{
		self::checkSingleValue($value);

		return static::get($this->removeBitwise($this->getValue(), $value));
	}

	/**
	 * @param integer $a
	 * @param integer $b
	 * @return integer
	 */
	private function removeBitwise($a, $b)
	{
		return $a & (~ $b);
	}

	/**
	 * @param static $that
	 * @return static different instance of enum
	 */
	public function intersect(self $that)
	{
		$this->checkSameEnum($that);

		return static::get($this->intersectBitwise($this->getValue(), $that->getValue()));
	}

	/**
	 * @param \Consistence\Enum\Enum $singleEnum
	 * @return static different instance of enum
	 */
	public function intersectEnum(Enum $singleEnum)
	{
		$this->checkSingleEnum($singleEnum);

		return static::get($this->intersectBitwise($this->getValue(), static::convertSingleEnumToValue($singleEnum)));
	}

	/**
	 * @param integer $value
	 * @return static different instance of enum
	 */
	public function intersectValue($value)
	{
		self::checkSingleValue($value);

		return static::get($this->intersectBitwise($this->getValue(), $value));
	}

	/**
	 * @param integer $a
	 * @param integer $b
	 * @return integer
	 */
	private function intersectBitwise($a, $b)
	{
		return $a & $b;
	}

	/**
	 * Create new MultiEnum from current single Enums (if defined) filtered by callback(\Consistence\Enum\Enum)
	 *
	 * @param \Closure $callback
	 * @return static
	 */
	public function filter(Closure $callback)
	{
		return static::getMultiByEnums(ArrayType::filterValuesByCallback($this->getEnums(), $callback));
	}

	/**
	 * Create new MultiEnum from current values filtered by callback(mixed)
	 *
	 * @param \Closure $callback
	 * @return static
	 */
	public function filterValues(Closure $callback)
	{
		return static::getMultiByArray(ArrayType::filterValuesByCallback($this->getValues(), $callback));
	}

}
