<?php

declare(strict_types = 1);

namespace Consistence\Type;

use ArrayObject;
use Closure;
use DateTimeImmutable;
use DateTimeInterface;
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
	 * @return mixed[][]
	 */
	public function typesDataProvider(): array
	{
		return [
			'string' => ['foo', 'string'],
			'integer' => [1, 'int'],
			'boolean' => [true, 'bool'],
			'float' => [1.5, 'float'],
			'array' => [[], 'array'],
			'null' => [null, 'null'],
			'object' => [new DateTimeImmutable(), DateTimeImmutable::class],
			'Closure' => [static function (): void {
				return;
			}, Closure::class],
			'resource' => [fopen(__DIR__, 'r'), 'resource'],
		];
	}

	/**
	 * @return mixed[][]
	 */
	public function typeChecksDataProvider(): array
	{
		return array_merge(
			$this->typesDataProvider(),
			[
				'null uppercase' => [null, 'NULL'],
				'integer is string or int' => [1, 'string|int'],
				'integer is string or integer' => [1, 'string|integer'],
				'string is string or int' => ['foo', 'string|int'],
				'integer is null or int' => [2, 'null|int'],
				'integer is null or integer' => [2, 'null|integer'],
				'boolean is null or bool' => [true, 'null|bool'],
				'boolean is bool or null' => [false, 'bool|null'],
				'null is null or int' => [null, 'null|int'],
				'null is null (uppercase) or int' => [null, 'NULL|int'],
				'null is null (uppercase) or integer - uppercase' => [null, 'NULL|integer'],
				'string is class or string' => [DateTimeImmutable::class, 'DateTimeImmutable|string'],
				'object is class of object or string' => [new DateTimeImmutable(), 'DateTimeImmutable|string'],
				'object is object' => [new DateTimeImmutable(), 'object'],
				'string is not object' => ['foo', 'object', false],
				'integer is mixed' => [1, 'mixed'],
				'string is mixed' => ['foo', 'mixed'],
				'class-string is mixed' => [DateTimeImmutable::class, 'mixed'],
				'integer is string or mixed' => [1, 'string|mixed'],
				'empty array is iterable<string>' => [[], 'string[]'],
				'string is not iterable<string>' => ['foo', 'string[]', false],
				'array<integer, string> is iterable<string>' => [['foo'], 'string[]'],
				'array<integer, integer> is not iterable<string>' => [[1], 'string[]', false],
				'array<integer, string> with multiple strings is iterable<string>' => [['foo', 'bar'], 'string[]'],
				'empty array is iterable<string> or iterable<int>' => [[], 'string[]|int[]'],
				'array<integer, string> is iterable<string> or iterable<int>' => [['foo', 'bar'], 'string[]|int[]'],
				'integer is iterable<string> or int' => [2, 'string[]|int'],
				'array<integer, integer> is iterable<string> or iterable<int>' => [[1, 2], 'string[]|int[]'],
				'array<integer, DateTimeImmutable> is iterable<object>' => [[new DateTimeImmutable()], 'object[]'],
				'array<integer, object> is iterable<object>' => [[new DateTimeImmutable(), new stdClass()], 'object[]'],
				'array<integer, object|string> is not iterable<object>' => [[new DateTimeImmutable(), 'foo'], 'object[]', false],
				'array<integer, integer|string> is iterable<mixed>' => [[1, 'foo'], 'mixed[]'],
				'integer is not iterable<mixed>' => [1, 'mixed[]', false],
				'array<integer, integer|string> is mixed' => [[1, 'foo'], 'mixed'],
				'array<integer, array<integer, integer>> is iterable<iterable<int>>' => [[[1, 2]], 'int[][]'],
				'array<integer, array<integer, integer>> with multiple array<integer, integer> is iterable<iterable<int>>' => [[[1, 2], [3, 4]], 'int[][]'],
				'array<integer, array<integer, integer>> with multiple array<integer, integer> is iterable<iterable<integer>>' => [[[1, 2], [3, 4]], 'integer[][]'],
				'array<integer, array<integer, integer>|array<integer, string>> is not iterable<iterable<int>>' => [[[1, 2], ['foo']], 'int[][]', false],
				'array<integer, array<integer, string>> is not iterable<iterable<int>>' => [[['foo']], 'int[][]', false],
				'array<integer, integer> is not iterable<iterable<int>>' => [[1, 2], 'int[][]', false],
				'empty ArrayObject is iterable<string>' => [new ArrayObject([]), 'string[]'],
				'ArrayObject<integer, string> is iterable<string>' => [new ArrayObject(['foo']), 'string[]'],
				'ArrayObject<integer, integer> is not iterable<string>' => [new ArrayObject([1]), 'string[]', false],
				'ArrayObject<integer, string> with multiple strings is iterable<string>' => [new ArrayObject(['foo', 'bar']), 'string[]'],
				'empty ArrayObject is iterable<string> or iterable<int>' => [new ArrayObject([]), 'string[]|int[]'],
				'ArrayObject<integer, string> is iterable<string> or iterable<int>' => [new ArrayObject(['foo', 'bar']), 'string[]|int[]'],
				'ArrayObject<integer, integer> is iterable<string> or iterable<int>' => [new ArrayObject([1, 2]), 'string[]|int[]'],
				'ArrayObject<ArrayObject<integer, integer>> is iterable<iterable<int>>' => [new ArrayObject([new ArrayObject([1, 2])]), 'int[][]'],
				'ArrayObject<ArrayObject<integer, integer>> with multiple ArrayObject<integer, integer> is iterable<iterable<int>>' => [new ArrayObject([new ArrayObject([1, 2]), new ArrayObject([3, 4])]), 'int[][]'],
				'ArrayObject<integer, ArrayObject<integer, integer>|ArrayObject<integer, string>> is not iterable<iterable<int>>' => [new ArrayObject([new ArrayObject([1, 2]), new ArrayObject(['foo'])]), 'int[][]', false],
				'ArrayObject<integer, ArrayObject<integer, string>> is not iterable<iterable<int>>' => [new ArrayObject([new ArrayObject(['foo'])]), 'int[][]', false],
				'ArrayObject<integer, integer> is not iterable<iterable<int>>' => [new ArrayObject([1, 2]), 'int[][]', false],
				'empty array is iterable<int, string>' => [[], 'int:string[]'],
				'string is not iterable<int, string>' => ['foo', 'int:string[]', false],
				'array<integer, string> is iterable<int, string>' => [['foo'], 'int:string[]'],
				'array<integer, integer> is not iterable<int, string>' => [[1], 'int:string[]', false],
				'array<integer, string> with multiple strings is iterable<int, string>' => [['foo', 'bar'], 'int:string[]'],
				'empty array is iterable<int, string> or iterable<int, int>' => [[], 'int:string[]|int:int[]'],
				'array<integer, string> is iterable<int, string> or iterable<int, int>' => [['foo', 'bar'], 'int:string[]|int:int[]'],
				'array<integer, integer> is iterable<int, string> or iterable<int, int>' => [[1, 2], 'int:string[]|int:int[]'],
				'array<integer, integer|string> is iterable<int, mixed>' => [[1, 'foo'], 'int:mixed[]'],
				'integer is not iterable<int, mixed>' => [1, 'int:mixed[]', false],
				'array<integer, array<integer, integer>> is iterable<int, iterable<int, int>>' => [[[1, 2]], 'int:int:int[][]'],
				'array<integer, array<integer, integer>> with multiple array<integer, integer> is iterable<int, iterable<int, int>>' => [[[1, 2], [3, 4]], 'int:int:int[][]'],
				'array<integer, array<integer, integer>|array<integer, string>> is not iterable<int, iterable<int, int>>' => [[[1, 2], ['foo']], 'int:int:int[][]', false],
				'empty ArrayObject is iterable<int, string>' => [new ArrayObject([]), 'int:string[]'],
				'ArrayObject<integer, string> is iterable<int, string>' => [new ArrayObject(['foo']), 'int:string[]'],
				'ArrayObject<integer, integer> is not iterable<int, string>' => [new ArrayObject([1]), 'int:string[]', false],
				'ArrayObject<integer, string> with multiple strings is iterable<int, string>' => [new ArrayObject(['foo', 'bar']), 'int:string[]'],
				'empty ArrayObject is iterable<int, string> or iterable<int, int>' => [new ArrayObject([]), 'int:string[]|int:int[]'],
				'ArrayObject<integer, string> is iterable<int, string> or iterable<int, int>' => [new ArrayObject(['foo', 'bar']), 'int:string[]|int:int[]'],
				'ArrayObject<integer, integer> is iterable<int, string> or iterable<int, int>' => [new ArrayObject([1, 2]), 'int:string[]|int:int[]'],
				'ArrayObject<integer, ArrayObject<integer, integer>> is iterable<int, iterable<int, int>>' => [new ArrayObject([new ArrayObject([1, 2])]), 'int:int:int[][]'],
				'ArrayObject<integer, ArrayObject<integer, integer>> with multiple ArrayObject<integer, integer> is iterable<int, iterable<int, int>>' => [new ArrayObject([new ArrayObject([1, 2]), new ArrayObject([3, 4])]), 'int:int:int[][]'],
				'ArrayObject<integer, ArrayObject<integer, integer>|ArrayObject<integer, string>> is not iterable<int, iterable<int, int>>' => [new ArrayObject([new ArrayObject([1, 2]), new ArrayObject(['foo'])]), 'int:int:int[][]', false],
				'ArrayObject<integer, ArrayObject<integer, string>> is not iterable<int, iterable<int, int>>' => [new ArrayObject([new ArrayObject(['foo'])]), 'int:int:int[][]', false],
				'ArrayObject<integer, integer> is not iterable<int, iterable<int, int>>' => [new ArrayObject([1, 2]), 'int:int:int[][]', false],
				'array<string, string> is iterable<string, string>' => [['foo' => 'bar'], 'string:string[]'],
				'array<integer, string> is not iterable<string, string>' => [['foo', 'bar'], 'string:string[]', false],
				'array<integer, string> is iterable<string, string> or iterable<int, string>' => [['foo', 'bar'], 'string:string[]|int:string[]'],
				'array<string, string> is iterable<string, string> or iterable<int, string>' => [['foo' => 'bar'], 'string:string[]|int:string[]'],
				'array<string, array<integer, string>> is iterable<string, iterable<int, string>>' => [['foo' => ['bar']], 'string:int:string[][]'],
				'array<string, array<integer, string>> is not iterable<string, iterable<string, string>>' => [['foo' => ['bar']], 'string:string:string[][]', false],
				'array<integer, array<string, string>> is iterable<int, iterable<string, string>>' => [[['foo' => 'bar']], 'int:string:string[][]'],
				'array<integer, array<string, string>> is not iterable<int, iterable<int, string>> ' => [[['foo' => 'bar']], 'int:int:string[][]', false],
				'array<string, array<integer, string>> is iterable<string, iterable<string>>' => [['foo' => ['bar']], 'string:string[][]'],
				'array<string, array<integer, string>> is not iterable<int, iterable<string>>' => [['foo' => ['bar']], 'int:string[][]', false],
				'array<string, array<integer, string>> is iterable<mixed, iterable<int, string>>' => [['foo' => ['bar']], 'mixed:int:string[][]'],
				'array<string, array<integer, string>> is not iterable<mixed, iterable<string, string>>' => [['foo' => ['bar']], 'mixed:string:string[][]', false],
			]
		);
	}

	/**
	 * @dataProvider typesDataProvider
	 *
	 * @param mixed $type
	 * @param string $expected
	 */
	public function testTypes($type, string $expected): void
	{
		Assert::assertSame($expected, Type::getType($type));
	}

	/**
	 * @dataProvider typeChecksDataProvider
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

	public function testCheckTypeException(): void
	{
		$this->expectException(\Consistence\InvalidArgumentTypeException::class);
		$this->expectExceptionMessage('[string] given');

		Type::checkType('foo', 'int');
	}

	/**
	 * @dataProvider typesDataProvider
	 *
	 * @param mixed $value
	 * @param string $valueType
	 */
	public function testCheckTypeExceptionValues($value, string $valueType): void
	{
		try {
			Type::checkType($value, 'Foo');
			Assert::fail();
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
