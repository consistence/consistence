<?php

declare(strict_types = 1);

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
	 * @return int[] format: const name (string) => value (int)
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
	 * @param int[] $availableValues
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
	 * @param int ...$values
	 * @return static
	 */
	public static function getMulti(int ...$values): self
	{
		return self::getMultiByArray($values);
	}

	/**
	 * @param int[] $values enum values
	 * @return static
	 */
	public static function getMultiByArray(array $values): self
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
	public static function getMultiByEnum(Enum $singleEnum): self
	{
		return self::get(static::convertSingleEnumToValue($singleEnum));
	}

	/**
	 * @param \Consistence\Enum\Enum[] $singleEnums
	 * @return static
	 */
	public static function getMultiByEnums(array $singleEnums): self
	{
		return self::getMultiByArray(ArrayType::mapValuesByCallback($singleEnums, function (Enum $singleEnum): int {
			return static::convertSingleEnumToValue($singleEnum);
		}));
	}

	/**
	 * Converts value representing a value from single Enum to MultiEnum counterpart
	 *
	 * @param mixed $singleEnumValue
	 * @return int
	 */
	protected static function convertSingleEnumValueToValue($singleEnumValue): int
	{
		return $singleEnumValue;
	}

	/**
	 * Converts value representing a value from MultiEnum to single Enum counterpart
	 *
	 * @param int $value
	 * @return mixed
	 */
	protected static function convertValueToSingleEnumValue(int $value)
	{
		return $value;
	}

	/**
	 * Converts value representing part of MultiEnum to Enum instance
	 *
	 * @param int $value
	 * @return \Consistence\Enum\Enum
	 */
	protected static function convertValueToSingleEnum(int $value): Enum
	{
		$singleEnumClass = static::getSingleEnumClass();

		return $singleEnumClass::get(static::convertValueToSingleEnumValue($value));
	}

	/**
	 * Converts Enum instance to value representing part of MultiEnum
	 *
	 * @param \Consistence\Enum\Enum $singleEnum
	 * @return int
	 */
	protected static function convertSingleEnumToValue(Enum $singleEnum): int
	{
		return static::convertSingleEnumValueToValue($singleEnum->getValue());
	}

	/**
	 * @param string $singleEnumClass
	 * @return int[] format: const name (string) => value (int)
	 */
	private static function getSingleEnumMappedAvailableValues(string $singleEnumClass)
	{
		return ArrayType::mapValuesByCallback($singleEnumClass::getAvailableValues(), function ($singleEnumValue): int {
			return static::convertSingleEnumValueToValue($singleEnumValue);
		});
	}

	private function checkSingleEnum(Enum $singleEnum)
	{
		$singleEnumClass = static::getSingleEnumClass();
		if ($singleEnumClass === null) {
			throw new \Consistence\Enum\NoSingleEnumSpecifiedException(static::class);
		}
		Type::checkType($singleEnum, $singleEnumClass);
	}

	private static function checkSingleValue(int $value)
	{
		parent::checkValue($value);
	}

	/**
	 * @param int $value
	 */
	public static function checkValue($value)
	{
		Type::checkType($value, 'int');
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
	 * @return int[] format: const name (string) => value (int)
	 */
	public function getValues()
	{
		return ArrayType::filterValuesByCallback(self::getAvailableValues(), function (int $value): bool {
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

		return ArrayType::mapValuesByCallback($this->getValues(), function (int $value): Enum {
			return static::convertValueToSingleEnum($value);
		});
	}

	/**
	 * Iterates trough single Enums (if defined)
	 *
	 * @return \ArrayIterator
	 */
	public function getIterator(): ArrayIterator
	{
		return new ArrayIterator($this->getEnums());
	}

	public function contains(self $that): bool
	{
		$this->checkSameEnum($that);

		return $this->containsBitwise($this->getValue(), $that->getValue());
	}

	public function containsEnum(Enum $singleEnum): bool
	{
		$this->checkSingleEnum($singleEnum);

		return $this->containsBitwise($this->getValue(), static::convertSingleEnumToValue($singleEnum));
	}

	public function containsValue(int $value): bool
	{
		self::checkSingleValue($value);

		return $this->containsBitwise($this->getValue(), $value);
	}

	private function containsBitwise(int $a, int $b): bool
	{
		return ($a & $b) === $b;
	}

	public function isEmpty(): bool
	{
		return $this->getValue() === 0;
	}

	/**
	 * @param static $that
	 * @return static different instance of enum
	 */
	public function add(self $that): self
	{
		$this->checkSameEnum($that);

		return static::get($this->addBitwise($this->getValue(), $that->getValue()));
	}

	/**
	 * @param \Consistence\Enum\Enum $singleEnum
	 * @return static different instance of enum
	 */
	public function addEnum(Enum $singleEnum): self
	{
		$this->checkSingleEnum($singleEnum);

		return static::get($this->addBitwise($this->getValue(), static::convertSingleEnumToValue($singleEnum)));
	}

	/**
	 * @param int $value
	 * @return static different instance of enum
	 */
	public function addValue(int $value): self
	{
		self::checkSingleValue($value);

		return static::get($this->addBitwise($this->getValue(), $value));
	}

	private function addBitwise(int $a, int $b): int
	{
		return $a | $b;
	}

	/**
	 * @param static $that
	 * @return static different instance of enum
	 */
	public function remove(self $that): self
	{
		$this->checkSameEnum($that);

		return static::get($this->removeBitwise($this->getValue(), $that->getValue()));
	}

	/**
	 * @param \Consistence\Enum\Enum $singleEnum
	 * @return static different instance of enum
	 */
	public function removeEnum(Enum $singleEnum): self
	{
		$this->checkSingleEnum($singleEnum);

		return static::get($this->removeBitwise($this->getValue(), static::convertSingleEnumToValue($singleEnum)));
	}

	/**
	 * @param int $value
	 * @return static different instance of enum
	 */
	public function removeValue(int $value): self
	{
		self::checkSingleValue($value);

		return static::get($this->removeBitwise($this->getValue(), $value));
	}

	private function removeBitwise(int $a, int $b): int
	{
		return $a & (~ $b);
	}

	/**
	 * @param static $that
	 * @return static different instance of enum
	 */
	public function intersect(self $that): self
	{
		$this->checkSameEnum($that);

		return static::get($this->intersectBitwise($this->getValue(), $that->getValue()));
	}

	/**
	 * @param \Consistence\Enum\Enum $singleEnum
	 * @return static different instance of enum
	 */
	public function intersectEnum(Enum $singleEnum): self
	{
		$this->checkSingleEnum($singleEnum);

		return static::get($this->intersectBitwise($this->getValue(), static::convertSingleEnumToValue($singleEnum)));
	}

	/**
	 * @param int $value
	 * @return static different instance of enum
	 */
	public function intersectValue(int $value): self
	{
		self::checkSingleValue($value);

		return static::get($this->intersectBitwise($this->getValue(), $value));
	}

	private function intersectBitwise(int $a, int $b): int
	{
		return $a & $b;
	}

	/**
	 * Create new MultiEnum from current single Enums (if defined) filtered by callback(\Consistence\Enum\Enum)
	 *
	 * @param \Closure $callback
	 * @return static
	 */
	public function filter(Closure $callback): self
	{
		return static::getMultiByEnums(ArrayType::filterValuesByCallback($this->getEnums(), $callback));
	}

	/**
	 * Create new MultiEnum from current values filtered by callback(mixed)
	 *
	 * @param \Closure $callback
	 * @return static
	 */
	public function filterValues(Closure $callback): self
	{
		return static::getMultiByArray(ArrayType::filterValuesByCallback($this->getValues(), $callback));
	}

}
