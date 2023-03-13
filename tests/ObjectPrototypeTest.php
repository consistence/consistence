<?php

declare(strict_types = 1);

namespace Consistence;

use PHPUnit\Framework\Assert;

class ObjectPrototypeTest extends \PHPUnit\Framework\TestCase
{

	public function testMagicCall(): void
	{
		$object = new ConsistenceObjectPrototypeMock();

		try {
			$object->getFoo();
			Assert::fail('Exception expected');
		} catch (\Consistence\UndefinedMethodException $e) {
			Assert::assertSame(ConsistenceObjectPrototypeMock::class, $e->getClassName());
			Assert::assertSame('getFoo', $e->getMethodName());
		}
	}

	public function testMagicCallStatic(): void
	{
		try {
			ConsistenceObjectPrototypeMock::doStatic();
			Assert::fail('Exception expected');
		} catch (\Consistence\UndefinedMethodException $e) {
			Assert::assertSame(ConsistenceObjectPrototypeMock::class, $e->getClassName());
			Assert::assertSame('doStatic', $e->getMethodName());
		}
	}

	public function testMagicGet(): void
	{
		$object = new ConsistenceObjectPrototypeMock();

		try {
			$object->foo;
			Assert::fail('Exception expected');
		} catch (\Consistence\UndefinedPropertyException $e) {
			Assert::assertSame(ConsistenceObjectPrototypeMock::class, $e->getClassName());
			Assert::assertSame('foo', $e->getPropertyName());
		}
	}

	public function testMagicSet(): void
	{
		$object = new ConsistenceObjectPrototypeMock();

		try {
			$object->foo = 'bar';
			Assert::fail('Exception expected');
		} catch (\Consistence\UndefinedPropertyException $e) {
			Assert::assertSame(ConsistenceObjectPrototypeMock::class, $e->getClassName());
			Assert::assertSame('foo', $e->getPropertyName());
		}
	}

	public function testMagicIsset(): void
	{
		$object = new ConsistenceObjectPrototypeMock();

		try {
			isset($object->foo);
			Assert::fail('Exception expected');
		} catch (\Consistence\UndefinedPropertyException $e) {
			Assert::assertSame(ConsistenceObjectPrototypeMock::class, $e->getClassName());
			Assert::assertSame('foo', $e->getPropertyName());
		}
	}

	public function testMagicUnset(): void
	{
		$object = new ConsistenceObjectPrototypeMock();

		try {
			unset($object->foo);
			Assert::fail('Exception expected');
		} catch (\Consistence\UndefinedPropertyException $e) {
			Assert::assertSame(ConsistenceObjectPrototypeMock::class, $e->getClassName());
			Assert::assertSame('foo', $e->getPropertyName());
		}
	}

}
