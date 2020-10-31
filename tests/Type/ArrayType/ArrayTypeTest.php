<?php

declare(strict_types = 1);

namespace Consistence\Type\ArrayType;

use DateTimeImmutable;

class ArrayTypeTest extends \Consistence\TestCase
{

	public function testContainsKeyIsNotStrict(): void
	{
		$values = [
			'three' => 'three',
			'7' => '7',
			3 => 3,
			null => null,
			false => 'false',
			true => 'true',
			'nullValue' => null,
		];

		$this->assertTrue(ArrayType::containsKey($values, 'three'));
		$this->assertTrue(ArrayType::containsKey($values, '7'));
		$this->assertTrue(ArrayType::containsKey($values, 7));
		$this->assertTrue(ArrayType::containsKey($values, null));
		$this->assertTrue(ArrayType::containsKey($values, 3));
		$this->assertTrue(ArrayType::containsKey($values, '3'));
		$this->assertTrue(ArrayType::containsKey($values, '')); // null key
		$this->assertTrue(ArrayType::containsKey($values, 0)); // false key
		$this->assertTrue(ArrayType::containsKey($values, 1)); // true key
		$this->assertTrue(ArrayType::containsKey($values, 'nullValue'));
		$this->assertFalse(ArrayType::containsKey($values, '99'));
	}

	public function testStaticConstruct(): void
	{
		$this->expectException(\Consistence\StaticClassException::class);

		new ArrayType();
	}

	public function testContainsValueDefault(): void
	{
		$values = [1, 2, 3];
		$this->assertTrue(ArrayType::containsValue($values, 2));
		$this->assertFalse(ArrayType::containsValue($values, '2'));
	}

	public function testContainsValueStrict(): void
	{
		$values = [1, 2, 3];
		$this->assertTrue(ArrayType::containsValue($values, 2, ArrayType::STRICT_TRUE));
		$this->assertFalse(ArrayType::containsValue($values, '2', ArrayType::STRICT_TRUE));
	}

	public function testContainsValueLoose(): void
	{
		$values = [1, 2, 3];
		$this->assertTrue(ArrayType::containsValue($values, '2', ArrayType::STRICT_FALSE));
	}

	public function testContainsValueNull(): void
	{
		$this->assertTrue(ArrayType::containsValue([1, 2, 3, null], null));
		$this->assertFalse(ArrayType::containsValue([1, 2, 3], null));
	}

	public function testContainsByCallback(): void
	{
		$values = [1, 2, 3];
		$this->assertTrue(ArrayType::containsByCallback($values, function (KeyValuePair $pair): bool {
			return ($pair->getValue() % 2) === 0;
		}));
	}

	public function testContainsByCallbackLoose(): void
	{
		$values = [1, 2, 3];
		$this->assertTrue(ArrayType::containsByCallback($values, function (KeyValuePair $pair): bool {
			return $pair->getValue() == '2';
		}));
	}

	public function testContainsByCallbackNotFound(): void
	{
		$values = [1, 2, 3];
		$this->assertFalse(ArrayType::containsByCallback($values, function (KeyValuePair $pair): bool {
			return $pair->getValue() === 0;
		}));
	}

	public function testContainsValueByValueCallback(): void
	{
		$values = [1, 2, 3];
		$this->assertTrue(ArrayType::containsValueByValueCallback($values, function (int $value): bool {
			return ($value % 2) === 0;
		}));
	}

	public function testContainsValueByValueCallbackLoose(): void
	{
		$values = [1, 2, 3];
		$this->assertTrue(ArrayType::containsValueByValueCallback($values, function (int $value): bool {
			return $value == '2';
		}));
	}

	public function testContainsValueByValueCallbackNotFound(): void
	{
		$values = [1, 2, 3];
		$this->assertFalse(ArrayType::containsValueByValueCallback($values, function (int $value): bool {
			return $value === 0;
		}));
	}

	public function testContainsValueByValueCallbackNull(): void
	{
		$values = [1, 2, 3, null];
		$this->assertTrue(ArrayType::containsValueByValueCallback($values, function ($value): bool {
			return $value === null;
		}));
	}

	public function testArraySearchDefault(): void
	{
		$values = [1, 2, 3];
		$this->assertSame(1, ArrayType::findKey($values, 2));
		$this->assertNull(ArrayType::findKey($values, '2'));
	}

	public function testArraySearchStrict(): void
	{
		$values = [1, 2, 3];
		$this->assertSame(1, ArrayType::findKey($values, 2, ArrayType::STRICT_TRUE));
		$this->assertNull(ArrayType::findKey($values, '2', ArrayType::STRICT_TRUE));
	}

	public function testArraySearchLoose(): void
	{
		$values = [1, 2, 3];
		$this->assertSame(1, ArrayType::findKey($values, '2', ArrayType::STRICT_FALSE));
	}

	public function testFindKeyByCallback(): void
	{
		$values = [1, 2, 3];
		$this->assertSame(1, ArrayType::findKeyByCallback($values, function (KeyValuePair $pair): bool {
			return ($pair->getValue() % 2) === 0;
		}));
	}

	public function testFindKeyByCallbackNotFound(): void
	{
		$values = [1, 2, 3];
		$this->assertNull(ArrayType::findKeyByCallback($values, function (KeyValuePair $pair): bool {
			return $pair->getValue() === 0;
		}));
	}

	public function testFindKeyByCallbackCustomKeys(): void
	{
		$values = [
			'one' => 1,
			'two' => 2,
			'three' => 3,
		];
		$this->assertSame('two', ArrayType::findKeyByCallback($values, function (KeyValuePair $pair): bool {
			return ($pair->getValue() % 2) === 0;
		}));
	}

	public function testFindKeyByValueCallback(): void
	{
		$values = [1, 2, 3];
		$this->assertSame(1, ArrayType::findKeyByValueCallback($values, function (int $value): bool {
			return ($value % 2) === 0;
		}));
	}

	public function testFindKeyByValueCallbackNotFound(): void
	{
		$values = [1, 2, 3];
		$this->assertNull(ArrayType::findKeyByValueCallback($values, function (int $value): bool {
			return $value === 0;
		}));
	}

	public function testFindKeyByValueCallbackCustomKeys(): void
	{
		$values = [
			'one' => 1,
			'two' => 2,
			'three' => 3,
		];
		$this->assertSame('two', ArrayType::findKeyByValueCallback($values, function (int $value): bool {
			return ($value % 2) === 0;
		}));
	}

	public function testGetKey(): void
	{
		$values = [1, 2, 3];
		$this->assertSame(1, ArrayType::getKey($values, 2));
	}

	public function testGetKeyNotFound(): void
	{
		$values = [1, 2, 3];

		$this->expectException(\Consistence\Type\ArrayType\ElementDoesNotExistException::class);

		ArrayType::getKey($values, '2');
	}

	public function testGetKeyByCallback(): void
	{
		$values = [1, 2, 3];
		$this->assertSame(1, ArrayType::getKeyByCallback($values, function (KeyValuePair $pair): bool {
			return ($pair->getValue() % 2) === 0;
		}));
	}

	public function testGetKeyByCallbackNotFound(): void
	{
		$values = [1, 2, 3];

		$this->expectException(\Consistence\Type\ArrayType\ElementDoesNotExistException::class);

		ArrayType::getKeyByCallback($values, function (KeyValuePair $pair): bool {
			return $pair->getValue() === 0;
		});
	}

	public function testGetKeyByCallbackCustomKeys(): void
	{
		$values = [
			'one' => 1,
			'two' => 2,
			'three' => 3,
		];
		$this->assertSame('two', ArrayType::getKeyByCallback($values, function (KeyValuePair $pair): bool {
			return ($pair->getValue() % 2) === 0;
		}));
	}

	public function testGetKeyByValueCallback(): void
	{
		$values = [1, 2, 3];
		$this->assertSame(1, ArrayType::getKeyByValueCallback($values, function (int $value): bool {
			return ($value % 2) === 0;
		}));
	}

	public function testGetKeyByValueCallbackNotFound(): void
	{
		$values = [1, 2, 3];

		$this->expectException(\Consistence\Type\ArrayType\ElementDoesNotExistException::class);

		ArrayType::getKeyByValueCallback($values, function (int $value): bool {
			return $value === 0;
		});
	}

	public function testGetKeyByValueCallbackCustomKeys(): void
	{
		$values = [
			'one' => 1,
			'two' => 2,
			'three' => 3,
		];
		$this->assertSame('two', ArrayType::getKeyByValueCallback($values, function (int $value): bool {
			return ($value % 2) === 0;
		}));
	}

	public function testFindValue(): void
	{
		$values = [
			'foo',
			'bar',
		];
		$this->assertSame('bar', ArrayType::findValue($values, 1));
	}

	public function testFindValueNotFound(): void
	{
		$values = [
			'foo',
			'bar',
		];
		$this->assertNull(ArrayType::findValue($values, 2));
	}

	public function testGetValue(): void
	{
		$values = [
			'foo',
			'bar',
		];
		$this->assertSame('bar', ArrayType::getValue($values, 1));
	}

	public function testGetValueNotFound(): void
	{
		$values = [
			'foo',
			'bar',
		];

		$this->expectException(\Consistence\Type\ArrayType\ElementDoesNotExistException::class);

		ArrayType::getValue($values, 2);
	}

	public function testGetValueNull(): void
	{
		$values = [
			'null' => null,
		];
		$this->assertSame(null, ArrayType::getValue($values, 'null'));
	}

	public function testFindByCallback(): void
	{
		$values = [1, 2, 3];
		$result = ArrayType::findByCallback($values, function (KeyValuePair $pair): bool {
			return ($pair->getValue() % 2) === 0;
		});
		$this->assertInstanceOf(KeyValuePair::class, $result);
		$this->assertSame(2, $result->getValue());
		$this->assertSame(1, $result->getKey());
	}

	public function testFindByCallbackNothingFound(): void
	{
		$values = [1, 2, 3];
		$result = ArrayType::findByCallback($values, function (KeyValuePair $pair): bool {
			return $pair->getValue() > 3;
		});
		$this->assertNull($result);
	}

	public function testFindValueByCallback(): void
	{
		$values = [1, 2, 3];
		$result = ArrayType::findValueByCallback($values, function (int $value): bool {
			return ($value % 2) === 0;
		});
		$this->assertSame(2, $result);
	}

	public function testFindValueByCallbackNothingFound(): void
	{
		$values = [1, 2, 3];
		$result = ArrayType::findValueByCallback($values, function (int $value): bool {
			return $value > 3;
		});
		$this->assertNull($result);
	}

	public function testGetByCallback(): void
	{
		$values = [1, 2, 3];
		$result = ArrayType::getByCallback($values, function (KeyValuePair $pair): bool {
			return ($pair->getValue() % 2) === 0;
		});
		$this->assertInstanceOf(KeyValuePair::class, $result);
		$this->assertSame(2, $result->getValue());
		$this->assertSame(1, $result->getKey());
	}

	public function testGetByCallbackNothingFound(): void
	{
		$values = [1, 2, 3];

		$this->expectException(\Consistence\Type\ArrayType\ElementDoesNotExistException::class);

		ArrayType::getByCallback($values, function (KeyValuePair $pair): bool {
			return $pair->getValue() > 3;
		});
	}

	public function testGetValueByCallback(): void
	{
		$values = [1, 2, 3];
		$result = ArrayType::getValueByCallback($values, function (int $value): bool {
			return ($value % 2) === 0;
		});
		$this->assertSame(2, $result);
	}

	public function testGetValueByCallbackNothingFound(): void
	{
		$values = [1, 2, 3];

		$this->expectException(\Consistence\Type\ArrayType\ElementDoesNotExistException::class);

		ArrayType::getValueByCallback($values, function (int $value): bool {
			return $value > 3;
		});
	}

	public function testGetValueByCallbackNull(): void
	{
		$values = [1, 2, 3, null];
		$this->assertSame(null, ArrayType::getValueByCallback($values, function ($value): bool {
			return $value === null;
		}));
	}

	public function testFilterByCallback(): void
	{
		$values = [1, 2, 3];
		$result = ArrayType::filterByCallback($values, function (KeyValuePair $pair): bool {
			return $pair->getKey() > 1;
		});
		$this->assertCount(1, $result);
		$this->assertSame(3, $result[2]);
	}

	public function testFilterValueByCallback(): void
	{
		$values = [1, 2, 3];
		$result = ArrayType::filterValuesByCallback($values, function (int $value): bool {
			return ($value % 2) === 0;
		});
		$this->assertCount(1, $result);
		$this->assertSame(2, $result[1]);
	}

	public function testMapByCallback(): void
	{
		$array = [
			'foo' => 'bar',
		];
		$result = ArrayType::mapByCallback($array, function (KeyValuePair $pair): KeyValuePair {
			return new KeyValuePair(strtoupper($pair->getKey()), strtoupper($pair->getValue()));
		});
		$this->assertSame([
			'FOO' => 'BAR',
		], $result);
	}

	public function testMapValuesByCallback(): void
	{
		$values = [1, 2, 3];
		$result = ArrayType::mapValuesByCallback($values, function (int $value): int {
			return $value * 2;
		});
		$this->assertSame([2, 4, 6], $result);
	}

	public function testRemoveValue(): void
	{
		$values = [1, 2, 3];
		$this->assertTrue(ArrayType::removeValue($values, 2));
		$this->assertCount(2, $values);
		$this->assertSame(1, $values[0]);
		$this->assertSame(3, $values[2]);
	}

	public function testRemoveValueNoChange(): void
	{
		$values = [1, 2, 3];
		$this->assertFalse(ArrayType::removeValue($values, 4));
		$this->assertCount(3, $values);
	}

	public function testRemoveKeys(): void
	{
		$values = [1, 2, 3];
		$this->assertTrue(ArrayType::removeKeys($values, [0, 2]));
		$this->assertCount(1, $values);
		$this->assertSame(2, $values[1]);
	}

	public function testRemoveKeysNoChange(): void
	{
		$values = [1, 2, 3];
		$this->assertFalse(ArrayType::removeKeys($values, [4, 5]));
		$this->assertCount(3, $values);
	}

	public function testRemoveKeysByArrayKeys(): void
	{
		$values = [1, 2, 3];
		$this->assertTrue(ArrayType::removeKeysByArrayKeys($values, [
			0 => 'foo',
			2 => 'bar',
		]));
		$this->assertCount(1, $values);
		$this->assertSame(2, $values[1]);
	}

	public function testRemoveKeysByArrayKeysNoChange(): void
	{
		$values = [1, 2, 3];
		$this->assertFalse(ArrayType::removeKeysByArrayKeys($values, [
			4 => 'foo',
			5 => 'bar',
		]));
		$this->assertCount(3, $values);
	}

	public function testUniqueValuesStrict(): void
	{
		$values = ['1', 1];
		$expected = ['1', 1];

		$actual = ArrayType::uniqueValues($values);

		$this->assertSame($expected, $actual);
	}

	public function testUniqueValuesStrictWithObjects(): void
	{
		$values = [
			new DateTimeImmutable('2017-01-01T12:00:00.000000'),
			new DateTimeImmutable('2017-01-01T12:00:00.000000'),
		];

		$actual = ArrayType::uniqueValues($values);

		$this->assertSame($values, $actual);
	}

	public function testUniqueValuesNonStrictBehavesAsArrayUniqueWithRegularComparison(): void
	{
		$values = ['1', 1];

		$actual = ArrayType::uniqueValues($values, ArrayType::STRICT_FALSE);

		$this->assertContains('1', $actual);
		$this->assertCount(1, $actual);
	}

	public function testUniqueValuesNonStrictWithObjects(): void
	{
		$values = [
			new DateTimeImmutable('2017-01-01T12:00:00.000000'),
			new DateTimeImmutable('2017-01-01T12:00:00.000000'),
		];

		$actual = ArrayType::uniqueValues($values, ArrayType::STRICT_FALSE);

		$this->assertContainsEquals(new DateTimeImmutable('2017-01-01T12:00:00.000000'), $actual);
		$this->assertCount(1, $actual);
	}

	public function testUniqueValuesKeepsKeys(): void
	{
		$values = [
			'a' => 'green',
			0 => 'red',
			1 => 'blue',
		];

		$actual = ArrayType::uniqueValues($values);

		$this->assertSame($values, $actual);
	}

	public function testUniqueValuesByCallbackWithStrictComparison(): void
	{
		$values = ['1', 1];

		$actual = ArrayType::uniqueValuesByCallback($values, function ($a, $b): bool {
			return $a === $b;
		});

		$this->assertSame($values, $actual);
	}

	public function testUniqueValuesByCallbackWithStrictComparisonWithObjects(): void
	{
		$values = [
			new DateTimeImmutable('2017-01-01T12:00:00.000000'),
			new DateTimeImmutable('2017-01-01T12:00:00.000000'),
		];

		$actual = ArrayType::uniqueValuesByCallback($values, function (DateTimeImmutable $a, DateTimeImmutable $b): bool {
			return $a === $b;
		});

		$this->assertSame($values, $actual);
	}

	public function testUniqueValuesByCallbackWithNonStrictComparison(): void
	{
		$values = ['1', 1];

		$actual = ArrayType::uniqueValuesByCallback($values, function ($a, $b): bool {
			return $a == $b;
		});

		$this->assertContains('1', $actual);
		$this->assertCount(1, $actual);
	}

	public function testUniqueValuesByCallbackWithNonStrictComparisonWithObjects(): void
	{
		$values = [
			new DateTimeImmutable('2017-01-01T12:00:00.000000'),
			new DateTimeImmutable('2017-01-01T12:00:00.000000'),
		];

		$actual = ArrayType::uniqueValuesByCallback($values, function (DateTimeImmutable $a, DateTimeImmutable $b): bool {
			return $a == $b;
		});

		$this->assertContainsEquals(new DateTimeImmutable('2017-01-01T12:00:00.000000'), $actual);
		$this->assertCount(1, $actual);
	}

}
