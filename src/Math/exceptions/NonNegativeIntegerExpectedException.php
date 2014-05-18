<?php

namespace Consistence\Math;

class NonNegativeIntegerExpectedException extends \Consistence\PhpException implements \Consistence\Math\Exception
{

	/** @var mixed */
	private $value;

	/**
	 * @param mixed $value
	 * @param \Exception|null $previous
	 */
	public function __construct($value, \Exception $previous = null)
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
