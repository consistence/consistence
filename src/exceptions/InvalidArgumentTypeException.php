<?php

namespace Consistence;

use Consistence\Type\Type;

class InvalidArgumentTypeException extends \Consistence\InvalidArgumentException implements \Consistence\Exception
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
	 * @param \Exception $previous
	 */
	public function __construct($value, $expectedTypes, \Exception $previous = null)
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

	/**
	 * @return string
	 */
	public function getValueType()
	{
		return $this->valueType;
	}

	/**
	 * @return string
	 */
	public function getExpectedTypes()
	{
		return $this->expectedTypes;
	}

	/**
	 * @param mixed $value
	 * @return string
	 */
	private function getPrintedValue($value)
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
	private function getObjectHash($value)
	{
		return '#' . substr(md5(spl_object_hash($value)), 0, 4);
	}

}
