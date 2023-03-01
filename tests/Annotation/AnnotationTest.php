<?php

declare(strict_types = 1);

namespace Consistence\Annotation;

class AnnotationTest extends \Consistence\TestCase
{

	public function testCreateNoParams(): void
	{
		$annotation = Annotation::createAnnotationWithoutParams('lorem');
		$this->assertSame('lorem', $annotation->getName());
		$this->assertEmpty($annotation->getFields());
		$this->assertNull($annotation->getValue());
	}

	public function testCreateAnnotationWithValue(): void
	{
		$annotation = Annotation::createAnnotationWithValue('lorem', 'ipsum');
		$this->assertSame('lorem', $annotation->getName());
		$this->assertEmpty($annotation->getFields());
		$this->assertSame('ipsum', $annotation->getValue());
	}

	public function testCreateAnnotationWithFields(): void
	{
		$annotation = Annotation::createAnnotationWithFields('lorem', [
			new AnnotationField('foo', 'bar'),
		]);
		$this->assertSame('lorem', $annotation->getName());
		$this->assertCount(1, $annotation->getFields());
		$this->assertSame('foo', $annotation->getFields()[0]->getName());
		$this->assertSame('bar', $annotation->getFields()[0]->getValue());
		$this->assertNull($annotation->getValue());
	}

	public function testGetField(): void
	{
		$annotation = Annotation::createAnnotationWithFields('test', [
			new AnnotationField('lorem', 1),
			new AnnotationField('ipsum', 2),
			new AnnotationField('dolor', 3),
		]);
		$field = $annotation->getField('ipsum');
		$this->assertSame('ipsum', $field->getName());
		$this->assertSame(2, $field->getValue());
	}

	public function testGetMissingField(): void
	{
		try {
			$annotation = Annotation::createAnnotationWithoutParams('lorem');
			$annotation->getField('test');

			$this->fail();
		} catch (\Consistence\Annotation\AnnotationFieldNotFoundException $e) {
			$this->assertSame('test', $e->getFieldName());
		}
	}

}
