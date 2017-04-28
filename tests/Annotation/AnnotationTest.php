<?php

declare(strict_types = 1);

namespace Consistence\Annotation;

class AnnotationTest extends \Consistence\TestCase
{

	public function testCreateNoParams()
	{
		$annotation = Annotation::createAnnotationWithoutParams('lorem');
		$this->assertSame('lorem', $annotation->getName());
		$this->assertEmpty($annotation->getFields());
		$this->assertNull($annotation->getValue());
	}

	public function testCreateAnnotationWithValue()
	{
		$annotation = Annotation::createAnnotationWithValue('lorem', 'ipsum');
		$this->assertSame('lorem', $annotation->getName());
		$this->assertEmpty($annotation->getFields());
		$this->assertSame('ipsum', $annotation->getValue());
	}

	public function testCreateAnnotationWithFields()
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

	public function testGetField()
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

	public function testGetMissingField()
	{
		$annotation = Annotation::createAnnotationWithoutParams('lorem');

		$this->expectException(\Consistence\Annotation\AnnotationFieldNotFoundException::class);
		$this->expectExceptionMessage('\'test\' not found');

		$annotation->getField('test');
	}

	public function testGetMissingFieldValues()
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
