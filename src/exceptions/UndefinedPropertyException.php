<?php

declare(strict_types = 1);

namespace Consistence;

class UndefinedPropertyException extends \Consistence\PhpException
{

	/** @var string */
	private $className;

	/** @var string */
	private $propertyName;

	public function __construct(string $className, string $propertyName, \Throwable $previous = null)
	{
		parent::__construct(sprintf('Property %s::$%s is not defined or is not accessible', $className, $propertyName), $previous);
		$this->className = $className;
		$this->propertyName = $propertyName;
	}

	public function getClassName(): string
	{
		return $this->className;
	}

	public function getPropertyName(): string
	{
		return $this->propertyName;
	}

}
