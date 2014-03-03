<?php

namespace Consistence\Reflection;

use ReflectionClass;

class ClassReflectionTest extends \Consistence\TestCase
{

	public function testStaticConstruct()
	{
		$this->expectException(\Consistence\StaticClassException::class);

		new ClassReflection();
	}

	public function testGetDeclaredMethods()
	{
		$classReflection = new ReflectionClass(Bar::class);
		$methods = ClassReflection::getDeclaredMethods($classReflection);
		$this->assertCount(1, $methods);
		$this->assertSame('barMethod', $methods[0]->name);
	}

	public function testHasDeclaredMethod()
	{
		$classReflection = new ReflectionClass(Bar::class);
		$this->assertTrue(ClassReflection::hasDeclaredMethod($classReflection, 'barMethod'));
		$this->assertFalse(ClassReflection::hasDeclaredMethod($classReflection, 'fooMethod'));
		$this->assertFalse(ClassReflection::hasDeclaredMethod($classReflection, 'xxx'));
	}

}
