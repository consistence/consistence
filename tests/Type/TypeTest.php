<?php

declare(strict_types = 1);

namespace Consistence\Type;

use ArrayObject;
use Closure;
use DateTimeImmutable;
use DateTimeInterface;
use Generator;
use PHPUnit\Framework\Assert;
use stdClass;

class TypeTest extends \PHPUnit\Framework\TestCase
{

	public function testStaticConstruct(): void
	{
		$this->expectException(\Consistence\StaticClassException::class);

		new Type();
	}

	/**
	 * @return mixed[][]|\Generator
	 */
	public function typeDataProvider(): Generator
	{
		yield 'string' => [
			'value' => 'foo',
			'valueType' => 'string',
		];
		yield 'integer' => [
			'value' => 1,
			'valueType' => 'int',
		];
		yield 'boolean' => [
			'value' => true,
			'valueType' => 'bool',
		];
		yield 'float' => [
			'value' => 1.5,
			'valueType' => 'float',
		];
		yield 'array' => [
			'value' => [],
			'valueType' => 'array',
		];
		yield 'null' => [
			'value' => null,
			'valueType' => 'null',
		];
		yield 'object' => [
			'value' => new DateTimeImmutable(),
			'valueType' => DateTimeImmutable::class,
		];
		yield 'Closure' => [
			'value' => static function (): void {
				return;
			},
			'valueType' => Closure::class,
		];
		yield 'resource' => [
			'value' => fopen(__DIR__, 'r'),
			'valueType' => 'resource',
		];
	}

	/**
	 * @return mixed[][]|\Generator
	 */
	public function hasTypeDataProvider(): Generator
	{
		foreach ($this->typeDataProvider() as $caseName => $caseData) {
			yield $caseName => [
				'value' => $caseData['value'],
				'expectedTypes' => $caseData['valueType'],
			];
		}

		yield 'null uppercase' => [
			'value' => null,
			'expectedTypes' => 'NULL',
		];
		yield 'integer is string or int' => [
			'value' => 1,
			'expectedTypes' => 'string|int',
		];
		yield 'integer is string or integer' => [
			'value' => 1,
			'expectedTypes' => 'string|integer',
		];
		yield 'string is string or int' => [
			'value' => 'foo',
			'expectedTypes' => 'string|int',
		];
		yield 'integer is null or int' => [
			'value' => 2,
			'expectedTypes' => 'null|int',
		];
		yield 'integer is null or integer' => [
			'value' => 2,
			'expectedTypes' => 'null|integer',
		];
		yield 'boolean is null or bool' => [
			'value' => true,
			'expectedTypes' => 'null|bool',
		];
		yield 'boolean is bool or null' => [
			'value' => false,
			'expectedTypes' => 'bool|null',
		];
		yield 'null is null or int' => [
			'value' => null,
			'expectedTypes' => 'null|int',
		];
		yield 'null is null (uppercase) or int' => [
			'value' => null,
			'expectedTypes' => 'NULL|int',
		];
		yield 'null is null (uppercase) or integer - uppercase' => [
			'value' => null,
			'expectedTypes' => 'NULL|integer',
		];
		yield 'string is class or string' => [
			'value' => DateTimeImmutable::class,
			'expectedTypes' => 'DateTimeImmutable|string',
		];
		yield 'object is class of object or string' => [
			'value' => new DateTimeImmutable(),
			'expectedTypes' => 'DateTimeImmutable|string',
		];
		yield 'object is object' => [
			'value' => new DateTimeImmutable(),
			'expectedTypes' => 'object',
		];
		yield 'string is not object' => [
			'value' => 'foo',
			'expectedTypes' => 'object',
			'result' => false,
		];
		yield 'integer is mixed' => [
			'value' => 1,
			'expectedTypes' => 'mixed',
		];
		yield 'string is mixed' => [
			'value' => 'foo',
			'expectedTypes' => 'mixed',
		];
		yield 'string containing class name is mixed' => [
			'value' => DateTimeImmutable::class,
			'expectedTypes' => 'mixed',
		];
		yield 'integer is string or mixed' => [
			'value' => 1,
			'expectedTypes' => 'string|mixed',
		];
		yield 'empty array is iterable<string>' => [
			'value' => [],
			'expectedTypes' => 'string[]',
		];
		yield 'string is not iterable<string>' => [
			'value' => 'foo',
			'expectedTypes' => 'string[]',
			'result' => false,
		];
		yield 'array<integer, string> is iterable<string>' => [
			'value' => ['foo'],
			'expectedTypes' => 'string[]',
		];
		yield 'array<integer, integer> is not iterable<string>' => [
			'value' => [1],
			'expectedTypes' => 'string[]',
			'result' => false,
		];
		yield 'array<integer, string> with multiple strings is iterable<string>' => [
			'value' => ['foo', 'bar'],
			'expectedTypes' => 'string[]',
		];
		yield 'empty array is iterable<string> or iterable<int>' => [
			'value' => [],
			'expectedTypes' => 'string[]|int[]',
		];
		yield 'array<integer, string> is iterable<string> or iterable<int>' => [
			'value' => ['foo', 'bar'],
			'expectedTypes' => 'string[]|int[]',
		];
		yield 'integer is iterable<string> or int' => [
			'value' => 2,
			'expectedTypes' => 'string[]|int',
		];
		yield 'array<integer, integer> is iterable<string> or iterable<int>' => [
			'value' => [1, 2],
			'expectedTypes' => 'string[]|int[]',
		];
		yield 'array<integer, DateTimeImmutable> is iterable<object>' => [
			'value' => [new DateTimeImmutable()],
			'expectedTypes' => 'object[]',
		];
		yield 'array<integer, object> is iterable<object>' => [
			'value' => [new DateTimeImmutable(), new stdClass()],
			'expectedTypes' => 'object[]',
		];
		yield 'array<integer, object|string> is not iterable<object>' => [
			'value' => [new DateTimeImmutable(), 'foo'],
			'expectedTypes' => 'object[]',
			'result' => false,
		];
		yield 'array<integer, integer|string> is iterable<mixed>' => [
			'value' => [1, 'foo'],
			'expectedTypes' => 'mixed[]',
		];
		yield 'integer is not iterable<mixed>' => [
			'value' => 1,
			'expectedTypes' => 'mixed[]',
			'result' => false,
		];
		yield 'array<integer, integer|string> is mixed' => [
			'value' => [1, 'foo'],
			'expectedTypes' => 'mixed',
		];
		yield 'array<integer, array<integer, integer>> is iterable<iterable<int>>' => [
			'value' => [[1, 2]],
			'expectedTypes' => 'int[][]',
		];
		yield 'array<integer, array<integer, integer>> with multiple array<integer, integer> is iterable<iterable<int>>' => [
			'value' => [[1, 2], [3, 4]],
			'expectedTypes' => 'int[][]',
		];
		yield 'array<integer, array<integer, integer>> with multiple array<integer, integer> is iterable<iterable<integer>>' => [
			'value' => [[1, 2], [3, 4]],
			'expectedTypes' => 'integer[][]',
		];
		yield 'array<integer, array<integer, integer>|array<integer, string>> is not iterable<iterable<int>>' => [
			'value' => [[1, 2], ['foo']],
			'expectedTypes' => 'int[][]',
			'result' => false,
		];
		yield 'array<integer, array<integer, string>> is not iterable<iterable<int>>' => [
			'value' => [['foo']],
			'expectedTypes' => 'int[][]',
			'result' => false,
		];
		yield 'array<integer, integer> is not iterable<iterable<int>>' => [
			'value' => [1, 2],
			'expectedTypes' => 'int[][]',
			'result' => false,
		];
		yield 'empty ArrayObject is iterable<string>' => [
			'value' => new ArrayObject([]),
			'expectedTypes' => 'string[]',
		];
		yield 'ArrayObject<integer, string> is iterable<string>' => [
			'value' => new ArrayObject(['foo']),
			'expectedTypes' => 'string[]',
		];
		yield 'ArrayObject<integer, integer> is not iterable<string>' => [
			'value' => new ArrayObject([1]),
			'expectedTypes' => 'string[]',
			'result' => false,
		];
		yield 'ArrayObject<integer, string> with multiple strings is iterable<string>' => [
			'value' => new ArrayObject(['foo', 'bar']),
			'expectedTypes' => 'string[]',
		];
		yield 'empty ArrayObject is iterable<string> or iterable<int>' => [
			'value' => new ArrayObject([]),
			'expectedTypes' => 'string[]|int[]',
		];
		yield 'ArrayObject<integer, string> is iterable<string> or iterable<int>' => [
			'value' => new ArrayObject(['foo', 'bar']),
			'expectedTypes' => 'string[]|int[]',
		];
		yield 'ArrayObject<integer, integer> is iterable<string> or iterable<int>' => [
			'value' => new ArrayObject([1, 2]),
			'expectedTypes' => 'string[]|int[]',
		];
		yield 'ArrayObject<ArrayObject<integer, integer>> is iterable<iterable<int>>' => [
			'value' => new ArrayObject([new ArrayObject([1, 2])]),
			'expectedTypes' => 'int[][]',
		];
		yield 'ArrayObject<ArrayObject<integer, integer>> with multiple ArrayObject<integer, integer> is iterable<iterable<int>>' => [
			'value' => new ArrayObject([new ArrayObject([1, 2]), new ArrayObject([3, 4])]),
			'expectedTypes' => 'int[][]',
		];
		yield 'ArrayObject<integer, ArrayObject<integer, integer>|ArrayObject<integer, string>> is not iterable<iterable<int>>' => [
			'value' => new ArrayObject([new ArrayObject([1, 2]), new ArrayObject(['foo'])]),
			'expectedTypes' => 'int[][]',
			'result' => false,
		];
		yield 'ArrayObject<integer, ArrayObject<integer, string>> is not iterable<iterable<int>>' => [
			'value' => new ArrayObject([new ArrayObject(['foo'])]),
			'expectedTypes' => 'int[][]',
			'result' => false,
		];
		yield 'ArrayObject<integer, integer> is not iterable<iterable<int>>' => [
			'value' => new ArrayObject([1, 2]),
			'expectedTypes' => 'int[][]',
			'result' => false,
		];
		yield 'empty array is iterable<int, string>' => [
			'value' => [],
			'expectedTypes' => 'int:string[]',
		];
		yield 'string is not iterable<int, string>' => [
			'value' => 'foo',
			'expectedTypes' => 'int:string[]',
			'result' => false,
		];
		yield 'array<integer, string> is iterable<int, string>' => [
			'value' => ['foo'],
			'expectedTypes' => 'int:string[]',
		];
		yield 'array<integer, integer> is not iterable<int, string>' => [
			'value' => [1],
			'expectedTypes' => 'int:string[]',
			'result' => false,
		];
		yield 'array<integer, string> with multiple strings is iterable<int, string>' => [
			'value' => ['foo', 'bar'],
			'expectedTypes' => 'int:string[]',
		];
		yield 'empty array is iterable<int, string> or iterable<int, int>' => [
			'value' => [],
			'expectedTypes' => 'int:string[]|int:int[]',
		];
		yield 'array<integer, string> is iterable<int, string> or iterable<int, int>' => [
			'value' => ['foo', 'bar'],
			'expectedTypes' => 'int:string[]|int:int[]',
		];
		yield 'array<integer, integer> is iterable<int, string> or iterable<int, int>' => [
			'value' => [1, 2],
			'expectedTypes' => 'int:string[]|int:int[]',
		];
		yield 'array<integer, integer|string> is iterable<int, mixed>' => [
			'value' => [1, 'foo'],
			'expectedTypes' => 'int:mixed[]',
		];
		yield 'integer is not iterable<int, mixed>' => [
			'value' => 1,
			'expectedTypes' => 'int:mixed[]',
			'result' => false,
		];
		yield 'array<integer, array<integer, integer>> is iterable<int, iterable<int, int>>' => [
			'value' => [[1, 2]],
			'expectedTypes' => 'int:int:int[][]',
		];
		yield 'array<integer, array<integer, integer>> with multiple array<integer, integer> is iterable<int, iterable<int, int>>' => [
			'value' => [[1, 2], [3, 4]],
			'expectedTypes' => 'int:int:int[][]',
		];
		yield 'array<integer, array<integer, integer>|array<integer, string>> is not iterable<int, iterable<int, int>>' => [
			'value' => [[1, 2], ['foo']],
			'expectedTypes' => 'int:int:int[][]',
			'result' => false,
		];
		yield 'empty ArrayObject is iterable<int, string>' => [
			'value' => new ArrayObject([]),
			'expectedTypes' => 'int:string[]',
		];
		yield 'ArrayObject<integer, string> is iterable<int, string>' => [
			'value' => new ArrayObject(['foo']),
			'expectedTypes' => 'int:string[]',
		];
		yield 'ArrayObject<integer, integer> is not iterable<int, string>' => [
			'value' => new ArrayObject([1]),
			'expectedTypes' => 'int:string[]',
			'result' => false,
		];
		yield 'ArrayObject<integer, string> with multiple strings is iterable<int, string>' => [
			'value' => new ArrayObject(['foo', 'bar']),
			'expectedTypes' => 'int:string[]',
		];
		yield 'empty ArrayObject is iterable<int, string> or iterable<int, int>' => [
			'value' => new ArrayObject([]),
			'expectedTypes' => 'int:string[]|int:int[]',
		];
		yield 'ArrayObject<integer, string> is iterable<int, string> or iterable<int, int>' => [
			'value' => new ArrayObject(['foo', 'bar']),
			'expectedTypes' => 'int:string[]|int:int[]',
		];
		yield 'ArrayObject<integer, integer> is iterable<int, string> or iterable<int, int>' => [
			'value' => new ArrayObject([1, 2]),
			'expectedTypes' => 'int:string[]|int:int[]',
		];
		yield 'ArrayObject<integer, ArrayObject<integer, integer>> is iterable<int, iterable<int, int>>' => [
			'value' => new ArrayObject([new ArrayObject([1, 2])]),
			'expectedTypes' => 'int:int:int[][]',
		];
		yield 'ArrayObject<integer, ArrayObject<integer, integer>> with multiple ArrayObject<integer, integer> is iterable<int, iterable<int, int>>' => [
			'value' => new ArrayObject([new ArrayObject([1, 2]), new ArrayObject([3, 4])]),
			'expectedTypes' => 'int:int:int[][]',
		];
		yield 'ArrayObject<integer, ArrayObject<integer, integer>|ArrayObject<integer, string>> is not iterable<int, iterable<int, int>>' => [
			'value' => new ArrayObject([new ArrayObject([1, 2]), new ArrayObject(['foo'])]),
			'expectedTypes' => 'int:int:int[][]',
			'result' => false,
		];
		yield 'ArrayObject<integer, ArrayObject<integer, string>> is not iterable<int, iterable<int, int>>' => [
			'value' => new ArrayObject([new ArrayObject(['foo'])]),
			'expectedTypes' => 'int:int:int[][]',
			'result' => false,
		];
		yield 'ArrayObject<integer, integer> is not iterable<int, iterable<int, int>>' => [
			'value' => new ArrayObject([1, 2]),
			'expectedTypes' => 'int:int:int[][]',
			'result' => false,
		];
		yield 'array<string, string> is iterable<string, string>' => [
			'value' => ['foo' => 'bar'],
			'expectedTypes' => 'string:string[]',
		];
		yield 'array<integer, string> is not iterable<string, string>' => [
			'value' => ['foo', 'bar'],
			'expectedTypes' => 'string:string[]',
			'result' => false,
		];
		yield 'array<integer, string> is iterable<string, string> or iterable<int, string>' => [
			'value' => ['foo', 'bar'],
			'expectedTypes' => 'string:string[]|int:string[]',
		];
		yield 'array<string, string> is iterable<string, string> or iterable<int, string>' => [
			'value' => ['foo' => 'bar'],
			'expectedTypes' => 'string:string[]|int:string[]',
		];
		yield 'array<string, array<integer, string>> is iterable<string, iterable<int, string>>' => [
			'value' => ['foo' => ['bar']],
			'expectedTypes' => 'string:int:string[][]',
		];
		yield 'array<string, array<integer, string>> is not iterable<string, iterable<string, string>>' => [
			'value' => ['foo' => ['bar']],
			'expectedTypes' => 'string:string:string[][]',
			'result' => false,
		];
		yield 'array<integer, array<string, string>> is iterable<int, iterable<string, string>>' => [
			'value' => [['foo' => 'bar']],
			'expectedTypes' => 'int:string:string[][]',
		];
		yield 'array<integer, array<string, string>> is not iterable<int, iterable<int, string>> ' => [
			'value' => [['foo' => 'bar']],
			'expectedTypes' => 'int:int:string[][]',
			'result' => false,
		];
		yield 'array<string, array<integer, string>> is iterable<string, iterable<string>>' => [
			'value' => ['foo' => ['bar']],
			'expectedTypes' => 'string:string[][]',
		];
		yield 'array<string, array<integer, string>> is not iterable<int, iterable<string>>' => [
			'value' => ['foo' => ['bar']],
			'expectedTypes' => 'int:string[][]',
			'result' => false,
		];
		yield 'array<string, array<integer, string>> is iterable<mixed, iterable<int, string>>' => [
			'value' => ['foo' => ['bar']],
			'expectedTypes' => 'mixed:int:string[][]',
		];
		yield 'array<string, array<integer, string>> is not iterable<mixed, iterable<string, string>>' => [
			'value' => ['foo' => ['bar']],
			'expectedTypes' => 'mixed:string:string[][]',
			'result' => false,
		];
	}

	/**
	 * @dataProvider typeDataProvider
	 *
	 * @param mixed $value
	 * @param string $valueType
	 */
	public function testTypes($value, string $valueType): void
	{
		Assert::assertSame($valueType, Type::getType($value));
	}

	/**
	 * @dataProvider hasTypeDataProvider
	 *
	 * @param mixed $value
	 * @param string $expectedTypes
	 * @param bool $result
	 */
	public function testHasType($value, string $expectedTypes, bool $result = true): void
	{
		Assert::assertSame($result, Type::hasType($value, $expectedTypes));
	}

	public function testCheckTypeOk(): void
	{
		$this->expectNotToPerformAssertions();

		Type::checkType('foo', 'string');
	}

	/**
	 * @dataProvider typeDataProvider
	 *
	 * @param mixed $value
	 * @param string $valueType
	 */
	public function testCheckTypeException($value, string $valueType): void
	{
		try {
			Type::checkType($value, 'Foo');
			Assert::fail('Exception expected');
		} catch (\Consistence\InvalidArgumentTypeException $e) {
			Assert::assertSame($value, $e->getValue());
			Assert::assertSame($valueType, $e->getValueType());
			Assert::assertSame('Foo', $e->getExpectedTypes());
		}
	}

	public function testAllowSubtypes(): void
	{
		Assert::assertTrue(Type::hasType(new DateTimeImmutable(), DateTimeInterface::class));
	}

	public function testDisallowSubtypes(): void
	{
		Assert::assertFalse(Type::hasType(new DateTimeImmutable(), DateTimeInterface::class, Type::SUBTYPES_DISALLOW));
	}

}
