<?php

namespace Consistence\Enum;

class NoSingleEnumSpecifiedException extends \Consistence\PhpException implements \Consistence\Enum\Exception
{

	/** @var string */
	private $class;

	/**
	 * @param string $class
	 * @param \Exception|null $previous
	 */
	public function __construct($class, \Exception $previous = null)
	{
		parent::__construct(sprintf(
			'There is no single Enum (implementing %s) defined for MultiEnum %s',
			Enum::class,
			$class
		), $previous);
		$this->class = $class;
	}

	/**
	 * @return string
	 */
	public function getClass()
	{
		return $this->class;
	}

}
