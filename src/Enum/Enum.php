<?php

declare(strict_types = 1);

namespace Consistence\Enum;

use Consistence\Reflection\ClassReflection;
use Consistence\Type\ArrayType\ArrayType;
use Consistence\Type\ArrayType\KeyValuePair;
use Consistence\Type\Type;
use ReflectionClass;
use ReflectionClassConstant;

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
	public static function get($value): self
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
	private static function getValueIndex($value): string
	{
		$type = Type::getType($value);
		return $value . sprintf('[%s]', $type);
	}

	/**
	 * @return mixed[]
	 */
	public static function getAvailableValues(): iterable
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
	 * @return static[]
	 */
	public static function getAvailableEnums(): iterable
	{
		$values = static::getAvailableValues();
		return ArrayType::mapByCallback($values, function (KeyValuePair $pair) {
			return new KeyValuePair($pair->getKey(), static::get($pair->getValue()));
		});
	}

	/**
	 * @return mixed[] format: const name (string) => value (mixed)
	 */
	private static function getEnumConstants(): array
	{
		$classReflection = new ReflectionClass(get_called_class());
		$declaredConstants = ClassReflection::getDeclaredConstants($classReflection);
		$declaredPublicConstants = ArrayType::filterValuesByCallback(
			$declaredConstants,
			function (ReflectionClassConstant $constant): bool {
				return $constant->isPublic();
			}
		);

		return ArrayType::mapByCallback(
			$declaredPublicConstants,
			function (KeyValuePair $keyValuePair): KeyValuePair {
				$constant = $keyValuePair->getValue();
				assert($constant instanceof ReflectionClassConstant);

				return new KeyValuePair(
					$constant->getName(),
					$constant->getValue()
				);
			}
		);
	}

	/**
	 * @param mixed[] $availableValues
	 */
	protected static function checkAvailableValues(iterable $availableValues): void
	{
		$index = [];
		foreach ($availableValues as $value) {
			Type::checkType($value, 'int|string|float|bool|null');
			$key = self::getValueIndex($value);
			if (isset($index[$key])) {
				throw new \Consistence\Enum\DuplicateValueSpecifiedException($value, static::class);
			}
			$index[$key] = true;
		}
	}

	/**
	 * @param mixed $value
	 * @return bool
	 */
	public static function isValidValue($value): bool
	{
		return ArrayType::containsValue(static::getAvailableValues(), $value);
	}

	/**
	 * @param mixed $value
	 */
	public static function checkValue($value): void
	{
		if (!static::isValidValue($value)) {
			throw new \Consistence\Enum\InvalidEnumValueException($value, static::class);
		}
	}

	protected function checkSameEnum(self $that): void
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

	public function equals(self $that): bool
	{
		$this->checkSameEnum($that);

		return $this->equalsValue($that->getValue());
	}

	/**
	 * @param mixed $value
	 * @return bool
	 */
	public function equalsValue($value): bool
	{
		return $this->getValue() === $value;
	}

}
