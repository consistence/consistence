<?php

declare(strict_types = 1);

namespace Consistence\Reflection;

use PHPUnit\Framework\Assert;
use ReflectionClass;

class ClassReflectionTest extends \Consistence\TestCase
{

	public function testStaticConstruct(): void
	{
		$this->expectException(\Consistence\StaticClassException::class);

		new ClassReflection();
	}

	public function testGetDeclaredMethods(): void
	{
		$classReflection = new ReflectionClass(Bar::class);
		$methods = ClassReflection::getDeclaredMethods($classReflection);
		Assert::assertCount(1, $methods);
		Assert::assertSame('barMethod', $methods[0]->name);
	}

	public function testHasDeclaredMethod(): void
	{
		$classReflection = new ReflectionClass(Bar::class);
		Assert::assertTrue(ClassReflection::hasDeclaredMethod($classReflection, 'barMethod'));
		Assert::assertFalse(ClassReflection::hasDeclaredMethod($classReflection, 'fooMethod'));
		Assert::assertFalse(ClassReflection::hasDeclaredMethod($classReflection, 'xxx'));
		Assert::assertTrue(ClassReflection::hasDeclaredMethod($classReflection, 'barMethod', ClassReflection::CASE_SENSITIVE));
		Assert::assertFalse(ClassReflection::hasDeclaredMethod($classReflection, 'barmethod', ClassReflection::CASE_SENSITIVE));
	}

	public function testHasDeclaredMethodCaseSensitive(): void
	{
		$classReflection = new ReflectionClass(Bar::class);
		Assert::assertTrue(ClassReflection::hasDeclaredMethod($classReflection, 'barMethod', ClassReflection::CASE_SENSITIVE));
		Assert::assertFalse(ClassReflection::hasDeclaredMethod($classReflection, 'barmethod', ClassReflection::CASE_SENSITIVE));
		Assert::assertFalse(ClassReflection::hasDeclaredMethod($classReflection, 'xxx', ClassReflection::CASE_SENSITIVE));
	}

	public function testHasDeclaredMethodCaseInsensitive(): void
	{
		$classReflection = new ReflectionClass(Bar::class);
		Assert::assertTrue(ClassReflection::hasDeclaredMethod($classReflection, 'barMethod', ClassReflection::CASE_INSENSITIVE));
		Assert::assertTrue(ClassReflection::hasDeclaredMethod($classReflection, 'barmethod', ClassReflection::CASE_INSENSITIVE));
		Assert::assertFalse(ClassReflection::hasDeclaredMethod($classReflection, 'xxx', ClassReflection::CASE_INSENSITIVE));
	}

	public function testGetDeclaredProperties(): void
	{
		$classReflection = new ReflectionClass(Bar::class);
		$properties = ClassReflection::getDeclaredProperties($classReflection);
		Assert::assertCount(1, $properties);
		Assert::assertSame('bar', $properties[0]->name);
	}

	public function testHasDeclaredProperty(): void
	{
		$classReflection = new ReflectionClass(Bar::class);
		Assert::assertTrue(ClassReflection::hasDeclaredProperty($classReflection, 'bar'));
		Assert::assertFalse(ClassReflection::hasDeclaredProperty($classReflection, 'foo'));
		Assert::assertFalse(ClassReflection::hasDeclaredProperty($classReflection, 'xxx'));
	}

	public function testGetDeclaredConstants(): void
	{
		$classReflection = new ReflectionClass(Bar::class);
		$constants = ClassReflection::getDeclaredConstants($classReflection);
		Assert::assertCount(1, $constants);
		Assert::assertSame('BAR', $constants[0]->name);
	}

	public function testHasDeclaredConstant(): void
	{
		$classReflection = new ReflectionClass(Bar::class);
		Assert::assertTrue(ClassReflection::hasDeclaredConstant($classReflection, 'BAR'));
		Assert::assertFalse(ClassReflection::hasDeclaredConstant($classReflection, 'FOO'));
		Assert::assertFalse(ClassReflection::hasDeclaredConstant($classReflection, 'XXX'));
	}

}
