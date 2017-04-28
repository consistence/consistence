<?php

declare(strict_types = 1);

namespace Consistence\Annotation;

class AnnotationField extends \Consistence\ObjectPrototype
{

	/** @var string */
	private $name;

	/** @var mixed */
	private $value;

	/**
	 * @param string $name
	 * @param mixed|null $value
	 */
	public function __construct(string $name, $value = null)
	{
		$this->name = $name;
		$this->value = $value;
	}

	public function getName(): string
	{
		return $this->name;
	}

	/**
	 * @return mixed|null
	 */
	public function getValue()
	{
		return $this->value;
	}

}
