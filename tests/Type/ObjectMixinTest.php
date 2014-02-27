<?php

namespace Consistence\Type;

use stdClass;

class ObjectMixinTest extends \Consistence\TestCase
{

	public function testStaticConstruct()
	{
		$this->expectException(\Consistence\StaticClassException::class);

		new ObjectMixin();
	}

	public function testMagicCall()
	{
		$this->expectException(\Consistence\UndefinedMethodException::class);
		$this->expectExceptionMessage('stdClass::getFoo()');

		ObjectMixin::magicCall(new stdClass(), 'getFoo');
	}

	public function testMagicCallCatch()
	{
		try {
			ObjectMixin::magicCall(new stdClass(), 'getFoo');
			$this->fail();
		} catch (\Consistence\UndefinedMethodException $e) {
			$this->assertSame(stdClass::class, $e->getClassName());
			$this->assertSame('getFoo', $e->getMethodName());
		}
	}

	public function testMagicCallStatic()
	{
		$this->expectException(\Consistence\UndefinedMethodException::class);
		$this->expectExceptionMessage('stdClass::doStatic()');

		ObjectMixin::magicCallStatic(stdClass::class, 'doStatic');
	}

	public function testMagicCallStaticCatch()
	{
		try {
			ObjectMixin::magicCallStatic(stdClass::class, 'doStatic');
			$this->fail();
		} catch (\Consistence\UndefinedMethodException $e) {
			$this->assertSame(stdClass::class, $e->getClassName());
			$this->assertSame('doStatic', $e->getMethodName());
		}
	}

	public function testMagicGet()
	{
		$this->expectException(\Consistence\UndefinedPropertyException::class);
		$this->expectExceptionMessage('stdClass::$foo');

		ObjectMixin::magicGet(new stdClass(), 'foo');
	}

	public function testMagicGetCatch()
	{
		try {
			ObjectMixin::magicGet(new stdClass(), 'foo');
			$this->fail();
		} catch (\Consistence\UndefinedPropertyException $e) {
			$this->assertSame(stdClass::class, $e->getClassName());
			$this->assertSame('foo', $e->getPropertyName());
		}
	}

	public function testMagicSet()
	{
		$this->expectException(\Consistence\UndefinedPropertyException::class);
		$this->expectExceptionMessage('stdClass::$foo');

		ObjectMixin::magicSet(new stdClass(), 'foo');
	}

	public function testMagicSetCatch()
	{
		try {
			ObjectMixin::magicSet(new stdClass(), 'foo');
			$this->fail();
		} catch (\Consistence\UndefinedPropertyException $e) {
			$this->assertSame(stdClass::class, $e->getClassName());
			$this->assertSame('foo', $e->getPropertyName());
		}
	}

	public function testMagicIsset()
	{
		$this->expectException(\Consistence\UndefinedPropertyException::class);
		$this->expectExceptionMessage('stdClass::$foo');

		ObjectMixin::magicIsSet(new stdClass(), 'foo');
	}

	public function testMagicIsSetCatch()
	{
		try {
			ObjectMixin::magicIsSet(new stdClass(), 'foo');
			$this->fail();
		} catch (\Consistence\UndefinedPropertyException $e) {
			$this->assertSame(stdClass::class, $e->getClassName());
			$this->assertSame('foo', $e->getPropertyName());
		}
	}

	public function testMagicUnset()
	{
		$this->expectException(\Consistence\UndefinedPropertyException::class);
		$this->expectExceptionMessage('stdClass::$foo');

		ObjectMixin::magicUnset(new stdClass(), 'foo');
	}

	public function testMagicUnsetCatch()
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
