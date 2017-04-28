<?php

declare(strict_types = 1);

namespace Consistence;

class UndefinedMethodException extends \Consistence\PhpException
{

	/** @var string */
	private $className;

	/** @var string */
	private $methodName;

	public function __construct(string $className, string $methodName, \Throwable $previous = null)
	{
		parent::__construct(sprintf('Method %s::%s() is not defined or is not accessible', $className, $methodName), $previous);
		$this->className = $className;
		$this->methodName = $methodName;
	}

	public function getClassName(): string
	{
		return $this->className;
	}

	public function getMethodName(): string
	{
		return $this->methodName;
	}

}
