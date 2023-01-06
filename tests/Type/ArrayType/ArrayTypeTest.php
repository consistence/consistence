<?php

declare(strict_types = 1);

namespace Consistence\Type\ArrayType;

use DateTimeImmutable;
use PHPUnit\Framework\Assert;

class ArrayTypeTest extends \Consistence\TestCase
{

	public function testStaticConstruct(): void
	{
		$this->expectException(\Consistence\StaticClassException::class);

		new ArrayType();
	}

	public function testContainsKeyIsNotStrict(): void
	{
		$haystack = [
			'three' => 'three',
			'7' => '7',
			3 => 3,
			null => null,
			false => 'false',
			true => 'true',
			'nullValue' => null,
		];

		Assert::assertTrue(ArrayType::containsKey($haystack, 'three'));
		Assert::assertTrue(ArrayType::containsKey($haystack, '7'));
		Assert::assertTrue(ArrayType::containsKey($haystack, 7));
		Assert::assertTrue(ArrayType::containsKey($haystack, null));
		Assert::assertTrue(ArrayType::containsKey($haystack, 3));
		Assert::assertTrue(ArrayType::containsKey($haystack, '3'));
		Assert::assertTrue(ArrayType::containsKey($haystack, '')); // null key
		Assert::assertTrue(ArrayType::containsKey($haystack, 0)); // false key
		Assert::assertTrue(ArrayType::containsKey($haystack, 1)); // true key
		Assert::assertTrue(ArrayType::containsKey($haystack, 'nullValue'));
		Assert::assertFalse(ArrayType::containsKey($haystack, '99'));
	}

	public function testContainsValueDefault(): void
	{
		$haystack = [1, 2, 3];
		Assert::assertTrue(ArrayType::containsValue($haystack, 2));
		Assert::assertFalse(ArrayType::containsValue($haystack, '2'));
	}

	public function testContainsValueStrict(): void
	{
		$haystack = [1, 2, 3];
		Assert::assertTrue(ArrayType::containsValue($haystack, 2, ArrayType::STRICT_TRUE));
		Assert::assertFalse(ArrayType::containsValue($haystack, '2', ArrayType::STRICT_TRUE));
	}

	public function testContainsValueLoose(): void
	{
		$haystack = [1, 2, 3];
		Assert::assertTrue(ArrayType::containsValue($haystack, '2', ArrayType::STRICT_FALSE));
	}

	public function testContainsValueNull(): void
	{
		Assert::assertTrue(ArrayType::containsValue([1, 2, 3, null], null));
		Assert::assertFalse(ArrayType::containsValue([1, 2, 3], null));
	}

	public function testContainsByCallback(): void
	{
		$haystack = [1, 2, 3];
		Assert::assertTrue(ArrayType::containsByCallback($haystack, function (KeyValuePair $pair): bool {
			return ($pair->getValue() % 2) === 0;
		}));
	}

	public function testContainsByCallbackLoose(): void
	{
		$haystack = [1, 2, 3];
		Assert::assertTrue(ArrayType::containsByCallback($haystack, function (KeyValuePair $pair): bool {
			return $pair->getValue() == '2';
		}));
	}

	public function testContainsByCallbackNotFound(): void
	{
		$haystack = [1, 2, 3];
		Assert::assertFalse(ArrayType::containsByCallback($haystack, function (KeyValuePair $pair): bool {
			return $pair->getValue() === 0;
		}));
	}

	public function testContainsValueByValueCallback(): void
	{
		$haystack = [1, 2, 3];
		Assert::assertTrue(ArrayType::containsValueByValueCallback($haystack, function (int $value): bool {
			return ($value % 2) === 0;
		}));
	}

	public function testContainsValueByValueCallbackLoose(): void
	{
		$haystack = [1, 2, 3];
		Assert::assertTrue(ArrayType::containsValueByValueCallback($haystack, function (int $value): bool {
			return $value == '2';
		}));
	}

	public function testContainsValueByValueCallbackNotFound(): void
	{
		$haystack = [1, 2, 3];
		Assert::assertFalse(ArrayType::containsValueByValueCallback($haystack, function (int $value): bool {
			return $value === 0;
		}));
	}

	public function testContainsValueByValueCallbackNull(): void
	{
		$haystack = [1, 2, 3, null];
		Assert::assertTrue(ArrayType::containsValueByValueCallback($haystack, function ($value): bool {
			return $value === null;
		}));
	}

	public function testFindKeyDefault(): void
	{
		$haystack = [1, 2, 3];
		Assert::assertSame(1, ArrayType::findKey($haystack, 2));
		Assert::assertNull(ArrayType::findKey($haystack, '2'));
	}

	public function testFindKeyStrict(): void
	{
		$haystack = [1, 2, 3];
		Assert::assertSame(1, ArrayType::findKey($haystack, 2, ArrayType::STRICT_TRUE));
		Assert::assertNull(ArrayType::findKey($haystack, '2', ArrayType::STRICT_TRUE));
	}

	public function testFindKeyLoose(): void
	{
		$haystack = [1, 2, 3];
		Assert::assertSame(1, ArrayType::findKey($haystack, '2', ArrayType::STRICT_FALSE));
	}

	public function testFindKeyByCallback(): void
	{
		$haystack = [1, 2, 3];
		Assert::assertSame(1, ArrayType::findKeyByCallback($haystack, function (KeyValuePair $pair): bool {
			return ($pair->getValue() % 2) === 0;
		}));
	}

	public function testFindKeyByCallbackNotFound(): void
	{
		$haystack = [1, 2, 3];
		Assert::assertNull(ArrayType::findKeyByCallback($haystack, function (KeyValuePair $pair): bool {
			return $pair->getValue() === 0;
		}));
	}

	public function testFindKeyByCallbackCustomKeys(): void
	{
		$haystack = [
			'one' => 1,
			'two' => 2,
			'three' => 3,
		];
		Assert::assertSame('two', ArrayType::findKeyByCallback($haystack, function (KeyValuePair $pair): bool {
			return ($pair->getValue() % 2) === 0;
		}));
	}

	public function testFindKeyByValueCallback(): void
	{
		$haystack = [1, 2, 3];
		Assert::assertSame(1, ArrayType::findKeyByValueCallback($haystack, function (int $value): bool {
			return ($value % 2) === 0;
		}));
	}

	public function testFindKeyByValueCallbackNotFound(): void
	{
		$haystack = [1, 2, 3];
		Assert::assertNull(ArrayType::findKeyByValueCallback($haystack, function (int $value): bool {
			return $value === 0;
		}));
	}

	public function testFindKeyByValueCallbackCustomKeys(): void
	{
		$haystack = [
			'one' => 1,
			'two' => 2,
			'three' => 3,
		];
		Assert::assertSame('two', ArrayType::findKeyByValueCallback($haystack, function (int $value): bool {
			return ($value % 2) === 0;
		}));
	}

	public function testGetKey(): void
	{
		$haystack = [1, 2, 3];
		Assert::assertSame(1, ArrayType::getKey($haystack, 2));
	}

	public function testGetKeyNotFound(): void
	{
		$haystack = [1, 2, 3];

		$this->expectException(\Consistence\Type\ArrayType\ElementDoesNotExistException::class);

		ArrayType::getKey($haystack, '2');
	}

	public function testGetKeyByCallback(): void
	{
		$haystack = [1, 2, 3];
		Assert::assertSame(1, ArrayType::getKeyByCallback($haystack, function (KeyValuePair $pair): bool {
			return ($pair->getValue() % 2) === 0;
		}));
	}

	public function testGetKeyByCallbackNotFound(): void
	{
		$haystack = [1, 2, 3];

		$this->expectException(\Consistence\Type\ArrayType\ElementDoesNotExistException::class);

		ArrayType::getKeyByCallback($haystack, function (KeyValuePair $pair): bool {
			return $pair->getValue() === 0;
		});
	}

	public function testGetKeyByCallbackCustomKeys(): void
	{
		$haystack = [
			'one' => 1,
			'two' => 2,
			'three' => 3,
		];
		Assert::assertSame('two', ArrayType::getKeyByCallback($haystack, function (KeyValuePair $pair): bool {
			return ($pair->getValue() % 2) === 0;
		}));
	}

	public function testGetKeyByValueCallback(): void
	{
		$haystack = [1, 2, 3];
		Assert::assertSame(1, ArrayType::getKeyByValueCallback($haystack, function (int $value): bool {
			return ($value % 2) === 0;
		}));
	}

	public function testGetKeyByValueCallbackNotFound(): void
	{
		$haystack = [1, 2, 3];

		$this->expectException(\Consistence\Type\ArrayType\ElementDoesNotExistException::class);

		ArrayType::getKeyByValueCallback($haystack, function (int $value): bool {
			return $value === 0;
		});
	}

	public function testGetKeyByValueCallbackCustomKeys(): void
	{
		$haystack = [
			'one' => 1,
			'two' => 2,
			'three' => 3,
		];
		Assert::assertSame('two', ArrayType::getKeyByValueCallback($haystack, function (int $value): bool {
			return ($value % 2) === 0;
		}));
	}

	public function testFindValue(): void
	{
		$haystack = [
			'foo',
			'bar',
		];
		Assert::assertSame('bar', ArrayType::findValue($haystack, 1));
	}

	public function testFindValueNotFound(): void
	{
		$haystack = [
			'foo',
			'bar',
		];
		Assert::assertNull(ArrayType::findValue($haystack, 2));
	}

	public function testGetValue(): void
	{
		$haystack = [
			'foo',
			'bar',
		];
		Assert::assertSame('bar', ArrayType::getValue($haystack, 1));
	}

	public function testGetValueNotFound(): void
	{
		$haystack = [
			'foo',
			'bar',
		];

		$this->expectException(\Consistence\Type\ArrayType\ElementDoesNotExistException::class);

		ArrayType::getValue($haystack, 2);
	}

	public function testGetValueNull(): void
	{
		$haystack = [
			'null' => null,
		];
		Assert::assertSame(null, ArrayType::getValue($haystack, 'null'));
	}

	public function testFindByCallback(): void
	{
		$haystack = [1, 2, 3];
		$result = ArrayType::findByCallback($haystack, function (KeyValuePair $pair): bool {
			return ($pair->getValue() % 2) === 0;
		});
		Assert::assertInstanceOf(KeyValuePair::class, $result);
		Assert::assertSame(2, $result->getValue());
		Assert::assertSame(1, $result->getKey());
	}

	public function testFindByCallbackNothingFound(): void
	{
		$haystack = [1, 2, 3];
		$result = ArrayType::findByCallback($haystack, function (KeyValuePair $pair): bool {
			return $pair->getValue() > 3;
		});
		Assert::assertNull($result);
	}

	public function testFindValueByCallback(): void
	{
		$haystack = [1, 2, 3];
		$result = ArrayType::findValueByCallback($haystack, function (int $value): bool {
			return ($value % 2) === 0;
		});
		Assert::assertSame(2, $result);
	}

	public function testFindValueByCallbackNothingFound(): void
	{
		$haystack = [1, 2, 3];
		$result = ArrayType::findValueByCallback($haystack, function (int $value): bool {
			return $value > 3;
		});
		Assert::assertNull($result);
	}

	public function testGetByCallback(): void
	{
		$haystack = [1, 2, 3];
		$result = ArrayType::getByCallback($haystack, function (KeyValuePair $pair): bool {
			return ($pair->getValue() % 2) === 0;
		});
		Assert::assertInstanceOf(KeyValuePair::class, $result);
		Assert::assertSame(2, $result->getValue());
		Assert::assertSame(1, $result->getKey());
	}

	public function testGetByCallbackNothingFound(): void
	{
		$haystack = [1, 2, 3];

		$this->expectException(\Consistence\Type\ArrayType\ElementDoesNotExistException::class);

		ArrayType::getByCallback($haystack, function (KeyValuePair $pair): bool {
			return $pair->getValue() > 3;
		});
	}

	public function testGetValueByCallback(): void
	{
		$haystack = [1, 2, 3];
		$result = ArrayType::getValueByCallback($haystack, function (int $value): bool {
			return ($value % 2) === 0;
		});
		Assert::assertSame(2, $result);
	}

	public function testGetValueByCallbackNothingFound(): void
	{
		$haystack = [1, 2, 3];

		$this->expectException(\Consistence\Type\ArrayType\ElementDoesNotExistException::class);

		ArrayType::getValueByCallback($haystack, function (int $value): bool {
			return $value > 3;
		});
	}

	public function testGetValueByCallbackNull(): void
	{
		$haystack = [1, 2, 3, null];
		Assert::assertSame(null, ArrayType::getValueByCallback($haystack, function ($value): bool {
			return $value === null;
		}));
	}

	public function testFilterByCallback(): void
	{
		$haystack = [1, 2, 3];
		$result = ArrayType::filterByCallback($haystack, function (KeyValuePair $pair): bool {
			return $pair->getKey() > 1;
		});
		Assert::assertCount(1, $result);
		Assert::assertSame(3, $result[2]);
	}

	public function testFilterValueByCallback(): void
	{
		$haystack = [1, 2, 3];
		$result = ArrayType::filterValuesByCallback($haystack, function (int $value): bool {
			return ($value % 2) === 0;
		});
		Assert::assertCount(1, $result);
		Assert::assertSame(2, $result[1]);
	}

	public function testMapByCallback(): void
	{
		$haystack = [
			'foo' => 'bar',
		];
		$result = ArrayType::mapByCallback($haystack, function (KeyValuePair $pair): KeyValuePair {
			return new KeyValuePair(strtoupper($pair->getKey()), strtoupper($pair->getValue()));
		});
		Assert::assertSame([
			'FOO' => 'BAR',
		], $result);
	}

	public function testMapValuesByCallback(): void
	{
		$haystack = [1, 2, 3];
		$result = ArrayType::mapValuesByCallback($haystack, function (int $value): int {
			return $value * 2;
		});
		Assert::assertSame([2, 4, 6], $result);
	}

	public function testRemoveValue(): void
	{
		$haystack = [1, 2, 3];
		Assert::assertTrue(ArrayType::removeValue($haystack, 2));
		Assert::assertCount(2, $haystack);
		Assert::assertSame(1, $haystack[0]);
		Assert::assertSame(3, $haystack[2]);
	}

	public function testRemoveValueNoChange(): void
	{
		$haystack = [1, 2, 3];
		Assert::assertFalse(ArrayType::removeValue($haystack, 4));
		Assert::assertCount(3, $haystack);
	}

	public function testRemoveKeys(): void
	{
		$haystack = [1, 2, 3];
		Assert::assertTrue(ArrayType::removeKeys($haystack, [0, 2]));
		Assert::assertCount(1, $haystack);
		Assert::assertSame(2, $haystack[1]);
	}

	public function testRemoveKeysNoChange(): void
	{
		$haystack = [1, 2, 3];
		Assert::assertFalse(ArrayType::removeKeys($haystack, [4, 5]));
		Assert::assertCount(3, $haystack);
	}

	public function testRemoveKeysByArrayKeys(): void
	{
		$haystack = [1, 2, 3];
		Assert::assertTrue(ArrayType::removeKeysByArrayKeys($haystack, [
			0 => 'foo',
			2 => 'bar',
		]));
		Assert::assertCount(1, $haystack);
		Assert::assertSame(2, $haystack[1]);
	}

	public function testRemoveKeysByArrayKeysNoChange(): void
	{
		$haystack = [1, 2, 3];
		Assert::assertFalse(ArrayType::removeKeysByArrayKeys($haystack, [
			4 => 'foo',
			5 => 'bar',
		]));
		Assert::assertCount(3, $haystack);
	}

	public function testUniqueValuesStrict(): void
	{
		$haystack = ['1', 1];
		$expected = ['1', 1];

		$actual = ArrayType::uniqueValues($haystack);

		Assert::assertSame($expected, $actual);
	}

	public function testUniqueValuesStrictWithObjects(): void
	{
		$haystack = [
			new DateTimeImmutable('2017-01-01T12:00:00.000000'),
			new DateTimeImmutable('2017-01-01T12:00:00.000000'),
		];

		$actual = ArrayType::uniqueValues($haystack);

		Assert::assertSame($haystack, $actual);
	}

	public function testUniqueValuesNonStrictBehavesAsArrayUniqueWithRegularComparison(): void
	{
		$haystack = ['1', 1];

		$actual = ArrayType::uniqueValues($haystack, ArrayType::STRICT_FALSE);

		Assert::assertContains(1, $actual);
		Assert::assertCount(1, $actual);
	}

	public function testUniqueValuesNonStrictWithObjects(): void
	{
		$haystack = [
			new DateTimeImmutable('2017-01-01T12:00:00.000000'),
			new DateTimeImmutable('2017-01-01T12:00:00.000000'),
		];

		$actual = ArrayType::uniqueValues($haystack, ArrayType::STRICT_FALSE);

		Assert::assertContainsEquals(new DateTimeImmutable('2017-01-01T12:00:00.000000'), $actual);
		Assert::assertCount(1, $actual);
	}

	public function testUniqueValuesKeepsKeys(): void
	{
		$haystack = [
			'a' => 'green',
			0 => 'red',
			1 => 'blue',
		];

		$actual = ArrayType::uniqueValues($haystack);

		Assert::assertSame($haystack, $actual);
	}

	public function testUniqueValuesByCallbackWithStrictComparison(): void
	{
		$haystack = ['1', 1];

		$actual = ArrayType::uniqueValuesByCallback($haystack, function ($a, $b): bool {
			return $a === $b;
		});

		Assert::assertSame($haystack, $actual);
	}

	public function testUniqueValuesByCallbackWithStrictComparisonWithObjects(): void
	{
		$haystack = [
			new DateTimeImmutable('2017-01-01T12:00:00.000000'),
			new DateTimeImmutable('2017-01-01T12:00:00.000000'),
		];

		$actual = ArrayType::uniqueValuesByCallback($haystack, function (DateTimeImmutable $a, DateTimeImmutable $b): bool {
			return $a === $b;
		});

		Assert::assertSame($haystack, $actual);
	}

	public function testUniqueValuesByCallbackWithNonStrictComparison(): void
	{
		$haystack = ['1', 1];

		$actual = ArrayType::uniqueValuesByCallback($haystack, function ($a, $b): bool {
			return $a == $b;
		});

		Assert::assertContains(1, $actual);
		Assert::assertCount(1, $actual);
	}

	public function testUniqueValuesByCallbackWithNonStrictComparisonWithObjects(): void
	{
		$haystack = [
			new DateTimeImmutable('2017-01-01T12:00:00.000000'),
			new DateTimeImmutable('2017-01-01T12:00:00.000000'),
		];

		$actual = ArrayType::uniqueValuesByCallback($haystack, function (DateTimeImmutable $a, DateTimeImmutable $b): bool {
			return $a == $b;
		});

		Assert::assertContainsEquals(new DateTimeImmutable('2017-01-01T12:00:00.000000'), $actual);
		Assert::assertCount(1, $actual);
	}

}
