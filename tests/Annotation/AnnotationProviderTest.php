<?php

declare(strict_types = 1);

namespace Consistence\Annotation;

use ReflectionProperty;

class AnnotationProviderTest extends \Consistence\TestCase
{

	public function testGetAnnotation()
	{
		$property = new ReflectionProperty(Foo::class, 'foo');
		$annotationProvider = $this->createMock(AnnotationProvider::class);
		$annotationProvider
			->expects($this->once())
			->method('getPropertyAnnotation')
			->with($property, 'test')
			->will($this->returnValue(Annotation::createAnnotationWithoutParams('test')));

		$annotation = $annotationProvider->getPropertyAnnotation($property, 'test');
		$this->assertInstanceOf(Annotation::class, $annotation);
	}

	public function testGetMissingAnnotation()
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

	public function testGetMissingAnnotationValues()
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

			$this->fail();
		} catch (\Consistence\Annotation\AnnotationNotFoundException $e) {
			$this->assertSame('test', $e->getAnnotationName());
			$this->assertSame($property, $e->getProperty());
		}
	}

	public function testGetAnnotations()
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
		$this->assertCount(2, $annotations);
	}

}
