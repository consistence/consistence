<?php

namespace Consistence\Annotation;

class AnnotationFieldTest extends \Consistence\TestCase
{

	public function testCreate()
	{
		$annotationFiled = new AnnotationField('lorem', 'ipsum');
		$this->assertSame('lorem', $annotationFiled->getName());
		$this->assertSame('ipsum', $annotationFiled->getValue());
	}

	public function testCreateWithoutValue()
	{
		$annotationFiled = new AnnotationField('lorem');
		$this->assertSame('lorem', $annotationFiled->getName());
		$this->assertNull($annotationFiled->getValue());
	}

}
