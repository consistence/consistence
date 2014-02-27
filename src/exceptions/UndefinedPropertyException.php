<?php

namespace Consistence;

class UndefinedPropertyException extends \Consistence\PhpException implements \Consistence\Exception
{

	/** @var string */
	private $className;

	/** @var string */
	private $propertyName;

	/**
	 * @param string $className
	 * @param string $propertyName
	 * @param \Exception|null $previous
	 */
	public function __construct($className, $propertyName, \Exception $previous = null)
	{
		parent::__construct(sprintf('Property %s::$%s is not defined or is not accessible', $className, $propertyName), $previous);
		$this->className = $className;
		$this->propertyName = $propertyName;
	}

	/**
	 * @return string
	 */
	public function getClassName()
	{
		return $this->className;
	}

	/**
	 * @return string
	 */
	public function getPropertyName()
	{
		return $this->propertyName;
	}

}
