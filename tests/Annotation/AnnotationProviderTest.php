<?php

declare(strict_types = 1);

namespace Consistence\Annotation;

use PHPUnit\Framework\Assert;
use ReflectionProperty;

class AnnotationProviderTest extends \PHPUnit\Framework\TestCase
{

	public function testGetAnnotation(): void
	{
		$property = new ReflectionProperty(Foo::class, 'foo');
		$annotationProvider = $this->createMock(AnnotationProvider::class);
		$annotationProvider
			->expects(self::once())
			->method('getPropertyAnnotation')
			->with($property, 'test')
			->will(self::returnValue(Annotation::createAnnotationWithoutParams('test')));

		$annotation = $annotationProvider->getPropertyAnnotation($property, 'test');
		Assert::assertInstanceOf(Annotation::class, $annotation);
	}

	public function testGetMissingAnnotation(): void
	{
		$property = new ReflectionProperty(Foo::class, 'foo');
		$annotationProvider = $this->createMock(AnnotationProvider::class);
		$annotationProvider
			->expects(self::once())
			->method('getPropertyAnnotation')
			->with($property, 'test')
			->will(self::throwException(new \Consistence\Annotation\AnnotationNotFoundException('test', $property)));

		try {
			$annotationProvider->getPropertyAnnotation($property, 'test');

			Assert::fail('Exception expected');
		} catch (\Consistence\Annotation\AnnotationNotFoundException $e) {
			Assert::assertSame('test', $e->getAnnotationName());
			Assert::assertSame($property, $e->getProperty());
		}
	}

	public function testGetAnnotations(): void
	{
		$property = new ReflectionProperty(Foo::class, 'foo');
		$annotationProvider = $this->createMock(AnnotationProvider::class);
		$annotationProvider
			->expects(self::once())
			->method('getPropertyAnnotations')
			->with($property, 'test')
			->will(self::returnValue([
				Annotation::createAnnotationWithoutParams('test'),
				Annotation::createAnnotationWithoutParams('test'),
			]));

		$annotations = $annotationProvider->getPropertyAnnotations($property, 'test');
		Assert::assertCount(2, $annotations);
	}

}
