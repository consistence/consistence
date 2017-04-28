<?php

declare(strict_types = 1);

namespace Consistence\Math;

class NonNegativeIntegerExpectedException extends \Consistence\PhpException
{

	/** @var mixed */
	private $value;

	/**
	 * @param mixed $value
	 * @param \Throwable|null $previous
	 */
	public function __construct($value, \Throwable $previous = null)
	{
		parent::__construct(
			sprintf('Non-negative integer expected, [%s] given', $value),
			$previous
		);
		$this->value = $value;
	}

	/**
	 * @return mixed
	 */
	public function getValue()
	{
		return $this->value;
	}

}
