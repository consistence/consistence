<?php

declare(strict_types = 1);

namespace Consistence\Annotation;

use PHPUnit\Framework\Assert;

class AnnotationFieldTest extends \Consistence\TestCase
{

	public function testCreate(): void
	{
		$annotationFiled = new AnnotationField('lorem', 'ipsum');
		Assert::assertSame('lorem', $annotationFiled->getName());
		Assert::assertSame('ipsum', $annotationFiled->getValue());
	}

	public function testCreateWithoutValue(): void
	{
		$annotationFiled = new AnnotationField('lorem');
		Assert::assertSame('lorem', $annotationFiled->getName());
		Assert::assertNull($annotationFiled->getValue());
	}

}
