<?php

declare(strict_types = 1);

namespace Consistence\Annotation;

use ReflectionProperty;

class AnnotationNotFoundException extends \Consistence\PhpException
{

	/** @var string */
	private $annotationName;

	/** @var \ReflectionProperty */
	private $property;

	public function __construct(string $annotationName, ReflectionProperty $property, \Throwable $previous = null)
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

	public function getAnnotationName(): string
	{
		return $this->annotationName;
	}

	public function getProperty(): ReflectionProperty
	{
		return $this->property;
	}

}
