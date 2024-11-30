<?php

declare(strict_types = 1);

namespace Consistence\Type;

use PHPUnit\Framework\Assert;
use stdClass;

class ObjectMixinTest extends \PHPUnit\Framework\TestCase
{

	public function testStaticConstruct(): void
	{
		$this->expectException(\Consistence\StaticClassException::class);

		new ObjectMixin();
	}

	public function testMagicCall(): void
	{
		$this->expectException(\Consistence\UndefinedMethodException::class);
		$this->expectExceptionMessage('stdClass::getFoo()');

		ObjectMixin::magicCall(new stdClass(), 'getFoo');
	}

	public function testMagicCallCatch(): void
	{
		try {
			ObjectMixin::magicCall(new stdClass(), 'getFoo');
			Assert::fail();
		} catch (\Consistence\UndefinedMethodException $e) {
			Assert::assertSame(stdClass::class, $e->getClassName());
			Assert::assertSame('getFoo', $e->getMethodName());
		}
	}

	public function testMagicCallStatic(): void
	{
		$this->expectException(\Consistence\UndefinedMethodException::class);
		$this->expectExceptionMessage('stdClass::doStatic()');

		ObjectMixin::magicCallStatic(stdClass::class, 'doStatic');
	}

	public function testMagicCallStaticCatch(): void
	{
		try {
			ObjectMixin::magicCallStatic(stdClass::class, 'doStatic');
			Assert::fail();
		} catch (\Consistence\UndefinedMethodException $e) {
			Assert::assertSame(stdClass::class, $e->getClassName());
			Assert::assertSame('doStatic', $e->getMethodName());
		}
	}

	public function testMagicGet(): void
	{
		$this->expectException(\Consistence\UndefinedPropertyException::class);
		$this->expectExceptionMessage('stdClass::$foo');

		ObjectMixin::magicGet(new stdClass(), 'foo');
	}

	public function testMagicGetCatch(): void
	{
		try {
			ObjectMixin::magicGet(new stdClass(), 'foo');
			Assert::fail();
		} catch (\Consistence\UndefinedPropertyException $e) {
			Assert::assertSame(stdClass::class, $e->getClassName());
			Assert::assertSame('foo', $e->getPropertyName());
		}
	}

	public function testMagicSet(): void
	{
		$this->expectException(\Consistence\UndefinedPropertyException::class);
		$this->expectExceptionMessage('stdClass::$foo');

		ObjectMixin::magicSet(new stdClass(), 'foo');
	}

	public function testMagicSetCatch(): void
	{
		try {
			ObjectMixin::magicSet(new stdClass(), 'foo');
			Assert::fail();
		} catch (\Consistence\UndefinedPropertyException $e) {
			Assert::assertSame(stdClass::class, $e->getClassName());
			Assert::assertSame('foo', $e->getPropertyName());
		}
	}

	public function testMagicIsset(): void
	{
		$this->expectException(\Consistence\UndefinedPropertyException::class);
		$this->expectExceptionMessage('stdClass::$foo');

		ObjectMixin::magicIsSet(new stdClass(), 'foo');
	}

	public function testMagicIsSetCatch(): void
	{
		try {
			ObjectMixin::magicIsSet(new stdClass(), 'foo');
			Assert::fail();
		} catch (\Consistence\UndefinedPropertyException $e) {
			Assert::assertSame(stdClass::class, $e->getClassName());
			Assert::assertSame('foo', $e->getPropertyName());
		}
	}

	public function testMagicUnset(): void
	{
		$this->expectException(\Consistence\UndefinedPropertyException::class);
		$this->expectExceptionMessage('stdClass::$foo');

		ObjectMixin::magicUnset(new stdClass(), 'foo');
	}

	public function testMagicUnsetCatch(): void
	{
		try {
			ObjectMixin::magicUnset(new stdClass(), 'foo');
			Assert::fail();
		} catch (\Consistence\UndefinedPropertyException $e) {
			Assert::assertSame(stdClass::class, $e->getClassName());
			Assert::assertSame('foo', $e->getPropertyName());
		}
	}

}
