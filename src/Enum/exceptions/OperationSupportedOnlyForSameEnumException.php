<?php

declare(strict_types = 1);

namespace Consistence\Enum;

class OperationSupportedOnlyForSameEnumException extends \Consistence\PhpException
{

	/** @var \Consistence\Enum\Enum */
	private $given;

	/** @var \Consistence\Enum\Enum */
	private $expected;

	public function __construct(Enum $given, Enum $expected, \Throwable $previous = null)
	{
		parent::__construct(sprintf(
			'Operation supported only for enum of same class: %s given, %s expected',
			get_class($given),
			get_class($expected)
		), $previous);
		$this->given = $given;
		$this->expected = $expected;
	}

	public function getGiven(): Enum
	{
		return $this->given;
	}

	public function getExpected(): Enum
	{
		return $this->expected;
	}

}
