<?php

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
	public function getPropertyAnnotation(ReflectionProperty $property, $annotationName);

	/**
	 * @param \ReflectionProperty $property
	 * @param string $annotationName
	 * @return \Consistence\Annotation\Annotation[]
	 */
	public function getPropertyAnnotations(ReflectionProperty $property, $annotationName);

}
