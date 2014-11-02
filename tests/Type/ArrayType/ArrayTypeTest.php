<?php

namespace Consistence\Type\ArrayType;

class ArrayTypeTest extends \Consistence\TestCase
{

	public function testStaticConstruct()
	{
		$this->expectException(\Consistence\StaticClassException::class);

		new ArrayType();
	}

	public function testInArrayDefault()
	{
		$values = [1, 2, 3];
		$this->assertTrue(ArrayType::inArray($values, 2));
		$this->assertFalse(ArrayType::inArray($values, '2'));
	}

	public function testInArrayStrict()
	{
		$values = [1, 2, 3];
		$this->assertTrue(ArrayType::inArray($values, 2, ArrayType::STRICT_TRUE));
		$this->assertFalse(ArrayType::inArray($values, '2', ArrayType::STRICT_TRUE));
	}

	public function testInArrayLoose()
	{
		$values = [1, 2, 3];
		$this->assertTrue(ArrayType::inArray($values, '2', ArrayType::STRICT_FALSE));
	}

	public function testArraySearchDefault()
	{
		$values = [1, 2, 3];
		$this->assertSame(1, ArrayType::findKey($values, 2));
		$this->assertNull(ArrayType::findKey($values, '2'));
	}

	public function testArraySearchStrict()
	{
		$values = [1, 2, 3];
		$this->assertSame(1, ArrayType::findKey($values, 2, ArrayType::STRICT_TRUE));
		$this->assertNull(ArrayType::findKey($values, '2', ArrayType::STRICT_TRUE));
	}

	public function testArraySearchLoose()
	{
		$values = [1, 2, 3];
		$this->assertSame(1, ArrayType::findKey($values, '2', ArrayType::STRICT_FALSE));
	}

	public function testGetKey()
	{
		$values = [1, 2, 3];
		$this->assertSame(1, ArrayType::getKey($values, 2));
	}

	public function testGetKeyNotFound()
	{
		$values = [1, 2, 3];

		$this->expectException(\Consistence\Type\ArrayType\ElementDoesNotExistException::class);

		ArrayType::getKey($values, '2');
	}

	public function testFindValue()
	{
		$values = [
			'foo',
			'bar',
		];
		$this->assertSame('bar', ArrayType::findValue($values, 1));
	}

	public function testFindValueNotFound()
	{
		$values = [
			'foo',
			'bar',
		];
		$this->assertNull(ArrayType::findValue($values, 2));
	}

}
