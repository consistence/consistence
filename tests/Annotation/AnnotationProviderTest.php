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
			->expects($this->once())
			->method('getPropertyAnnotation')
			->with($property, 'test')
			->will($this->returnValue(Annotation::createAnnotationWithoutParams('test')));

		$annotation = $annotationProvider->getPropertyAnnotation($property, 'test');
		Assert::assertInstanceOf(Annotation::class, $annotation);
	}

	public function testGetMissingAnnotation(): void
	{
		$property = new ReflectionProperty(Foo::class, 'foo');
		$annotationProvider = $this->createMock(AnnotationProvider::class);
		$annotationProvider
			->expects($this->once())
			->method('getPropertyAnnotation')
			->with($property, 'test')
			->will($this->throwException(new \Consistence\Annotation\AnnotationNotFoundException('test', $property)));

		$this->expectException(\Consistence\Annotation\AnnotationNotFoundException::class);
		$this->expectExceptionMessage('@test not found');

		$annotationProvider->getPropertyAnnotation($property, 'test');
	}

	public function testGetMissingAnnotationValues(): void
	{
		$property = new ReflectionProperty(Foo::class, 'foo');
		$annotationProvider = $this->createMock(AnnotationProvider::class);
		$annotationProvider
			->expects($this->once())
			->method('getPropertyAnnotation')
			->with($property, 'test')
			->will($this->throwException(new \Consistence\Annotation\AnnotationNotFoundException('test', $property)));

		try {
			$annotationProvider->getPropertyAnnotation($property, 'test');

			Assert::fail();
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
			->expects($this->once())
			->method('getPropertyAnnotations')
			->with($property, 'test')
			->will($this->returnValue([
				Annotation::createAnnotationWithoutParams('test'),
				Annotation::createAnnotationWithoutParams('test'),
			]));

		$annotations = $annotationProvider->getPropertyAnnotations($property, 'test');
		Assert::assertCount(2, $annotations);
	}

}
