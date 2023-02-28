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
			'foo',
			'string',
		];
		yield 'integer' => [
			1,
			'int',
		];
		yield 'boolean' => [
			true,
			'bool',
		];
		yield 'float' => [
			1.5,
			'float',
		];
		yield 'array' => [
			[],
			'array',
		];
		yield 'null' => [
			null,
			'null',
		];
		yield 'object' => [
			new DateTimeImmutable(),
			DateTimeImmutable::class,
		];
		yield 'Closure' => [
			static function (): void {
				return;
			},
			Closure::class,
		];
		yield 'resource' => [
			fopen(__DIR__, 'r'),
			'resource',
		];
	}

	/**
	 * @return mixed[][]|\Generator
	 */
	public function hasTypeDataProvider(): Generator
	{
		foreach ($this->typeDataProvider() as $caseName => $caseData) {
			yield $caseName => $caseData;
		}

		yield 'null uppercase' => [
			null,
			'NULL',
		];
		yield 'integer is string or int' => [
			1,
			'string|int',
		];
		yield 'integer is string or integer' => [
			1,
			'string|integer',
		];
		yield 'string is string or int' => [
			'foo',
			'string|int',
		];
		yield 'integer is null or int' => [
			2,
			'null|int',
		];
		yield 'integer is null or integer' => [
			2,
			'null|integer',
		];
		yield 'boolean is null or bool' => [
			true,
			'null|bool',
		];
		yield 'boolean is bool or null' => [
			false,
			'bool|null',
		];
		yield 'null is null or int' => [
			null,
			'null|int',
		];
		yield 'null is null (uppercase) or int' => [
			null,
			'NULL|int',
		];
		yield 'null is null (uppercase) or integer - uppercase' => [
			null,
			'NULL|integer',
		];
		yield 'string is class or string' => [
			DateTimeImmutable::class,
			'DateTimeImmutable|string',
		];
		yield 'object is class of object or string' => [
			new DateTimeImmutable(),
			'DateTimeImmutable|string',
		];
		yield 'object is object' => [
			new DateTimeImmutable(),
			'object',
		];
		yield 'string is not object' => [
			'foo',
			'object',
			false,
		];
		yield 'integer is mixed' => [
			1,
			'mixed',
		];
		yield 'string is mixed' => [
			'foo',
			'mixed',
		];
		yield 'string containing class name is mixed' => [
			DateTimeImmutable::class,
			'mixed',
		];
		yield 'integer is string or mixed' => [
			1,
			'string|mixed',
		];
		yield 'empty array is iterable<string>' => [
			[],
			'string[]',
		];
		yield 'string is not iterable<string>' => [
			'foo',
			'string[]',
			false,
		];
		yield 'array<integer, string> is iterable<string>' => [
			['foo'],
			'string[]',
		];
		yield 'array<integer, integer> is not iterable<string>' => [
			[1],
			'string[]',
			false,
		];
		yield 'array<integer, string> with multiple strings is iterable<string>' => [
			['foo', 'bar'],
			'string[]',
		];
		yield 'empty array is iterable<string> or iterable<int>' => [
			[],
			'string[]|int[]',
		];
		yield 'array<integer, string> is iterable<string> or iterable<int>' => [
			['foo', 'bar'],
			'string[]|int[]',
		];
		yield 'integer is iterable<string> or int' => [
			2,
			'string[]|int',
		];
		yield 'array<integer, integer> is iterable<string> or iterable<int>' => [
			[1, 2],
			'string[]|int[]',
		];
		yield 'array<integer, DateTimeImmutable> is iterable<object>' => [
			[new DateTimeImmutable()],
			'object[]',
		];
		yield 'array<integer, object> is iterable<object>' => [
			[new DateTimeImmutable(), new stdClass()],
			'object[]',
		];
		yield 'array<integer, object|string> is not iterable<object>' => [
			[new DateTimeImmutable(), 'foo'],
			'object[]',
			false,
		];
		yield 'array<integer, integer|string> is iterable<mixed>' => [
			[1, 'foo'],
			'mixed[]',
		];
		yield 'integer is not iterable<mixed>' => [
			1,
			'mixed[]',
			false,
		];
		yield 'array<integer, integer|string> is mixed' => [
			[1, 'foo'],
			'mixed',
		];
		yield 'array<integer, array<integer, integer>> is iterable<iterable<int>>' => [
			[[1, 2]],
			'int[][]',
		];
		yield 'array<integer, array<integer, integer>> with multiple array<integer, integer> is iterable<iterable<int>>' => [
			[[1, 2], [3, 4]],
			'int[][]',
		];
		yield 'array<integer, array<integer, integer>> with multiple array<integer, integer> is iterable<iterable<integer>>' => [
			[[1, 2], [3, 4]],
			'integer[][]',
		];
		yield 'array<integer, array<integer, integer>|array<integer, string>> is not iterable<iterable<int>>' => [
			[[1, 2], ['foo']],
			'int[][]',
			false,
		];
		yield 'array<integer, array<integer, string>> is not iterable<iterable<int>>' => [
			[['foo']],
			'int[][]',
			false,
		];
		yield 'array<integer, integer> is not iterable<iterable<int>>' => [
			[1, 2],
			'int[][]',
			false,
		];
		yield 'empty ArrayObject is iterable<string>' => [
			new ArrayObject([]),
			'string[]',
		];
		yield 'ArrayObject<integer, string> is iterable<string>' => [
			new ArrayObject(['foo']),
			'string[]',
		];
		yield 'ArrayObject<integer, integer> is not iterable<string>' => [
			new ArrayObject([1]),
			'string[]',
			false,
		];
		yield 'ArrayObject<integer, string> with multiple strings is iterable<string>' => [
			new ArrayObject(['foo', 'bar']),
			'string[]',
		];
		yield 'empty ArrayObject is iterable<string> or iterable<int>' => [
			new ArrayObject([]),
			'string[]|int[]',
		];
		yield 'ArrayObject<integer, string> is iterable<string> or iterable<int>' => [
			new ArrayObject(['foo', 'bar']),
			'string[]|int[]',
		];
		yield 'ArrayObject<integer, integer> is iterable<string> or iterable<int>' => [
			new ArrayObject([1, 2]),
			'string[]|int[]',
		];
		yield 'ArrayObject<ArrayObject<integer, integer>> is iterable<iterable<int>>' => [
			new ArrayObject([new ArrayObject([1, 2])]),
			'int[][]',
		];
		yield 'ArrayObject<ArrayObject<integer, integer>> with multiple ArrayObject<integer, integer> is iterable<iterable<int>>' => [
			new ArrayObject([new ArrayObject([1, 2]), new ArrayObject([3, 4])]),
			'int[][]',
		];
		yield 'ArrayObject<integer, ArrayObject<integer, integer>|ArrayObject<integer, string>> is not iterable<iterable<int>>' => [
			new ArrayObject([new ArrayObject([1, 2]), new ArrayObject(['foo'])]),
			'int[][]',
			false,
		];
		yield 'ArrayObject<integer, ArrayObject<integer, string>> is not iterable<iterable<int>>' => [
			new ArrayObject([new ArrayObject(['foo'])]),
			'int[][]',
			false,
		];
		yield 'ArrayObject<integer, integer> is not iterable<iterable<int>>' => [
			new ArrayObject([1, 2]),
			'int[][]',
			false,
		];
		yield 'empty array is iterable<int, string>' => [
			[],
			'int:string[]',
		];
		yield 'string is not iterable<int, string>' => [
			'foo',
			'int:string[]',
			false,
		];
		yield 'array<integer, string> is iterable<int, string>' => [
			['foo'],
			'int:string[]',
		];
		yield 'array<integer, integer> is not iterable<int, string>' => [
			[1],
			'int:string[]',
			false,
		];
		yield 'array<integer, string> with multiple strings is iterable<int, string>' => [
			['foo', 'bar'],
			'int:string[]',
		];
		yield 'empty array is iterable<int, string> or iterable<int, int>' => [
			[],
			'int:string[]|int:int[]',
		];
		yield 'array<integer, string> is iterable<int, string> or iterable<int, int>' => [
			['foo', 'bar'],
			'int:string[]|int:int[]',
		];
		yield 'array<integer, integer> is iterable<int, string> or iterable<int, int>' => [
			[1, 2],
			'int:string[]|int:int[]',
		];
		yield 'array<integer, integer|string> is iterable<int, mixed>' => [
			[1, 'foo'],
			'int:mixed[]',
		];
		yield 'integer is not iterable<int, mixed>' => [
			1,
			'int:mixed[]',
			false,
		];
		yield 'array<integer, array<integer, integer>> is iterable<int, iterable<int, int>>' => [
			[[1, 2]],
			'int:int:int[][]',
		];
		yield 'array<integer, array<integer, integer>> with multiple array<integer, integer> is iterable<int, iterable<int, int>>' => [
			[[1, 2], [3, 4]],
			'int:int:int[][]',
		];
		yield 'array<integer, array<integer, integer>|array<integer, string>> is not iterable<int, iterable<int, int>>' => [
			[[1, 2], ['foo']],
			'int:int:int[][]',
			false,
		];
		yield 'empty ArrayObject is iterable<int, string>' => [
			new ArrayObject([]),
			'int:string[]',
		];
		yield 'ArrayObject<integer, string> is iterable<int, string>' => [
			new ArrayObject(['foo']),
			'int:string[]',
		];
		yield 'ArrayObject<integer, integer> is not iterable<int, string>' => [
			new ArrayObject([1]),
			'int:string[]',
			false,
		];
		yield 'ArrayObject<integer, string> with multiple strings is iterable<int, string>' => [
			new ArrayObject(['foo', 'bar']),
			'int:string[]',
		];
		yield 'empty ArrayObject is iterable<int, string> or iterable<int, int>' => [
			new ArrayObject([]),
			'int:string[]|int:int[]',
		];
		yield 'ArrayObject<integer, string> is iterable<int, string> or iterable<int, int>' => [
			new ArrayObject(['foo', 'bar']),
			'int:string[]|int:int[]',
		];
		yield 'ArrayObject<integer, integer> is iterable<int, string> or iterable<int, int>' => [
			new ArrayObject([1, 2]),
			'int:string[]|int:int[]',
		];
		yield 'ArrayObject<integer, ArrayObject<integer, integer>> is iterable<int, iterable<int, int>>' => [
			new ArrayObject([new ArrayObject([1, 2])]),
			'int:int:int[][]',
		];
		yield 'ArrayObject<integer, ArrayObject<integer, integer>> with multiple ArrayObject<integer, integer> is iterable<int, iterable<int, int>>' => [
			new ArrayObject([new ArrayObject([1, 2]), new ArrayObject([3, 4])]),
			'int:int:int[][]',
		];
		yield 'ArrayObject<integer, ArrayObject<integer, integer>|ArrayObject<integer, string>> is not iterable<int, iterable<int, int>>' => [
			new ArrayObject([new ArrayObject([1, 2]), new ArrayObject(['foo'])]),
			'int:int:int[][]',
			false,
		];
		yield 'ArrayObject<integer, ArrayObject<integer, string>> is not iterable<int, iterable<int, int>>' => [
			new ArrayObject([new ArrayObject(['foo'])]),
			'int:int:int[][]',
			false,
		];
		yield 'ArrayObject<integer, integer> is not iterable<int, iterable<int, int>>' => [
			new ArrayObject([1, 2]),
			'int:int:int[][]',
			false,
		];
		yield 'array<string, string> is iterable<string, string>' => [
			['foo' => 'bar'],
			'string:string[]',
		];
		yield 'array<integer, string> is not iterable<string, string>' => [
			['foo', 'bar'],
			'string:string[]',
			false,
		];
		yield 'array<integer, string> is iterable<string, string> or iterable<int, string>' => [
			['foo', 'bar'],
			'string:string[]|int:string[]',
		];
		yield 'array<string, string> is iterable<string, string> or iterable<int, string>' => [
			['foo' => 'bar'],
			'string:string[]|int:string[]',
		];
		yield 'array<string, array<integer, string>> is iterable<string, iterable<int, string>>' => [
			['foo' => ['bar']],
			'string:int:string[][]',
		];
		yield 'array<string, array<integer, string>> is not iterable<string, iterable<string, string>>' => [
			['foo' => ['bar']],
			'string:string:string[][]',
			false,
		];
		yield 'array<integer, array<string, string>> is iterable<int, iterable<string, string>>' => [
			[['foo' => 'bar']],
			'int:string:string[][]',
		];
		yield 'array<integer, array<string, string>> is not iterable<int, iterable<int, string>> ' => [
			[['foo' => 'bar']],
			'int:int:string[][]',
			false,
		];
		yield 'array<string, array<integer, string>> is iterable<string, iterable<string>>' => [
			['foo' => ['bar']],
			'string:string[][]',
		];
		yield 'array<string, array<integer, string>> is not iterable<int, iterable<string>>' => [
			['foo' => ['bar']],
			'int:string[][]',
			false,
		];
		yield 'array<string, array<integer, string>> is iterable<mixed, iterable<int, string>>' => [
			['foo' => ['bar']],
			'mixed:int:string[][]',
		];
		yield 'array<string, array<integer, string>> is not iterable<mixed, iterable<string, string>>' => [
			['foo' => ['bar']],
			'mixed:string:string[][]',
			false,
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
