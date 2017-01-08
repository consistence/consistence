<?php

namespace Consistence\Enum;

class OperationSupportedOnlyForSameEnumException extends \Consistence\PhpException implements \Consistence\Enum\Exception
{

	/** @var \Consistence\Enum\Enum */
	private $given;

	/** @var \Consistence\Enum\Enum */
	private $expected;

	public function __construct(Enum $given, Enum $expected, \Exception $previous = null)
	{
		parent::__construct(sprintf(
			'Operation supported only for enum of same class: %s given, %s expected',
			get_class($given),
			get_class($expected)
		), $previous);
		$this->given = $given;
		$this->expected = $expected;
	}

	/**
	 * @return \Consistence\Enum\Enum
	 */
	public function getGiven()
	{
		return $this->given;
	}

	/**
	 * @return \Consistence\Enum\Enum
	 */
	public function getExpected()
	{
		return $this->expected;
	}

}
