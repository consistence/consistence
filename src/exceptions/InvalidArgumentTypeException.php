<?php

declare(strict_types = 1);

namespace Consistence;

use Consistence\Type\Type;

class InvalidArgumentTypeException extends \Consistence\InvalidArgumentException
{

	/** @var mixed */
	private $value;

	/** @var string */
	private $valueType;

	/** @var string */
	private $expectedTypes;

	/**
	 * @param mixed $value
	 * @param string $expectedTypes
	 * @param \Throwable|null $previous
	 */
	public function __construct($value, string $expectedTypes, \Throwable $previous = null)
	{
		$this->value = $value;
		$this->valueType = Type::getType($value);
		$this->expectedTypes = $expectedTypes;
		parent::__construct(
			sprintf('%s expected, %s [%s] given', $this->expectedTypes, $this->getPrintedValue($value), $this->valueType),
			$previous
		);
	}

	/**
	 * @return mixed
	 */
	public function getValue()
	{
		return $this->value;
	}

	public function getValueType(): string
	{
		return $this->valueType;
	}

	public function getExpectedTypes(): string
	{
		return $this->expectedTypes;
	}

	/**
	 * @param mixed $value
	 * @return string
	 */
	private function getPrintedValue($value): string
	{
		$printedValue = $value;
		if (is_object($value) && method_exists($value, '__toString') === false) {
			return get_class($value) . $this->getObjectHash($value);
		}
		if (is_array($value)) {
			return '';
		}

		return (string) $printedValue;
	}

	/**
	 * @param object $value
	 * @return string
	 */
	private function getObjectHash($value): string
	{
		return '#' . substr(md5(spl_object_hash($value)), 0, 4);
	}

}
