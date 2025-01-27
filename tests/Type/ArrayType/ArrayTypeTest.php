<?php

declare(strict_types = 1);

namespace Consistence\Type\ArrayType;

use Closure;
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
	public function keyValueStrictDataProvider(): Generator
	{
		yield 'existing integer value' => [
			'haystack' => [1, 2, 3],
			'key' => 1,
			'value' => 2,
		];

		yield 'existing string value' => [
			'haystack' => [
				'foo',
				'bar',
			],
			'key' => 1,
			'value' => 'bar',
		];

		yield 'existing null value with integer index' => [
			'haystack' => [1, 2, 3, null],
			'key' => 3,
			'value' => null,
		];

		yield 'existing null value with string index' => [
			'haystack' => ['null' => null],
			'key' => 'null',
			'value' => null,
		];
	}

	/**
	 * @return mixed[][]|\Generator
	 */
	public function keyValueLooseDataProvider(): Generator
	{
		yield 'existing integer value as numeric string' => [
			'haystack' => [1, 2, 3],
			'key' => 1,
			'value' => '2',
		];
	}

	/**
	 * @return mixed[][]|\Generator
	 */
	public function valueNotFoundDataProvider(): Generator
	{
		yield 'nonexistent null value' => [
			'haystack' => [1, 2, 3],
			'value' => null,
		];
	}

	/**
	 * @return mixed[][]|\Generator
	 */
	public function containsValueWithStrictParameterDataProvider(): Generator
	{
		foreach ($this->keyValueStrictDataProvider() as $caseName => $caseData) {
			yield $caseName . ' - STRICT_TRUE' => [
				'haystack' => $caseData['haystack'],
				'value' => $caseData['value'],
				'strict' => ArrayType::STRICT_TRUE,
				'expectedContainsValue' => true,
			];
			yield $caseName . ' - STRICT_FALSE' => [
				'haystack' => $caseData['haystack'],
				'value' => $caseData['value'],
				'strict' => ArrayType::STRICT_FALSE,
				'expectedContainsValue' => true,
			];
		}

		foreach ($this->keyValueLooseDataProvider() as $caseName => $caseData) {
			yield $caseName . ' - STRICT_TRUE' => [
				'haystack' => $caseData['haystack'],
				'value' => $caseData['value'],
				'strict' => ArrayType::STRICT_TRUE,
				'expectedContainsValue' => false,
			];
			yield $caseName . ' - STRICT_FALSE' => [
				'haystack' => $caseData['haystack'],
				'value' => $caseData['value'],
				'strict' => ArrayType::STRICT_FALSE,
				'expectedContainsValue' => true,
			];
		}

		foreach ($this->valueNotFoundDataProvider() as $caseName => $caseData) {
			yield $caseName . ' - STRICT_TRUE' => [
				'haystack' => $caseData['haystack'],
				'value' => $caseData['value'],
				'strict' => ArrayType::STRICT_TRUE,
				'expectedContainsValue' => false,
			];
			yield $caseName . ' - STRICT_FALSE' => [
				'haystack' => $caseData['haystack'],
				'value' => $caseData['value'],
				'strict' => ArrayType::STRICT_FALSE,
				'expectedContainsValue' => false,
			];
		}
	}

	/**
	 * @return mixed[][]|\Generator
	 */
	public function containsValueDefaultDataProvider(): Generator
	{
		foreach ($this->containsValueWithStrictParameterDataProvider() as $caseName => $caseData) {
			if ($caseData['strict'] === ArrayType::STRICT_TRUE) {
				yield $caseName => [
					'haystack' => $caseData['haystack'],
					'value' => $caseData['value'],
					'expectedContainsValue' => $caseData['expectedContainsValue'],
				];
			}
		}
	}

	/**
	 * @dataProvider containsValueDefaultDataProvider
	 *
	 * @param mixed[] $haystack
	 * @param mixed $value
	 * @param bool $expectedContainsValue
	 */
	public function testContainsValueDefault(
		array $haystack,
		$value,
		bool $expectedContainsValue
	): void
	{
		Assert::assertSame($expectedContainsValue, ArrayType::containsValue($haystack, $value));
	}

	/**
	 * @dataProvider containsValueWithStrictParameterDataProvider
	 *
	 * @param mixed[] $haystack
	 * @param mixed $value
	 * @param bool $strict
	 * @param bool $expectedContainsValue
	 */
	public function testContainsValueWithStrictParameter(
		array $haystack,
		$value,
		bool $strict,
		bool $expectedContainsValue
	): void
	{
		Assert::assertSame($expectedContainsValue, ArrayType::containsValue($haystack, $value, $strict));
	}

	/**
	 * @return mixed[][]|\Generator
	 */
	public function keyValueCallbackDataProvider(): Generator
	{
		yield 'value found by strict comparison' => [
			'haystack' => [1, 2, 3],
			'callback' => function (KeyValuePair $pair): bool {
				return ($pair->getValue() % 2) === 0;
			},
			'valueCallback' => function (int $value): bool {
				return ($value % 2) === 0;
			},
			'expectedKey' => 1,
			'expectedValue' => 2,
		];

		yield 'value found by loose comparison' => [
			'haystack' => [1, 2, 3],
			'callback' => static function (KeyValuePair $pair): bool {
				return $pair->getValue() == '2';
			},
			'valueCallback' => static function (int $value): bool {
				return $value == '2';
			},
			'expectedKey' => 1,
			'expectedValue' => 2,
		];

		yield 'null value found' => [
			'haystack' => [1, 2, 3, null],
			'callback' => static function (KeyValuePair $pair): bool {
				return $pair->getValue() === null;
			},
			'valueCallback' => static function ($value): bool {
				return $value === null;
			},
			'expectedKey' => 3,
			'expectedValue' => null,
		];

		yield 'value found, custom keys' => [
			'haystack' => [
				'one' => 1,
				'two' => 2,
				'three' => 3,
			],
			'callback' => function (KeyValuePair $pair): bool {
				return ($pair->getValue() % 2) === 0;
			},
			'valueCallback' => function (int $value): bool {
				return ($value % 2) === 0;
			},
			'expectedKey' => 'two',
			'expectedValue' => 2,
		];
	}

	/**
	 * @return mixed[][]|\Generator
	 */
	public function keyValueCallbackNotFoundDataProvider(): Generator
	{
		yield 'same value not found' => [
			'haystack' => [1, 2, 3],
			'callback' => function (KeyValuePair $pair): bool {
				return $pair->getValue() === 0;
			},
			'valueCallback' => function (int $value): bool {
				return $value === 0;
			},
		];
		yield 'greater value not found' => [
			'haystack' => [1, 2, 3],
			'callback' => function (KeyValuePair $pair): bool {
				return $pair->getValue() > 3;
			},
			'valueCallback' => function (int $value): bool {
				return $value > 3;
			},
		];
	}

	/**
	 * @return mixed[][]|\Generator
	 */
	public function getKeyByCallbackDataProvider(): Generator
	{
		foreach ($this->keyValueCallbackDataProvider() as $caseName => $caseData) {
			yield $caseName => [
				'haystack' => $caseData['haystack'],
				'callback' => $caseData['callback'],
				'expectedKey' => $caseData['expectedKey'],
			];
		}
	}

	/**
	 * @return mixed[][]|\Generator
	 */
	public function getKeyByCallbackNotFoundDataProvider(): Generator
	{
		foreach ($this->keyValueCallbackNotFoundDataProvider() as $caseName => $caseData) {
			yield $caseName => [
				'haystack' => $caseData['haystack'],
				'callback' => $caseData['callback'],
			];
		}
	}

	/**
	 * @return mixed[][]|\Generator
	 */
	public function containsByCallbackDataProvider(): Generator
	{
		foreach ($this->getKeyByCallbackDataProvider() as $caseName => $caseData) {
			yield $caseName => [
				'haystack' => $caseData['haystack'],
				'callback' => $caseData['callback'],
				'expectedContainsByCallback' => true,
			];
		}

		foreach ($this->getKeyByCallbackNotFoundDataProvider() as $caseName => $caseData) {
			yield $caseName => [
				'haystack' => $caseData['haystack'],
				'callback' => $caseData['callback'],
				'expectedContainsByCallback' => false,
			];
		}
	}

	/**
	 * @dataProvider containsByCallbackDataProvider
	 *
	 * @param mixed[] $haystack
	 * @param \Closure $callback
	 * @param bool $expectedContainsByCallback
	 */
	public function testContainsByCallback(
		array $haystack,
		Closure $callback,
		bool $expectedContainsByCallback
	): void
	{
		Assert::assertSame($expectedContainsByCallback, ArrayType::containsByCallback($haystack, $callback));
	}

	/**
	 * @return mixed[][]|\Generator
	 */
	public function getKeyByValueCallbackDataProvider(): Generator
	{
		foreach ($this->keyValueCallbackDataProvider() as $caseName => $caseData) {
			yield $caseName => [
				'haystack' => $caseData['haystack'],
				'valueCallback' => $caseData['valueCallback'],
				'expectedKey' => $caseData['expectedKey'],
			];
		}
	}

	/**
	 * @return mixed[][]|\Generator
	 */
	public function getKeyByValueCallbackNotFoundDataProvider(): Generator
	{
		foreach ($this->keyValueCallbackNotFoundDataProvider() as $caseName => $caseData) {
			yield $caseName => [
				'haystack' => $caseData['haystack'],
				'valueCallback' => $caseData['valueCallback'],
			];
		}
	}

	/**
	 * @return mixed[][]|\Generator
	 */
	public function containsValueByValueCallbackDataProvider(): Generator
	{
		foreach ($this->getKeyByValueCallbackDataProvider() as $caseName => $caseData) {
			yield $caseName => [
				'haystack' => $caseData['haystack'],
				'valueCallback' => $caseData['valueCallback'],
				'expectedContainsValueByValueCallback' => true,
			];
		}

		foreach ($this->getKeyByValueCallbackNotFoundDataProvider() as $caseName => $caseData) {
			yield $caseName => [
				'haystack' => $caseData['haystack'],
				'valueCallback' => $caseData['valueCallback'],
				'expectedContainsValueByValueCallback' => false,
			];
		}
	}

	/**
	 * @dataProvider containsValueByValueCallbackDataProvider
	 *
	 * @param mixed[] $haystack
	 * @param \Closure $valueCallback
	 * @param bool $expectedContainsValueByValueCallback
	 */
	public function testContainsValueByValueCallback(
		array $haystack,
		Closure $valueCallback,
		bool $expectedContainsValueByValueCallback
	): void
	{
		Assert::assertSame($expectedContainsValueByValueCallback, ArrayType::containsValueByValueCallback($haystack, $valueCallback));
	}

	/**
	 * @return mixed[][]|\Generator
	 */
	public function findKeyWithStrictParameterDataProvider(): Generator
	{
		foreach ($this->keyValueStrictDataProvider() as $caseName => $caseData) {
			yield $caseName . ' - STRICT_TRUE' => [
				'haystack' => $caseData['haystack'],
				'value' => $caseData['value'],
				'strict' => ArrayType::STRICT_TRUE,
				'expectedKey' => $caseData['key'],
			];
			yield $caseName . ' - STRICT_FALSE' => [
				'haystack' => $caseData['haystack'],
				'value' => $caseData['value'],
				'strict' => ArrayType::STRICT_FALSE,
				'expectedKey' => $caseData['key'],
			];
		}

		foreach ($this->keyValueLooseDataProvider() as $caseName => $caseData) {
			yield $caseName . ' - STRICT_TRUE' => [
				'haystack' => $caseData['haystack'],
				'value' => $caseData['value'],
				'strict' => ArrayType::STRICT_TRUE,
				'expectedKey' => null,
			];
			yield $caseName . ' - STRICT_FALSE' => [
				'haystack' => $caseData['haystack'],
				'value' => $caseData['value'],
				'strict' => ArrayType::STRICT_FALSE,
				'expectedKey' => $caseData['key'],
			];
		}

		foreach ($this->valueNotFoundDataProvider() as $caseName => $caseData) {
			yield $caseName . ' - STRICT_TRUE' => [
				'haystack' => $caseData['haystack'],
				'value' => $caseData['value'],
				'strict' => ArrayType::STRICT_TRUE,
				'expectedKey' => null,
			];
			yield $caseName . ' - STRICT_FALSE' => [
				'haystack' => $caseData['haystack'],
				'value' => $caseData['value'],
				'strict' => ArrayType::STRICT_FALSE,
				'expectedKey' => null,
			];
		}
	}

	/**
	 * @return mixed[][]|\Generator
	 */
	public function findKeyDefaultDataProvider(): Generator
	{
		foreach ($this->findKeyWithStrictParameterDataProvider() as $caseName => $caseData) {
			if ($caseData['strict'] === ArrayType::STRICT_TRUE) {
				yield $caseName => [
					'haystack' => $caseData['haystack'],
					'value' => $caseData['value'],
					'expectedKey' => $caseData['expectedKey'],
				];
			}
		}
	}

	/**
	 * @dataProvider findKeyDefaultDataProvider
	 *
	 * @param mixed[] $haystack
	 * @param mixed $value
	 * @param int|null|string $expectedKey
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
	 * @dataProvider findKeyWithStrictParameterDataProvider
	 *
	 * @param mixed[] $haystack
	 * @param mixed $value
	 * @param bool $strict
	 * @param int|null|string $expectedKey
	 */
	public function testFindKeyWithStrictParameter(
		array $haystack,
		$value,
		bool $strict,
		$expectedKey
	): void
	{
		Assert::assertSame($expectedKey, ArrayType::findKey($haystack, $value, $strict));
	}

	/**
	 * @return mixed[][]|\Generator
	 */
	public function findKeyByCallbackDataProvider(): Generator
	{
		foreach ($this->getKeyByCallbackDataProvider() as $caseName => $caseData) {
			yield $caseName => [
				'haystack' => $caseData['haystack'],
				'callback' => $caseData['callback'],
				'expectedKey' => $caseData['expectedKey'],
			];
		}

		foreach ($this->getKeyByCallbackNotFoundDataProvider() as $caseName => $caseData) {
			yield $caseName => [
				'haystack' => $caseData['haystack'],
				'callback' => $caseData['callback'],
				'expectedKey' => null,
			];
		}
	}

	/**
	 * @dataProvider findKeyByCallbackDataProvider
	 *
	 * @param mixed[] $haystack
	 * @param \Closure $callback
	 * @param int|null|string $expectedKey
	 */
	public function testFindKeyByCallback(
		array $haystack,
		Closure $callback,
		$expectedKey
	): void
	{
		Assert::assertSame($expectedKey, ArrayType::findKeyByCallback($haystack, $callback));
	}

	/**
	 * @return mixed[][]|\Generator
	 */
	public function findKeyByValueCallbackDataProvider(): Generator
	{
		foreach ($this->getKeyByValueCallbackDataProvider() as $caseName => $caseData) {
			yield $caseName => [
				'haystack' => $caseData['haystack'],
				'valueCallback' => $caseData['valueCallback'],
				'expectedKey' => $caseData['expectedKey'],
			];
		}

		foreach ($this->getKeyByValueCallbackNotFoundDataProvider() as $caseName => $caseData) {
			yield $caseName => [
				'haystack' => $caseData['haystack'],
				'valueCallback' => $caseData['valueCallback'],
				'expectedKey' => null,
			];
		}
	}

	/**
	 * @dataProvider findKeyByValueCallbackDataProvider
	 *
	 * @param mixed[] $haystack
	 * @param \Closure $valueCallback
	 * @param int|null|string $expectedKey
	 */
	public function testFindKeyByValueCallback(
		array $haystack,
		Closure $valueCallback,
		$expectedKey
	): void
	{
		Assert::assertSame($expectedKey, ArrayType::findKeyByValueCallback($haystack, $valueCallback));
	}

	/**
	 * @return mixed[][]|\Generator
	 */
	public function getKeyDataProvider(): Generator
	{
		foreach ($this->keyValueStrictDataProvider() as $caseName => $caseData) {
			yield $caseName => [
				'haystack' => $caseData['haystack'],
				'value' => $caseData['value'],
				'expectedKey' => $caseData['key'],
			];
		}
	}

	/**
	 * @dataProvider getKeyDataProvider
	 *
	 * @param mixed[] $haystack
	 * @param mixed $value
	 * @param int|string $expectedKey
	 */
	public function testGetKey(
		array $haystack,
		$value,
		$expectedKey
	): void
	{
		Assert::assertSame($expectedKey, ArrayType::getKey($haystack, $value));
	}

	/**
	 * @return mixed[][]|\Generator
	 */
	public function getKeyNotFoundDataProvider(): Generator
	{
		foreach ($this->keyValueLooseDataProvider() as $caseName => $caseData) {
			yield $caseName => [
				'haystack' => $caseData['haystack'],
				'value' => $caseData['value'],
			];
		}

		foreach ($this->valueNotFoundDataProvider() as $caseName => $caseData) {
			yield $caseName => [
				'haystack' => $caseData['haystack'],
				'value' => $caseData['value'],
			];
		}
	}

	/**
	 * @dataProvider getKeyNotFoundDataProvider
	 *
	 * @param mixed[] $haystack
	 * @param mixed $value
	 */
	public function testGetKeyNotFound(
		array $haystack,
		$value
	): void
	{
		$this->expectException(\Consistence\Type\ArrayType\ElementDoesNotExistException::class);

		ArrayType::getKey($haystack, $value);
	}

	/**
	 * @dataProvider getKeyByCallbackDataProvider
	 *
	 * @param mixed[] $haystack
	 * @param \Closure $callback
	 * @param int|string $expectedKey
	 */
	public function testGetKeyByCallback(
		array $haystack,
		Closure $callback,
		$expectedKey
	): void
	{
		Assert::assertSame($expectedKey, ArrayType::getKeyByCallback($haystack, $callback));
	}

	/**
	 * @dataProvider getKeyByCallbackNotFoundDataProvider
	 *
	 * @param mixed[] $haystack
	 * @param \Closure $callback
	 */
	public function testGetKeyByCallbackNotFound(
		array $haystack,
		Closure $callback
	): void
	{
		$this->expectException(\Consistence\Type\ArrayType\ElementDoesNotExistException::class);

		ArrayType::getKeyByCallback($haystack, $callback);
	}

	/**
	 * @dataProvider getKeyByValueCallbackDataProvider
	 *
	 * @param mixed[] $haystack
	 * @param \Closure $valueCallback
	 * @param int|string $expectedKey
	 */
	public function testGetKeyByValueCallback(
		array $haystack,
		Closure $valueCallback,
		$expectedKey
	): void
	{
		Assert::assertSame($expectedKey, ArrayType::getKeyByValueCallback($haystack, $valueCallback));
	}

	/**
	 * @dataProvider getKeyByValueCallbackNotFoundDataProvider
	 *
	 * @param mixed[] $haystack
	 * @param \Closure $valueCallback
	 */
	public function testGetKeyByValueCallbackNotFound(
		array $haystack,
		Closure $valueCallback
	): void
	{
		$this->expectException(\Consistence\Type\ArrayType\ElementDoesNotExistException::class);

		ArrayType::getKeyByValueCallback($haystack, $valueCallback);
	}

	/**
	 * @return mixed[][]|\Generator
	 */
	public function keyNotFoundDataProvider(): Generator
	{
		yield 'key not found' => [
			'haystack' => [
				'foo',
				'bar',
			],
			'key' => 2,
		];
	}

	/**
	 * @return mixed[][]|\Generator
	 */
	public function findValueDataProvider(): Generator
	{
		foreach ($this->keyValueStrictDataProvider() as $caseName => $caseData) {
			yield $caseName => [
				'haystack' => $caseData['haystack'],
				'key' => $caseData['key'],
				'expectedValue' => $caseData['value'],
			];
		}

		foreach ($this->keyNotFoundDataProvider() as $caseName => $caseData) {
			yield $caseName => [
				'haystack' => $caseData['haystack'],
				'key' => $caseData['key'],
				'expectedValue' => null,
			];
		}
	}

	/**
	 * @dataProvider findValueDataProvider
	 *
	 * @param mixed[] $haystack
	 * @param int|string $key
	 * @param mixed $expectedValue
	 */
	public function testFindValue(
		array $haystack,
		$key,
		$expectedValue
	): void
	{
		Assert::assertSame($expectedValue, ArrayType::findValue($haystack, $key));
	}

	/**
	 * @return mixed[][]|\Generator
	 */
	public function getValueDataProvider(): Generator
	{
		foreach ($this->keyValueStrictDataProvider() as $caseName => $caseData) {
			yield $caseName => [
				'haystack' => $caseData['haystack'],
				'key' => $caseData['key'],
				'expectedValue' => $caseData['value'],
			];
		}
	}

	/**
	 * @dataProvider getValueDataProvider
	 *
	 * @param mixed[] $haystack
	 * @param int|string $key
	 * @param mixed $expectedValue
	 */
	public function testGetValue(
		array $haystack,
		$key,
		$expectedValue
	): void
	{
		Assert::assertSame($expectedValue, ArrayType::getValue($haystack, $key));
	}

	/**
	 * @return mixed[][]|\Generator
	 */
	public function getValueNotFoundDataProvider(): Generator
	{
		foreach ($this->keyNotFoundDataProvider() as $caseName => $caseData) {
			yield $caseName => [
				'haystack' => $caseData['haystack'],
				'key' => $caseData['key'],
			];
		}
	}

	/**
	 * @dataProvider getValueNotFoundDataProvider
	 *
	 * @param mixed[] $haystack
	 * @param int|string $key
	 */
	public function testGetValueNotFound(
		array $haystack,
		$key
	): void
	{
		$this->expectException(\Consistence\Type\ArrayType\ElementDoesNotExistException::class);

		ArrayType::getValue($haystack, $key);
	}

	/**
	 * @return mixed[][]|\Generator
	 */
	public function expectedKeyValuePairForCallbackDataProvider(): Generator
	{
		foreach ($this->keyValueCallbackDataProvider() as $caseName => $caseData) {
			yield $caseName => [
				'haystack' => $caseData['haystack'],
				'callback' => $caseData['callback'],
				'expectedKeyValuePair' => new KeyValuePair($caseData['expectedKey'], $caseData['expectedValue']),
			];
		}
	}

	/**
	 * @dataProvider expectedKeyValuePairForCallbackDataProvider
	 *
	 * @param mixed[] $haystack
	 * @param \Closure $callback
	 * @param \Consistence\Type\ArrayType\KeyValuePair $expectedKeyValuePair
	 */
	public function testFindByCallback(
		array $haystack,
		Closure $callback,
		KeyValuePair $expectedKeyValuePair
	): void
	{
		$result = ArrayType::findByCallback($haystack, $callback);
		Assert::assertInstanceOf(KeyValuePair::class, $result);
		Assert::assertSame($expectedKeyValuePair->getValue(), $result->getValue());
		Assert::assertSame($expectedKeyValuePair->getKey(), $result->getKey());
	}

	/**
	 * @return mixed[][]|\Generator
	 */
	public function nothingFoundForCallbackDataProvider(): Generator
	{
		foreach ($this->keyValueCallbackNotFoundDataProvider() as $caseName => $caseData) {
			yield $caseName => [
				'haystack' => $caseData['haystack'],
				'callback' => $caseData['callback'],
			];
		}
	}

	/**
	 * @dataProvider nothingFoundForCallbackDataProvider
	 *
	 * @param mixed[] $haystack
	 * @param \Closure $callback
	 */
	public function testFindByCallbackNothingFound(
		array $haystack,
		Closure $callback
	): void
	{
		$result = ArrayType::findByCallback($haystack, $callback);
		Assert::assertNull($result);
	}

	/**
	 * @return mixed[][]|\Generator
	 */
	public function expectedValueForValueCallbackDataProvider(): Generator
	{
		foreach ($this->keyValueCallbackDataProvider() as $caseName => $caseData) {
			yield $caseName => [
				'haystack' => $caseData['haystack'],
				'valueCallback' => $caseData['valueCallback'],
				'expectedValue' => $caseData['expectedValue'],
			];
		}
	}

	/**
	 * @dataProvider expectedValueForValueCallbackDataProvider
	 *
	 * @param mixed[] $haystack
	 * @param \Closure $valueCallback
	 * @param mixed $expectedValue
	 */
	public function testFindValueByCallback(
		array $haystack,
		Closure $valueCallback,
		$expectedValue
	): void
	{
		$result = ArrayType::findValueByCallback($haystack, $valueCallback);
		Assert::assertSame($expectedValue, $result);
	}

	/**
	 * @return mixed[][]|\Generator
	 */
	public function nothingFoundForValueCallbackDataProvider(): Generator
	{
		foreach ($this->keyValueCallbackNotFoundDataProvider() as $caseName => $caseData) {
			yield $caseName => [
				'haystack' => $caseData['haystack'],
				'valueCallback' => $caseData['valueCallback'],
			];
		}
	}

	/**
	 * @dataProvider nothingFoundForValueCallbackDataProvider
	 *
	 * @param mixed[] $haystack
	 * @param \Closure $valueCallback
	 */
	public function testFindValueByCallbackNothingFound(
		array $haystack,
		Closure $valueCallback
	): void
	{
		$result = ArrayType::findValueByCallback($haystack, $valueCallback);
		Assert::assertNull($result);
	}

	/**
	 * @dataProvider expectedKeyValuePairForCallbackDataProvider
	 *
	 * @param mixed[] $haystack
	 * @param \Closure $callback
	 * @param \Consistence\Type\ArrayType\KeyValuePair $expectedKeyValuePair
	 */
	public function testGetByCallback(
		array $haystack,
		Closure $callback,
		KeyValuePair $expectedKeyValuePair
	): void
	{
		$result = ArrayType::getByCallback($haystack, $callback);
		Assert::assertInstanceOf(KeyValuePair::class, $result);
		Assert::assertSame($expectedKeyValuePair->getValue(), $result->getValue());
		Assert::assertSame($expectedKeyValuePair->getKey(), $result->getKey());
	}

	/**
	 * @dataProvider nothingFoundForCallbackDataProvider
	 *
	 * @param mixed[] $haystack
	 * @param \Closure $callback
	 */
	public function testGetByCallbackNothingFound(
		array $haystack,
		Closure $callback
	): void
	{
		$this->expectException(\Consistence\Type\ArrayType\ElementDoesNotExistException::class);

		ArrayType::getByCallback($haystack, $callback);
	}

	/**
	 * @dataProvider expectedValueForValueCallbackDataProvider
	 *
	 * @param mixed[] $haystack
	 * @param \Closure $valueCallback
	 * @param mixed $expectedValue
	 */
	public function testGetValueByCallback(
		array $haystack,
		Closure $valueCallback,
		$expectedValue
	): void
	{
		Assert::assertSame($expectedValue, ArrayType::getValueByCallback($haystack, $valueCallback));
	}

	/**
	 * @dataProvider nothingFoundForValueCallbackDataProvider
	 *
	 * @param mixed[] $haystack
	 * @param \Closure $valueCallback
	 */
	public function testGetValueByCallbackNothingFound(
		array $haystack,
		Closure $valueCallback
	): void
	{
		$this->expectException(\Consistence\Type\ArrayType\ElementDoesNotExistException::class);

		ArrayType::getValueByCallback($haystack, $valueCallback);
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

	/**
	 * @return mixed[][]|\Generator
	 */
	public function removeValueDataProvider(): Generator
	{
		yield 'existing value' => [
			'haystack' => [1, 2, 3],
			'valueToRemove' => 2,
			'expectedReturnValue' => true,
			'expectedValues' => [
				0 => 1,
				2 => 3,
			],
		];
		yield 'nonexistent value' => [
			'haystack' => [1, 2, 3],
			'valueToRemove' => 4,
			'expectedReturnValue' => false,
			'expectedValues' => [1, 2, 3],
		];
	}

	/**
	 * @dataProvider removeValueDataProvider
	 *
	 * @param mixed[] $haystack
	 * @param mixed $valueToRemove
	 * @param bool $expectedReturnValue
	 * @param mixed[] $expectedValues
	 */
	public function testRemoveValue(
		array $haystack,
		$valueToRemove,
		bool $expectedReturnValue,
		array $expectedValues
	): void
	{
		Assert::assertSame($expectedReturnValue, ArrayType::removeValue($haystack, $valueToRemove));
		Assert::assertSame($expectedValues, $haystack);
	}

	/**
	 * @return mixed[][]|\Generator
	 */
	public function removeKeysByArrayKeysDataProvider(): Generator
	{
		yield 'existing keys' => [
			'haystack' => [1, 2, 3],
			'arrayWithKeysToRemove' => [
				0 => 'foo',
				2 => 'bar',
			],
			'expectedReturnValue' => true,
			'expectedValues' => [
				1 => 2,
			],
		];
		yield 'nonexistent keys' => [
			'haystack' => [1, 2, 3],
			'arrayWithKeysToRemove' => [
				4 => 'foo',
				5 => 'bar',
			],
			'expectedReturnValue' => false,
			'expectedValues' => [1, 2, 3],
		];
	}

	/**
	 * @return mixed[][]|\Generator
	 */
	public function removeKeysDataProvider(): Generator
	{
		foreach ($this->removeKeysByArrayKeysDataProvider() as $caseName => $caseData) {
			yield $caseName => [
				'haystack' => $caseData['haystack'],
				'keysToRemove' => array_keys($caseData['arrayWithKeysToRemove']),
				'expectedReturnValue' => $caseData['expectedReturnValue'],
				'expectedValues' => $caseData['expectedValues'],
			];
		}
	}

	/**
	 * @dataProvider removeKeysDataProvider
	 *
	 * @param mixed[] $haystack
	 * @param mixed[] $keysToRemove
	 * @param bool $expectedReturnValue
	 * @param mixed[] $expectedValues
	 */
	public function testRemoveKeys(
		array $haystack,
		array $keysToRemove,
		bool $expectedReturnValue,
		array $expectedValues
	): void
	{
		Assert::assertSame($expectedReturnValue, ArrayType::removeKeys($haystack, $keysToRemove));
		Assert::assertSame($expectedValues, $haystack);
	}

	/**
	 * @dataProvider removeKeysByArrayKeysDataProvider
	 *
	 * @param mixed[] $haystack
	 * @param mixed[] $arrayWithKeysToRemove
	 * @param bool $expectedReturnValue
	 * @param mixed[] $expectedValues
	 */
	public function testRemoveKeysByArrayKeys(
		array $haystack,
		array $arrayWithKeysToRemove,
		bool $expectedReturnValue,
		array $expectedValues
	): void
	{
		Assert::assertSame($expectedReturnValue, ArrayType::removeKeysByArrayKeys($haystack, $arrayWithKeysToRemove));
		Assert::assertSame($expectedValues, $haystack);
	}

	/**
	 * @return mixed[][]|\Generator
	 */
	public function uniqueValuesWithStrictnessDataProvider(): Generator
	{
		yield 'same numeric value as integer and string' => (function (): array {
			$haystack = ['1', 1];

			return [
				'haystack' => $haystack,
				'expectedUniqueValuesStrict' => $haystack,
				'expectedUniqueValuesNonStrict' => [$haystack[0]],
			];
		})();

		yield 'two DateTimeImmutable objects with same value' => (function (): array {
			$haystack = [
				new DateTimeImmutable('2017-01-01T12:00:00.000000'),
				new DateTimeImmutable('2017-01-01T12:00:00.000000'),
			];

			return [
				'haystack' => $haystack,
				'expectedUniqueValuesStrict' => $haystack,
				'expectedUniqueValuesNonStrict' => [$haystack[0]],
			];
		})();

		yield 'combination of string and numeric keys' => (function (): array {
			$haystack = [
				'a' => 'green',
				0 => 'red',
				1 => 'blue',
			];

			return [
				'haystack' => $haystack,
				'expectedUniqueValuesStrict' => $haystack,
				'expectedUniqueValuesNonStrict' => $haystack,
			];
		})();
	}

	/**
	 * @return mixed[][]|\Generator
	 */
	public function uniqueValuesDataProvider(): Generator
	{
		foreach ($this->uniqueValuesWithStrictnessDataProvider() as $caseName => $caseData) {
			yield $caseName => [
				'haystack' => $caseData['haystack'],
				'expectedUniqueValues' => $caseData['expectedUniqueValuesStrict'],
			];
		}
	}

	/**
	 * @dataProvider uniqueValuesDataProvider
	 *
	 * @param mixed[] $haystack
	 * @param mixed[] $expectedUniqueValues
	 */
	public function testUniqueValues(
		array $haystack,
		array $expectedUniqueValues
	): void
	{
		Assert::assertSame($expectedUniqueValues, ArrayType::uniqueValues($haystack));
	}

	/**
	 * @return mixed[][]|\Generator
	 */
	public function uniqueValuesWithStrictParameterDataProvider(): Generator
	{
		foreach ($this->uniqueValuesWithStrictnessDataProvider() as $caseName => $caseData) {
			yield $caseName . '- STRICT_TRUE' => [
				'haystack' => $caseData['haystack'],
				'strict' => ArrayType::STRICT_TRUE,
				'expectedUniqueValues' => $caseData['expectedUniqueValuesStrict'],
			];

			yield $caseName . '- STRICT_FALSE' => [
				'haystack' => $caseData['haystack'],
				'strict' => ArrayType::STRICT_FALSE,
				'expectedUniqueValues' => $caseData['expectedUniqueValuesNonStrict'],
			];
		}
	}

	/**
	 * @dataProvider uniqueValuesWithStrictParameterDataProvider
	 *
	 * @param mixed[] $haystack
	 * @param bool $strict
	 * @param mixed[] $expectedUniqueValues
	 */
	public function testUniqueValuesWithStrictParameter(
		array $haystack,
		bool $strict,
		array $expectedUniqueValues
	): void
	{
		Assert::assertSame($expectedUniqueValues, ArrayType::uniqueValues($haystack, $strict));
	}

	/**
	 * @return mixed[][]|\Generator
	 */
	public function uniqueValuesByCallbackDataProvider(): Generator
	{
		yield 'same numeric value as integer and string, strict comparison in callback' => (function (): array {
			$haystack = ['1', 1];

			return [
				'haystack' => $haystack,
				'callback' => function ($a, $b): bool {
					return $a === $b;
				},
				'expectedUniqueValues' => $haystack,
			];
		})();

		yield 'two DateTimeImmutable objects with same value, strict comparison in callback' => (function (): array {
			$haystack = [
				new DateTimeImmutable('2017-01-01T12:00:00.000000'),
				new DateTimeImmutable('2017-01-01T12:00:00.000000'),
			];

			return [
				'haystack' => $haystack,
				'callback' => function (DateTimeImmutable $a, DateTimeImmutable $b): bool {
					return $a === $b;
				},
				'expectedUniqueValues' => $haystack,
			];
		})();

		yield 'same numeric value as integer and string, non-strict comparison in callback' => (function (): array {
			$haystack = ['1', 1];

			return [
				'haystack' => $haystack,
				'callback' => function ($a, $b): bool {
					return $a == $b;
				},
				'expectedUniqueValues' => [$haystack[0]],
			];
		})();

		yield 'two DateTimeImmutable objects with same value, non-strict comparison in callback' => (function (): array {
			$haystack = [
				new DateTimeImmutable('2017-01-01T12:00:00.000000'),
				new DateTimeImmutable('2017-01-01T12:00:00.000000'),
			];

			return [
				'haystack' => $haystack,
				'callback' => function (DateTimeImmutable $a, DateTimeImmutable $b): bool {
					return $a == $b;
				},
				'expectedUniqueValues' => [$haystack[0]],
			];
		})();
	}

	/**
	 * @dataProvider uniqueValuesByCallbackDataProvider
	 *
	 * @param mixed[] $haystack
	 * @param \Closure $callback
	 * @param mixed[] $expectedUniqueValues
	 */
	public function testUniqueValuesByCallback(
		array $haystack,
		Closure $callback,
		array $expectedUniqueValues
	): void
	{
		Assert::assertSame($expectedUniqueValues, ArrayType::uniqueValuesByCallback($haystack, $callback));
	}

}
