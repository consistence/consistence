<?php

namespace Consistence\Annotation;

use ReflectionProperty;

class AnnotationNotFoundException extends \Consistence\PhpException implements \Consistence\Annotation\Exception
{

	/** @var string */
	private $annotationName;

	/** @var \ReflectionProperty */
	private $property;

	/**
	 * @param string $annotationName
	 * @param \ReflectionProperty $property
	 * @param \Exception|null $previous
	 */
	public function __construct($annotationName, ReflectionProperty $property, \Exception $previous = null)
	{
		parent::__construct(
			sprintf(
				'Annotation @%s not found on property %s::$%s',
				$annotationName,
				$property->getDeclaringClass()->getName(),
				$property->getName()
			),
			$previous
		);
		$this->annotationName = $annotationName;
		$this->property = $property;
	}

	/**
	 * @return string
	 */
	public function getAnnotationName()
	{
		return $this->annotationName;
	}

	/**
	 * @return \ReflectionProperty
	 */
	public function getProperty()
	{
		return $this->property;
	}

}
