<?php

namespace Consistence;

class UndefinedMethodException extends \Consistence\PhpException implements \Consistence\Exception
{

	/** @var string */
	private $className;

	/** @var string */
	private $methodName;

	/**
	 * @param string $className
	 * @param string $methodName
	 * @param \Exception|null $previous
	 */
	public function __construct($className, $methodName, \Exception $previous = null)
	{
		parent::__construct(sprintf('Method %s::%s() is not defined or is not accessible', $className, $methodName), $previous);
		$this->className = $className;
		$this->methodName = $methodName;
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
	public function getMethodName()
	{
		return $this->methodName;
	}

}
