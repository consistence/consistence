<?php

declare(strict_types = 1);

namespace Consistence\Enum;

class InvalidEnumConstantException extends \Consistence\PhpException
{

	/** @var mixed */
	private $value;

	/** @var string */
	private $class;

	/** @var mixed[] */
	private $availableValues;

	/**
	 * @param mixed $value
	 * @param string $class
	 * @param array $availableConstants
	 * @param \Throwable|null $previous
	 */
	public function __construct($value, string $class, array $availableConstants, \Throwable $previous = null)
	{
		parent::__construct(sprintf(
			'%s is not a valid constant name of class %s, accepted values: %s',
			$value,
			$class,
			implode(', ', $availableConstants)
		), $previous);
		$this->value = $value;
		$this->class = $class;
		$this->availableValues = $availableConstants;
	}

	/**
	 * @return mixed
	 */
	public function getValue()
	{
		return $this->value;
	}

	public function getClass(): string
	{
		return $this->class;
	}

	/**
	 * @return mixed[]
	 */
	public function getAvailableValues(): array
	{
		return $this->availableValues;
	}

}
