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
		try {
			ObjectMixin::magicCall(new stdClass(), 'getFoo');
			Assert::fail('Exception expected');
		} catch (\Consistence\UndefinedMethodException $e) {
			Assert::assertSame(stdClass::class, $e->getClassName());
			Assert::assertSame('getFoo', $e->getMethodName());
		}
	}

	public function testMagicCallStatic(): void
	{
		try {
			ObjectMixin::magicCallStatic(stdClass::class, 'doStatic');
			Assert::fail('Exception expected');
		} catch (\Consistence\UndefinedMethodException $e) {
			Assert::assertSame(stdClass::class, $e->getClassName());
			Assert::assertSame('doStatic', $e->getMethodName());
		}
	}

	public function testMagicGet(): void
	{
		try {
			ObjectMixin::magicGet(new stdClass(), 'foo');
			Assert::fail('Exception expected');
		} catch (\Consistence\UndefinedPropertyException $e) {
			Assert::assertSame(stdClass::class, $e->getClassName());
			Assert::assertSame('foo', $e->getPropertyName());
		}
	}

	public function testMagicSet(): void
	{
		try {
			ObjectMixin::magicSet(new stdClass(), 'foo');
			Assert::fail('Exception expected');
		} catch (\Consistence\UndefinedPropertyException $e) {
			Assert::assertSame(stdClass::class, $e->getClassName());
			Assert::assertSame('foo', $e->getPropertyName());
		}
	}

	public function testMagicIsSet(): void
	{
		try {
			ObjectMixin::magicIsSet(new stdClass(), 'foo');
			Assert::fail('Exception expected');
		} catch (\Consistence\UndefinedPropertyException $e) {
			Assert::assertSame(stdClass::class, $e->getClassName());
			Assert::assertSame('foo', $e->getPropertyName());
		}
	}

	public function testMagicUnset(): void
	{
		try {
			ObjectMixin::magicUnset(new stdClass(), 'foo');
			Assert::fail('Exception expected');
		} catch (\Consistence\UndefinedPropertyException $e) {
			Assert::assertSame(stdClass::class, $e->getClassName());
			Assert::assertSame('foo', $e->getPropertyName());
		}
	}

}
