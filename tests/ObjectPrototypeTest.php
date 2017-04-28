<?php

declare(strict_types = 1);

namespace Consistence;

class ObjectPrototypeTest extends \Consistence\TestCase
{

	/**
	 * @return \Consistence\ObjectPrototype[][]
	 */
	public function consistenceObjectPrototypeProvider(): array
	{
		return [
			[
				new ConsistenceObjectPrototypeMock(),
			],
		];
	}

	/**
	 * @dataProvider consistenceObjectPrototypeProvider
	 *
	 * @param \Consistence\ObjectPrototype $object
	 */
	public function testMagicCall(ObjectPrototype $object)
	{
		$this->expectException(\Consistence\UndefinedMethodException::class);
		$this->expectExceptionMessage('ConsistenceObjectPrototypeMock::getFoo()');

		$object->getFoo();
	}

	public function testMagicCallStatic()
	{
		$this->expectException(\Consistence\UndefinedMethodException::class);
		$this->expectExceptionMessage('ConsistenceObjectPrototypeMock::doStatic()');

		ConsistenceObjectPrototypeMock::doStatic();
	}

	/**
	 * @dataProvider consistenceObjectPrototypeProvider
	 *
	 * @param \Consistence\ObjectPrototype $object
	 */
	public function testMagicGet(ObjectPrototype $object)
	{
		$this->expectException(\Consistence\UndefinedPropertyException::class);
		$this->expectExceptionMessage('ConsistenceObjectPrototypeMock::$foo');

		$object->foo;
	}

	/**
	 * @dataProvider consistenceObjectPrototypeProvider
	 *
	 * @param \Consistence\ObjectPrototype $object
	 */
	public function testMagicSet(ObjectPrototype $object)
	{
		$this->expectException(\Consistence\UndefinedPropertyException::class);
		$this->expectExceptionMessage('ConsistenceObjectPrototypeMock::$foo');

		$object->foo = 'bar';
	}

	/**
	 * @dataProvider consistenceObjectPrototypeProvider
	 *
	 * @param \Consistence\ObjectPrototype $object
	 */
	public function testMagicIsset(ObjectPrototype $object)
	{
		$this->expectException(\Consistence\UndefinedPropertyException::class);
		$this->expectExceptionMessage('ConsistenceObjectPrototypeMock::$foo');

		isset($object->foo);
	}

	/**
	 * @dataProvider consistenceObjectPrototypeProvider
	 *
	 * @param \Consistence\ObjectPrototype $object
	 */
	public function testMagicUnset(ObjectPrototype $object)
	{
		$this->expectException(\Consistence\UndefinedPropertyException::class);
		$this->expectExceptionMessage('ConsistenceObjectPrototypeMock::$foo');

		unset($object->foo);
	}

}
