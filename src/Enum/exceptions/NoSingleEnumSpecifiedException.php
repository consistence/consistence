<?php

declare(strict_types = 1);

namespace Consistence\Enum;

class NoSingleEnumSpecifiedException extends \Consistence\PhpException
{

	/** @var string */
	private $class;

	public function __construct(string $class, \Throwable $previous = null)
	{
		parent::__construct(sprintf(
			'There is no single Enum (implementing %s) defined for MultiEnum %s',
			Enum::class,
			$class
		), $previous);
		$this->class = $class;
	}

	public function getClass(): string
	{
		return $this->class;
	}

}
