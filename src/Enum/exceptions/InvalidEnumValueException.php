<?php

namespace Consistence\Enum;

use Consistence\Type\Type;

class InvalidEnumValueException extends \Consistence\PhpException implements \Consistence\Enum\Exception
{

	/** @var mixed */
	private $value;

	/** @var mixed[] */
	private $availableValues;

	/**
	 * @param mixed $value
	 * @param mixed[] $availableValues
	 * @param \Exception|null $previous
	 */
	public function __construct($value, array $availableValues, \Exception $previous = null)
	{
		parent::__construct(sprintf(
			'%s [%s] is not a valid value, accepted values: %s',
			$value,
			Type::getType($value),
			implode(', ', $availableValues)
		), $previous);
		$this->value = $value;
		$this->availableValues = $availableValues;
	}

	/**
	 * @return mixed
	 */
	public function getValue()
	{
		return $this->value;
	}

	/**
	 * @return mixed[] format: const name (string) => value (mixed)
	 */
	public function getAvailableValues()
	{
		return $this->availableValues;
	}

}
