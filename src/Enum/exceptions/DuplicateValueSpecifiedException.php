<?php

declare(strict_types = 1);

namespace Consistence\Enum;

use Consistence\Type\Type;

class DuplicateValueSpecifiedException extends \Consistence\PhpException
{

	/** @var mixed */
	private $value;

	/** @var string */
	private $class;

	/**
	 * @param mixed $value
	 * @param string $class
	 * @param \Throwable|null $previous
	 */
	public function __construct($value, string $class, \Throwable $previous = null)
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

	public function getClass(): string
	{
		return $this->class;
	}

}
