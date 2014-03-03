<?php

namespace Consistence\Enum;

use Consistence\Type\ArrayType\ArrayType;
use Consistence\Type\Type;

abstract class MultiEnum extends \Consistence\Enum\Enum
{

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
	 * @param static $that
	 * @return boolean
	 */
	public function contains(self $that)
	{
		$this->checkSameEnum($that);

		return $this->containsBitwise($this->getValue(), $that->getValue());
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

}
