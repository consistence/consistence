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

}
