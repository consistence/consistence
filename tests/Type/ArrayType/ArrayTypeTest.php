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

	public function testInArrayByCallback()
	{
		$values = [1, 2, 3];
		$this->assertTrue(ArrayType::inArrayByCallback($values, function (KeyValuePair $pair) {
			return ($pair->getValue() % 2) === 0;
		}));
	}

	public function testInArrayByCallbackLoose()
	{
		$values = [1, 2, 3];
		$this->assertTrue(ArrayType::inArrayByCallback($values, function (KeyValuePair $pair) {
			return $pair->getValue() == '2';
		}));
	}

	public function testInArrayByCallbackNotFound()
	{
		$values = [1, 2, 3];
		$this->assertFalse(ArrayType::inArrayByCallback($values, function (KeyValuePair $pair) {
			return $pair->getValue() === 0;
		}));
	}

	public function testInArrayByValueCallback()
	{
		$values = [1, 2, 3];
		$this->assertTrue(ArrayType::inArrayByValueCallback($values, function ($value) {
			return ($value % 2) === 0;
		}));
	}

	public function testInArrayByValueCallbackLoose()
	{
		$values = [1, 2, 3];
		$this->assertTrue(ArrayType::inArrayByValueCallback($values, function ($value) {
			return $value == '2';
		}));
	}

	public function testInArrayByValueCallbackNotFound()
	{
		$values = [1, 2, 3];
		$this->assertFalse(ArrayType::inArrayByValueCallback($values, function ($value) {
			return $value === 0;
		}));
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

	public function testFindKeyByCallback()
	{
		$values = [1, 2, 3];
		$this->assertSame(1, ArrayType::findKeyByCallback($values, function (KeyValuePair $pair) {
			return ($pair->getValue() % 2) === 0;
		}));
	}

	public function testFindKeyByCallbackNotFound()
	{
		$values = [1, 2, 3];
		$this->assertNull(ArrayType::findKeyByCallback($values, function (KeyValuePair $pair) {
			return $pair->getValue() === 0;
		}));
	}

	public function testFindKeyByCallbackCustomKeys()
	{
		$values = [
			'one' => 1,
			'two' => 2,
			'three' => 3
		];
		$this->assertSame('two', ArrayType::findKeyByCallback($values, function (KeyValuePair $pair) {
			return ($pair->getValue() % 2) === 0;
		}));
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

	public function testMapByCallback()
	{
		$array = [
			'foo' => 'bar',
		];
		$result = ArrayType::mapByCallback($array, function (KeyValuePair $pair) {
			return new KeyValuePair(strtoupper($pair->getKey()), strtoupper($pair->getValue()));
		});
		$this->assertSame([
			'FOO' => 'BAR',
		], $result);
	}

	public function testMapValuesByCallback()
	{
		$values = [1, 2, 3];
		$result = ArrayType::mapValuesByCallback($values, function ($value) {
			return $value * 2;
		});
		$this->assertSame([2, 4, 6], $result);
	}

	public function testRemoveValue()
	{
		$values = [1, 2, 3];
		$this->assertTrue(ArrayType::removeValue($values, 2));
		$this->assertCount(2, $values);
		$this->assertSame(1, $values[0]);
		$this->assertSame(3, $values[2]);
	}

	public function testRemoveValueNoChange()
	{
		$values = [1, 2, 3];
		$this->assertFalse(ArrayType::removeValue($values, 4));
		$this->assertCount(3, $values);
	}

	public function testRemoveKeys()
	{
		$values = [1, 2, 3];
		$this->assertTrue(ArrayType::removeKeys($values, [0, 2]));
		$this->assertCount(1, $values);
		$this->assertSame(2, $values[1]);
	}

	public function testRemoveKeysNoChange()
	{
		$values = [1, 2, 3];
		$this->assertFalse(ArrayType::removeKeys($values, [4, 5]));
		$this->assertCount(3, $values);
	}

	public function testRemoveKeysByArrayKeys()
	{
		$values = [1, 2, 3];
		$this->assertTrue(ArrayType::removeKeysByArrayKeys($values, [
			0 => 'foo',
			2 => 'bar',
		]));
		$this->assertCount(1, $values);
		$this->assertSame(2, $values[1]);
	}

	public function testRemoveKeysByArrayKeysNoChange()
	{
		$values = [1, 2, 3];
		$this->assertFalse(ArrayType::removeKeysByArrayKeys($values, [
			4 => 'foo',
			5 => 'bar',
		]));
		$this->assertCount(3, $values);
	}

}
