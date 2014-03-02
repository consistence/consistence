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

	public function testGetValue()
	{
		$values = [
			'foo',
			'bar',
		];
		$this->assertSame('bar', ArrayType::getValue($values, 1));
	}

	public function testGetValueNotFound()
	{
		$values = [
			'foo',
			'bar',
		];

		$this->expectException(\Consistence\Type\ArrayType\ElementDoesNotExistException::class);

		ArrayType::getValue($values, 2);
	}

	public function testFindByCallback()
	{
		$values = [1, 2, 3];
		$result = ArrayType::findByCallback($values, function (KeyValuePair $pair) {
			return ($pair->getValue() % 2) === 0;
		});
		$this->assertInstanceOf(KeyValuePair::class, $result);
		$this->assertSame(2, $result->getValue());
		$this->assertSame(1, $result->getKey());
	}

	public function testFindByCallbackNothingFound()
	{
		$values = [1, 2, 3];
		$result = ArrayType::findByCallback($values, function (KeyValuePair $pair) {
			return $pair->getValue() > 3;
		});
		$this->assertNull($result);
	}

	public function testFindValueByCallback()
	{
		$values = [1, 2, 3];
		$result = ArrayType::findValueByCallback($values, function ($value) {
			return ($value % 2) === 0;
		});
		$this->assertSame(2, $result);
	}

	public function testFindValueByCallbackNothingFound()
	{
		$values = [1, 2, 3];
		$result = ArrayType::findValueByCallback($values, function ($value) {
			return $value > 3;
		});
		$this->assertNull($result);
	}

	public function testGetByCallback()
	{
		$values = [1, 2, 3];
		$result = ArrayType::getByCallback($values, function (KeyValuePair $pair) {
			return ($pair->getValue() % 2) === 0;
		});
		$this->assertInstanceOf(KeyValuePair::class, $result);
		$this->assertSame(2, $result->getValue());
		$this->assertSame(1, $result->getKey());
	}

	public function testGetByCallbackNothingFound()
	{
		$values = [1, 2, 3];

		$this->expectException(\Consistence\Type\ArrayType\ElementDoesNotExistException::class);

		ArrayType::getByCallback($values, function (KeyValuePair $pair) {
			return $pair->getValue() > 3;
		});
	}

	public function testGetValueByCallback()
	{
		$values = [1, 2, 3];
		$result = ArrayType::getValueByCallback($values, function ($value) {
			return ($value % 2) === 0;
		});
		$this->assertSame(2, $result);
	}

	public function testGetValueByCallbackNothingFound()
	{
		$values = [1, 2, 3];

		$this->expectException(\Consistence\Type\ArrayType\ElementDoesNotExistException::class);

		ArrayType::getValueByCallback($values, function ($value) {
			return $value > 3;
		});
	}

	public function testFilterByCallback()
	{
		$values = [1, 2, 3];
		$result = ArrayType::filterByCallback($values, function (KeyValuePair $pair) {
			return $pair->getKey() > 1;
		});
		$this->assertCount(1, $result);
		$this->assertSame(3, $result[2]);
	}

	public function testFilterValueByCallback()
	{
		$values = [1, 2, 3];
		$result = ArrayType::filterValuesByCallback($values, function ($value) {
			return ($value % 2) === 0;
		});
		$this->assertCount(1, $result);
		$this->assertSame(2, $result[1]);
	}

}
