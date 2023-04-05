<?php

declare(strict_types = 1);

namespace Consistence\Annotation;

use PHPUnit\Framework\Assert;

class AnnotationTest extends \PHPUnit\Framework\TestCase
{

	public function testCreateNoParams(): void
	{
		$annotation = Annotation::createAnnotationWithoutParams('lorem');
		Assert::assertSame('lorem', $annotation->getName());
		Assert::assertEmpty($annotation->getFields());
		Assert::assertNull($annotation->getValue());
	}

	public function testCreateAnnotationWithValue(): void
	{
		$annotation = Annotation::createAnnotationWithValue('lorem', 'ipsum');
		Assert::assertSame('lorem', $annotation->getName());
		Assert::assertEmpty($annotation->getFields());
		Assert::assertSame('ipsum', $annotation->getValue());
	}

	public function testCreateAnnotationWithFields(): void
	{
		$annotation = Annotation::createAnnotationWithFields('lorem', [
			new AnnotationField('foo', 'bar'),
		]);
		Assert::assertSame('lorem', $annotation->getName());
		Assert::assertCount(1, $annotation->getFields());
		Assert::assertSame('foo', $annotation->getFields()[0]->getName());
		Assert::assertSame('bar', $annotation->getFields()[0]->getValue());
		Assert::assertNull($annotation->getValue());
	}

	public function testGetField(): void
	{
		$annotation = Annotation::createAnnotationWithFields('test', [
			new AnnotationField('lorem', 1),
			new AnnotationField('ipsum', 2),
			new AnnotationField('dolor', 3),
		]);
		$field = $annotation->getField('ipsum');
		Assert::assertSame('ipsum', $field->getName());
		Assert::assertSame(2, $field->getValue());
	}

	public function testGetMissingField(): void
	{
		try {
			$annotation = Annotation::createAnnotationWithoutParams('lorem');
			$annotation->getField('test');

			Assert::fail('Exception expected');
		} catch (\Consistence\Annotation\AnnotationFieldNotFoundException $e) {
			Assert::assertSame('test', $e->getFieldName());
		}
	}

}
