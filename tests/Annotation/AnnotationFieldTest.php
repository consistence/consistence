<?php

declare(strict_types = 1);

namespace Consistence\Annotation;

class AnnotationFieldTest extends \Consistence\TestCase
{

	public function testCreate(): void
	{
		$annotationFiled = new AnnotationField('lorem', 'ipsum');
		$this->assertSame('lorem', $annotationFiled->getName());
		$this->assertSame('ipsum', $annotationFiled->getValue());
	}

	public function testCreateWithoutValue(): void
	{
		$annotationFiled = new AnnotationField('lorem');
		$this->assertSame('lorem', $annotationFiled->getName());
		$this->assertNull($annotationFiled->getValue());
	}

}
