<?php

namespace Consistence\Enum;

use Consistence\Type\Type;

class DuplicateValueSpecifiedException extends \Consistence\PhpException implements \Consistence\Enum\Exception
{

	/** @var mixed */
	private $value;

	/** @var string */
	private $class;

	/**
	 * @param mixed $value
	 * @param string $class
	 * @param \Exception|null $previous
	 */
	public function __construct($value, $class, \Exception $previous = null)
	{
		parent::__construct(sprintf(
			'Value %s [%s] is specified in %s\'s available values multiple times',
			$value,
			Type::getType($value),
			$class
		), $previous);
		$this->value = $value;
		$this->class = $class;
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
	public function getClass()
	{
		return $this->class;
	}

}
