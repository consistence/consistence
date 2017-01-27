<?php

namespace Consistence\Enum;

class MultiEnumValueIsNotPowerOfTwoException extends \Consistence\PhpException implements \Consistence\Enum\Exception
{

	/** @var integer */
	private $value;

	/** @var string */
	private $class;

	/**
	 * @param integer $value
	 * @param string $class
	 * @param \Exception|null $previous
	 */
	public function __construct($value, $class, \Exception $previous = null)
	{
		parent::__construct(sprintf(
			'Value %s in %s is not a power of two, which is needed for MultiEnum to work as expected',
			$value,
			$class
		), $previous);
		$this->value = $value;
		$this->class = $class;
	}

	/**
	 * @return integer
	 */
	public function getValue()
	{
		return $this->value;
	}

	/**
	 * @return string
	 */
	public function getClass()
	{
		return $this->class;
	}

}
