<?php

declare(strict_types = 1);

namespace Consistence\Enum;

class MultiEnumValueIsNotPowerOfTwoException extends \Consistence\PhpException
{

	/** @var int */
	private $value;

	/** @var string */
	private $class;

	public function __construct(int $value, string $class, \Throwable $previous = null)
	{
		parent::__construct(sprintf(
			'Value %s in %s is not a power of two, which is needed for MultiEnum to work as expected',
			$value,
			$class
		), $previous);
		$this->value = $value;
		$this->class = $class;
	}

	public function getValue(): int
	{
		return $this->value;
	}

	public function getClass(): string
	{
		return $this->class;
	}

}
