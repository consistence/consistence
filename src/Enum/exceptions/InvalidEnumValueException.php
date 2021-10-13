<?php

declare(strict_types = 1);

namespace Consistence\Enum;

use Consistence\Type\ArrayType\ArrayType;
use Consistence\Type\Type;

class InvalidEnumValueException extends \Consistence\PhpException
{

	/** @var mixed */
	private $value;

	/** @var mixed[] */
	private $availableValues;

	/** @var string */
	private $enumClassName;

	/**
	 * @param mixed $value
	 * @param string $enumClassName
	 * @param \Throwable|null $previous
	 */
	public function __construct($value, string $enumClassName, ?\Throwable $previous = null)
	{
		if (!is_subclass_of($enumClassName, Enum::class)) {
			// @codeCoverageIgnoreStart
			// cannot be tested because it throws general exception
			throw new \Exception(sprintf(
				'"%s" is not a subclass of "%s"',
				$enumClassName,
				Enum::class
			));
			// @codeCoverageIgnoreEnd
		}

		$availableValues = $enumClassName::getAvailableValues();

		parent::__construct(sprintf(
			'%s is not a valid value for %s, accepted values: %s',
			$this->getPrintedValue($value),
			$enumClassName,
			implode(', ', $availableValues)
		), $previous);

		$this->value = $value;
		$this->availableValues = $availableValues;
		$this->enumClassName = $enumClassName;
	}

	/**
	 * @return mixed
	 */
	public function getValue()
	{
		return $this->value;
	}

	/**
	 * @return mixed[]
	 */
	public function getAvailableValues(): array
	{
		return $this->availableValues;
	}

	public function getEnumClassName(): string
	{
		return $this->enumClassName;
	}

	/**
	 * @param mixed $value
	 * @return string
	 */
	private function getPrintedValue($value): string
	{
		$valueType = Type::getType($value);
		if (is_object($value) && method_exists($value, '__toString') === false) {
			return get_class($value) . $this->getObjectHash($value);
		}
		if (ArrayType::containsValue(['array', 'null', 'resource'], $valueType)) {
			return sprintf('[%s]', $valueType);
		}
		if ($valueType === 'bool') {
			return sprintf('%s [%s]', $value ? 'true' : 'false', $valueType);
		}

		return sprintf('%s [%s]', (string) $value, $valueType);
	}

	private function getObjectHash(object $value): string
	{
		return '#' . substr(md5(spl_object_hash($value)), 0, 4);
	}

}
