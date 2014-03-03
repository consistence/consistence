<?php

namespace Consistence\Annotation;

use Consistence\Type\Type;

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
	public function __construct($name, $value = null)
	{
		Type::checkType($name, 'string');
		$this->name = $name;
		$this->value = $value;
	}

	/**
	 * @return string
	 */
	public function getName()
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
