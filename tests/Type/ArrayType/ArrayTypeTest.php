<?php

declare(strict_types = 1);

namespace Consistence\Type\ArrayType;

use DateTimeImmutable;
use Generator;
use PHPUnit\Framework\Assert;

class ArrayTypeTest extends \PHPUnit\Framework\TestCase
{

	public function testStaticConstruct(): void
	{
		$this->expectException(\Consistence\StaticClassException::class);

		new ArrayType();
	}

	/**
	 * @return mixed[][]|\Generator
	 */
	public function containsKeyIsNotStrictDataProvider(): Generator
	{
		yield 'existing string key' => [
			'haystack' => ['three' => 'three'],
			'key' => 'three',
			'expectedContainsKey' => true,
		];

		yield 'existing numeric string key as string' => [
			'haystack' => ['7' => '7'],
			'key' => '7',
			'expectedContainsKey' => true,
		];

		yield 'existing numeric string key as integer' => [
			'haystack' => ['7' => '7'],
			'key' => 7,
			'expectedContainsKey' => true,
		];

		yield 'existing integer key as integer' => [
			'haystack' => [3 => 3],
			'key' => 3,
			'expectedContainsKey' => true,
		];

		yield 'existing integer key as numeric string' => [
			'haystack' => [3 => 3],
			'key' => '3',
			'expectedContainsKey' => true,
		];

		yield 'existing null key as null' => [
			'haystack' => [null => null],
			'key' => null,
			'expectedContainsKey' => true,
		];

		yield 'existing null key as empty string' => [
			'haystack' => [null => null],
			'key' => '',
			'expectedContainsKey' => true,
		];

		yield 'existing false key as 0' => [
			'haystack' => [false => 'false'],
			'key' => 0,
			'expectedContainsKey' => true,
		];

		yield 'existing true key as 1' => [
			'haystack' => [true => 'true'],
			'key' => 1,
			'expectedContainsKey' => true,
		];

		yield 'existing `nullValue` string key as string' => [
			'haystack' => ['nullValue' => null],
			'key' => 'nullValue',
			'expectedContainsKey' => true,
		];

		yield 'non-existing numeric string key as string' => [
			'haystack' => [3 => 3],
			'key' => '99',
			'expectedContainsKey' => false,
		];
	}

	/**
	 * @dataProvider containsKeyIsNotStrictDataProvider
	 *
	 * @param mixed[] $haystack
	 * @param mixed $key
	 * @param bool $expectedContainsKey
	 */
	public function testContainsKeyIsNotStrict(
		array $haystack,
		$key,
		bool $expectedContainsKey
	): void
	{
		Assert::assertSame($expectedContainsKey, ArrayType::containsKey($haystack, $key));
	}

	/**
	 * @return mixed[][]|\Generator
	 */
	public function containsValueStrictDataProvider(): Generator
	{
		yield 'existing integer value' => [
			'haystack' => [1, 2, 3],
			'value' => 2,
			'expectedContains' => true,
		];

		yield 'existing integer value as numeric string' => [
			'haystack' => [1, 2, 3],
			'value' => '2',
			'expectedContains' => false,
		];
	}

	/**
	 * @dataProvider containsValueStrictDataProvider
	 *
	 * @param mixed[] $haystack
	 * @param mixed $value
	 * @param bool $expectedContains
	 */
	public function testContainsValueDefault(
		array $haystack,
		$value,
		bool $expectedContains
	): void
	{
		Assert::assertSame($expectedContains, ArrayType::containsValue($haystack, $value));
	}

	/**
	 * @dataProvider containsValueStrictDataProvider
	 *
	 * @param mixed[] $haystack
	 * @param mixed $value
	 * @param bool $expectedContains
	 */
	public function testContainsValueStrict(
		array $haystack,
		$value,
		bool $expectedContains
	): void
	{
		Assert::assertSame($expectedContains, ArrayType::containsValue($haystack, $value, ArrayType::STRICT_TRUE));
	}

	public function testContainsValueLoose(): void
	{
		$haystack = [1, 2, 3];
		Assert::assertTrue(ArrayType::containsValue($haystack, '2', ArrayType::STRICT_FALSE));
	}

	/**
	 * @return mixed[][]|\Generator
	 */
	public function containsValueNullDataProvider(): Generator
	{
		yield 'existing null value' => [
			'haystack' => [1, 2, 3, null],
			'value' => null,
			'expectedContains' => true,
		];

		yield 'nonexistent null value' => [
			'haystack' => [1, 2, 3],
			'value' => null,
			'expectedContains' => false,
		];
	}

	/**
	 * @dataProvider containsValueNullDataProvider
	 *
	 * @param mixed[] $haystack
	 * @param mixed $value
	 * @param bool $expectedContains
	 */
	public function testContainsValueNull(
		array $haystack,
		$value,
		bool $expectedContains
	): void
	{
		Assert::assertSame($expectedContains, ArrayType::containsValue($haystack, $value));
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

	/**
	 * @return mixed[][]|\Generator
	 */
	public function findKeyStrictDataProvider(): Generator
	{
		yield 'existing integer value' => [
			'haystack' => [1, 2, 3],
			'value' => 2,
			'expectedKey' => 1,
		];

		yield 'existing integer value as numeric string' => [
			'haystack' => [1, 2, 3],
			'value' => '2',
			'expectedKey' => null,
		];
	}

	/**
	 * @dataProvider findKeyStrictDataProvider
	 *
	 * @param mixed[] $haystack
	 * @param mixed $value
	 * @param int|string|null $expectedKey
	 */
	public function testFindKeyDefault(
		array $haystack,
		$value,
		$expectedKey
	): void
	{
		Assert::assertSame($expectedKey, ArrayType::findKey($haystack, $value));
	}

	/**
	 * @dataProvider findKeyStrictDataProvider
	 *
	 * @param mixed[] $haystack
	 * @param mixed $value
	 * @param int|string|null $expectedKey
	 */
	public function testFindKeyStrict(
		array $haystack,
		$value,
		$expectedKey
	): void
	{
		Assert::assertSame($expectedKey, ArrayType::findKey($haystack, $value));
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
