<?php

declare(strict_types = 1);

namespace Consistence\Annotation;

use ReflectionProperty;

interface AnnotationProvider
{

	/**
	 * @param \ReflectionProperty $property
	 * @param string $annotationName
	 * @return \Consistence\Annotation\Annotation
	 * @throws \Consistence\Annotation\AnnotationNotFoundException
	 */
	public function getPropertyAnnotation(ReflectionProperty $property, string $annotationName): Annotation;

	/**
	 * @param \ReflectionProperty $property
	 * @param string $annotationName
	 * @return \Consistence\Annotation\Annotation[]
	 */
	public function getPropertyAnnotations(ReflectionProperty $property, string $annotationName);

}
