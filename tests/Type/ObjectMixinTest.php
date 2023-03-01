<?php

declare(strict_types = 1);

namespace Consistence\Type;

use stdClass;

class ObjectMixinTest extends \Consistence\TestCase
{

	public function testStaticConstruct(): void
	{
		$this->expectException(\Consistence\StaticClassException::class);

		new ObjectMixin();
	}

	public function testMagicCall(): void
	{
		try {
			ObjectMixin::magicCall(new stdClass(), 'getFoo');
			$this->fail();
		} catch (\Consistence\UndefinedMethodException $e) {
			$this->assertSame(stdClass::class, $e->getClassName());
			$this->assertSame('getFoo', $e->getMethodName());
		}
	}

	public function testMagicCallStatic(): void
	{
		try {
			ObjectMixin::magicCallStatic(stdClass::class, 'doStatic');
			$this->fail();
		} catch (\Consistence\UndefinedMethodException $e) {
			$this->assertSame(stdClass::class, $e->getClassName());
			$this->assertSame('doStatic', $e->getMethodName());
		}
	}

	public function testMagicGet(): void
	{
		try {
			ObjectMixin::magicGet(new stdClass(), 'foo');
			$this->fail();
		} catch (\Consistence\UndefinedPropertyException $e) {
			$this->assertSame(stdClass::class, $e->getClassName());
			$this->assertSame('foo', $e->getPropertyName());
		}
	}

	public function testMagicSet(): void
	{
		try {
			ObjectMixin::magicSet(new stdClass(), 'foo');
			$this->fail();
		} catch (\Consistence\UndefinedPropertyException $e) {
			$this->assertSame(stdClass::class, $e->getClassName());
			$this->assertSame('foo', $e->getPropertyName());
		}
	}

	public function testMagicIsSet(): void
	{
		try {
			ObjectMixin::magicIsSet(new stdClass(), 'foo');
			$this->fail();
		} catch (\Consistence\UndefinedPropertyException $e) {
			$this->assertSame(stdClass::class, $e->getClassName());
			$this->assertSame('foo', $e->getPropertyName());
		}
	}

	public function testMagicUnset(): void
	{
		try {
			ObjectMixin::magicUnset(new stdClass(), 'foo');
			$this->fail();
		} catch (\Consistence\UndefinedPropertyException $e) {
			$this->assertSame(stdClass::class, $e->getClassName());
			$this->assertSame('foo', $e->getPropertyName());
		}
	}

}
