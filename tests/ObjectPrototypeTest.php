<?php

declare(strict_types = 1);

namespace Consistence;

class ObjectPrototypeTest extends \PHPUnit\Framework\TestCase
{

	public function testMagicCall(): void
	{
		$object = new ConsistenceObjectPrototypeMock();

		$this->expectException(\Consistence\UndefinedMethodException::class);
		$this->expectExceptionMessage('ConsistenceObjectPrototypeMock::getFoo()');

		$object->getFoo();
	}

	public function testMagicCallStatic(): void
	{
		$this->expectException(\Consistence\UndefinedMethodException::class);
		$this->expectExceptionMessage('ConsistenceObjectPrototypeMock::doStatic()');

		ConsistenceObjectPrototypeMock::doStatic();
	}

	public function testMagicGet(): void
	{
		$object = new ConsistenceObjectPrototypeMock();

		$this->expectException(\Consistence\UndefinedPropertyException::class);
		$this->expectExceptionMessage('ConsistenceObjectPrototypeMock::$foo');

		$object->foo;
	}

	public function testMagicSet(): void
	{
		$object = new ConsistenceObjectPrototypeMock();

		$this->expectException(\Consistence\UndefinedPropertyException::class);
		$this->expectExceptionMessage('ConsistenceObjectPrototypeMock::$foo');

		$object->foo = 'bar';
	}

	public function testMagicIsset(): void
	{
		$object = new ConsistenceObjectPrototypeMock();

		$this->expectException(\Consistence\UndefinedPropertyException::class);
		$this->expectExceptionMessage('ConsistenceObjectPrototypeMock::$foo');

		isset($object->foo);
	}

	public function testMagicUnset(): void
	{
		$object = new ConsistenceObjectPrototypeMock();

		$this->expectException(\Consistence\UndefinedPropertyException::class);
		$this->expectExceptionMessage('ConsistenceObjectPrototypeMock::$foo');

		unset($object->foo);
	}

}
