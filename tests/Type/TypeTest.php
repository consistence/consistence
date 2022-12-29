<?php

declare(strict_types = 1);

namespace Consistence\Type;

use ArrayObject;
use Closure;
use DateTimeImmutable;
use DateTimeInterface;
use stdClass;

class TypeTest extends \Consistence\TestCase
{

	public function testStaticConstruct(): void
	{
		$this->expectException(\Consistence\StaticClassException::class);

		new Type();
	}

	/**
	 * @return mixed[][]
	 */
	public function typesProvider(): array
	{
		return [
			[
				'value' => 'foo',
				'expectedType' => 'string',
			],
			[
				'value' => 1,
				'expectedType' => 'int',
			],
			[
				'value' => true,
				'expectedType' => 'bool',
			],
			[
				'value' => 1.5,
				'expectedType' => 'float',
			],
			[
				'value' => [],
				'expectedType' => 'array',
			],
			[
				'value' => null,
				'expectedType' => 'null',
			],
			[
				'value' => new DateTimeImmutable(),
				'expectedType' => DateTimeImmutable::class,
			],
			[
				'value' => static function (): void {
					return;
				},
				'expectedType' => Closure::class,
			],
			[
				'value' => fopen(__DIR__, 'r'),
				'expectedType' => 'resource',
			],
		];
	}

	/**
	 * @return mixed[][]
	 */
	public function typeChecksProvider(): array
	{
		return [
			[
				'value' => null,
				'expectedType' => 'NULL',
			],
			[
				'value' => 1,
				'expectedType' => 'string|int',
			],
			[
				'value' => 1,
				'expectedType' => 'string|integer',
			],
			[
				'value' => 'foo',
				'expectedType' => 'string|int',
			],
			[
				'value' => 2,
				'expectedType' => 'null|int',
			],
			[
				'value' => 2,
				'expectedType' => 'null|integer',
			],
			[
				'value' => true,
				'expectedType' => 'null|bool',
			],
			[
				'value' => false,
				'expectedType' => 'bool|null',
			],
			[
				'value' => null,
				'expectedType' => 'null|int',
			],
			[
				'value' => null,
				'expectedType' => 'NULL|int',
			],
			[
				'value' => null,
				'expectedType' => 'NULL|integer',
			],
			[
				'value' => DateTimeImmutable::class,
				'expectedType' => 'DateTimeImmutable|string',
			],
			[
				'value' => new DateTimeImmutable(),
				'expectedType' => 'DateTimeImmutable|string',
			],
			[
				'value' => new DateTimeImmutable(),
				'expectedType' => 'object',
			],
			[
				'value' => 'foo',
				'expectedType' => 'object',
				'isTheSameType' => false,
			],
			[
				'value' => 1,
				'expectedType' => 'mixed',
			],
			[
				'value' => 'foo',
				'expectedType' => 'mixed',
			],
			[
				'value' => DateTimeImmutable::class,
				'expectedType' => 'mixed',
			],
			[
				'value' => 1,
				'expectedType' => 'string|mixed',
			],
			[
				'value' => [],
				'expectedType' => 'string[]',
			],
			[
				'value' => 'foo',
				'expectedType' => 'string[]',
				'isTheSameType' => false,
			],
			[
				'value' => ['foo'],
				'expectedType' => 'string[]',
			],
			[
				'value' => [1],
				'expectedType' => 'string[]',
				'isTheSameType' => false,
			],
			[
				'value' => ['foo', 'bar'],
				'expectedType' => 'string[]',
			],
			[
				'value' => [],
				'expectedType' => 'string[]|int[]',
			],
			[
				'value' => ['foo', 'bar'],
				'expectedType' => 'string[]|int[]',
			],
			[
				'value' => 2,
				'expectedType' => 'string[]|int',
			],
			[
				'value' => [1, 2],
				'expectedType' => 'string[]|int[]',
			],
			[
				'value' => [new DateTimeImmutable()],
				'expectedType' => 'object[]',
			],
			[
				'value' => [new DateTimeImmutable(), new stdClass()],
				'expectedType' => 'object[]',
			],
			[
				'value' => [new DateTimeImmutable(), 'foo'],
				'expectedType' => 'object[]',
				'isTheSameType' => false,
			],
			[
				'value' => [1, 'foo'],
				'expectedType' => 'mixed[]',
			],
			[
				'value' => 1,
				'expectedType' => 'mixed[]',
				'isTheSameType' => false,
			],
			[
				'value' => [1, 'foo'],
				'expectedType' => 'mixed',
			],
			[
				'value' => [[1, 2]],
				'expectedType' => 'int[][]',
			],
			[
				'value' => [[1, 2], [3, 4]],
				'expectedType' => 'int[][]',
			],
			[
				'value' => [[1, 2], [3, 4]],
				'expectedType' => 'integer[][]',
			],
			[
				'value' => [[1, 2], ['foo']],
				'expectedType' => 'int[][]',
				'isTheSameType' => false,
			],
			[
				'value' => [['foo']],
				'expectedType' => 'int[][]',
				'isTheSameType' => false,
			],
			[
				'value' => [1, 2],
				'expectedType' => 'int[][]',
				'isTheSameType' => false,
			],
			[
				'value' => new ArrayObject([]),
				'expectedType' => 'string[]',
			],
			[
				'value' => new ArrayObject(['foo']),
				'expectedType' => 'string[]',
			],
			[
				'value' => new ArrayObject([1]),
				'expectedType' => 'string[]',
				'isTheSameType' => false,
			],
			[
				'value' => new ArrayObject(['foo', 'bar']),
				'expectedType' => 'string[]',
			],
			[
				'value' => new ArrayObject([]),
				'expectedType' => 'string[]|int[]',
			],
			[
				'value' => new ArrayObject(['foo', 'bar']),
				'expectedType' => 'string[]|int[]',
			],
			[
				'value' => new ArrayObject([1, 2]),
				'expectedType' => 'string[]|int[]',
			],
			[
				'value' => new ArrayObject([new ArrayObject([1, 2])]),
				'expectedType' => 'int[][]',
			],
			[
				'value' => new ArrayObject([new ArrayObject([1, 2]), new ArrayObject([3, 4])]),
				'expectedType' => 'int[][]',
			],
			[
				'value' => new ArrayObject([new ArrayObject([1, 2]), new ArrayObject(['foo'])]),
				'expectedType' => 'int[][]',
				'isTheSameType' => false,
			],
			[
				'value' => new ArrayObject([new ArrayObject(['foo'])]),
				'expectedType' => 'int[][]',
				'isTheSameType' => false,
			],
			[
				'value' => new ArrayObject([1, 2]),
				'expectedType' => 'int[][]',
				'isTheSameType' => false,
			],
			[
				'value' => new ArrayObject([]),
				'expectedType' => 'string[]',
			],
			[
				'value' => new ArrayObject(['foo']),
				'expectedType' => 'string[]',
			],
			[
				'value' => new ArrayObject([1]),
				'expectedType' => 'string[]',
				'isTheSameType' => false,
			],
			[
				'value' => new ArrayObject(['foo', 'bar']),
				'expectedType' => 'string[]',
			],
			[
				'value' => new ArrayObject([]),
				'expectedType' => 'string[]|int[]',
			],
			[
				'value' => new ArrayObject(['foo', 'bar']),
				'expectedType' => 'string[]|int[]',
			],
			[
				'value' => new ArrayObject([1, 2]),
				'expectedType' => 'string[]|int[]',
			],
			[
				'value' => new ArrayObject([new ArrayObject([1, 2])]),
				'expectedType' => 'int[][]',
			],
			[
				'value' => new ArrayObject([new ArrayObject([1, 2]), new ArrayObject([3, 4])]),
				'expectedType' => 'int[][]',
			],
			[
				'value' => new ArrayObject([new ArrayObject([1, 2]), new ArrayObject(['foo'])]),
				'expectedType' => 'int[][]',
				'isTheSameType' => false,
			],
			[
				'value' => new ArrayObject([new ArrayObject(['foo'])]),
				'expectedType' => 'int[][]',
				'isTheSameType' => false,
			],
			[
				'value' => new ArrayObject([1, 2]),
				'expectedType' => 'int[][]',
				'isTheSameType' => false,
			],
			[
				'value' => [],
				'expectedType' => 'int:string[]',
			],
			[
				'value' => 'foo',
				'expectedType' => 'int:string[]',
				'isTheSameType' => false,
			],
			[
				'value' => ['foo'],
				'expectedType' => 'int:string[]',
			],
			[
				'value' => [1],
				'expectedType' => 'int:string[]',
				'isTheSameType' =>false,
			],
			[
				'value' => ['foo', 'bar'],
				'expectedType' => 'int:string[]',
			],
			[
				'value' => [],
				'expectedType' => 'int:string[]|int:int[]',
			],
			[
				'value' => ['foo', 'bar'],
				'expectedType' => 'int:string[]|int:int[]',
			],
			[
				'value' => [1, 2],
				'expectedType' => 'int:string[]|int:int[]',
			],
			[
				'value' => [1, 'foo'],
				'expectedType' => 'int:mixed[]',
			],
			[
				'value' => 1,
				'expectedType' => 'int:mixed[]',
				'isTheSameType' => false,
			],
			[
				'value' => [[1, 2]],
				'expectedType' => 'int:int:int[][]',
			],
			[
				'value' => [[1, 2], [3, 4]],
				'expectedType' => 'int:int:int[][]',
			],
			[
				'value' => [[1, 2], ['foo']],
				'expectedType' => 'int:int:int[][]',
				'isTheSameType' => false,
			],
			[
				'value' => [['foo']],
				'expectedType' => 'int[][]',
				'isTheSameType' => false,
			],
			[
				'value' => [1, 2],
				'expectedType' => 'int[][]',
				'isTheSameType' => false,
			],
			[
				'value' => new ArrayObject([]),
				'expectedType' => 'int:string[]',
			],
			[
				'value' => new ArrayObject(['foo']),
				'expectedType' => 'int:string[]',
			],
			[
				'value' => new ArrayObject([1]),
				'expectedType' => 'int:string[]',
				'isTheSameType' => false,
			],
			[
				'value' => new ArrayObject(['foo', 'bar']),
				'expectedType' => 'int:string[]',
			],
			[
				'value' => new ArrayObject([]),
				'expectedType' => 'int:string[]|int:int[]',
			],
			[
				'value' => new ArrayObject(['foo', 'bar']),
				'expectedType' => 'int:string[]|int:int[]',
			],
			[
				'value' => new ArrayObject([1, 2]),
				'expectedType' => 'int:string[]|int:int[]',
			],
			[
				'value' => new ArrayObject([new ArrayObject([1, 2])]),
				'expectedType' => 'int:int:int[][]',
			],
			[
				'value' => new ArrayObject([new ArrayObject([1, 2]), new ArrayObject([3, 4])]),
				'expectedType' => 'int:int:int[][]',
			],
			[
				'value' => new ArrayObject([new ArrayObject([1, 2]), new ArrayObject(['foo'])]),
				'expectedType' => 'int:int:int[][]',
				'isTheSameType' => false,
			],
			[
				'value' => new ArrayObject([new ArrayObject(['foo'])]),
				'expectedType' => 'int:int:int[][]',
				'isTheSameType' => false,
			],
			[
				'value' => new ArrayObject([1, 2]),
				'expectedType' => 'int:int:int[][]',
				'isTheSameType' => false,
			],
			[
				'value' => ['foo' => 'bar'],
				'expectedType' => 'string:string[]',
			],
			[
				'value' => ['foo', 'bar'],
				'expectedType' => 'string:string[]',
				'isTheSameType' => false,
			],
			[
				'value' => ['foo', 'bar'],
				'expectedType' => 'string:string[]|int:string[]',
			],
			[
				'value' => ['foo' => 'bar'],
				'expectedType' => 'string:string[]|int:string[]',
			],
			[
				'value' => ['foo' => ['bar']],
				'expectedType' => 'string:int:string[][]',
			],
			[
				'value' => ['foo' => ['bar']],
				'expectedType' => 'string:string:string[][]',
				'isTheSameType' => false,
			],
			[
				'value' => [['foo' => 'bar']],
				'expectedType' => 'int:string:string[][]',
			],
			[
				'value' => [['foo' => 'bar']],
				'expectedType' => 'int:int:string[][]',
				'isTheSameType' => false,
			],
			[
				'value' => ['foo' => ['bar']],
				'expectedType' => 'string:string[][]',
			],
			[
				'value' => ['foo' => ['bar']],
				'expectedType' => 'int:string[][]',
				'isTheSameType' => false,
			],
			[
				'value' => ['foo' => ['bar']],
				'expectedType' => 'mixed:int:string[][]',
			],
			[
				'value' => ['foo' => ['bar']],
				'expectedType' => 'mixed:string:string[][]',
				'isTheSameType' => false,
			],
		];
	}

	/**
	 * @dataProvider typesProvider
	 *
	 * @param mixed $type
	 * @param string $expected
	 */
	public function testTypes($type, string $expected): void
	{
		$this->assertSame($expected, Type::getType($type));
	}

	/**
	 * @dataProvider typesProvider
	 * @dataProvider typeChecksProvider
	 *
	 * @param mixed $value
	 * @param string $expectedTypes
	 * @param bool $result
	 */
	public function testHasType($value, string $expectedTypes, bool $result = true): void
	{
		$this->assertSame($result, Type::hasType($value, $expectedTypes));
	}

	public function testCheckTypeOk(): void
	{
		Type::checkType('foo', 'string');
		$this->ok();
	}

	public function testCheckTypeException(): void
	{
		$this->expectException(\Consistence\InvalidArgumentTypeException::class);
		$this->expectExceptionMessage('[string] given');

		Type::checkType('foo', 'int');
	}

	/**
	 * @dataProvider typesProvider
	 *
	 * @param mixed $value
	 * @param string $valueType
	 */
	public function testCheckTypeExceptionValues($value, string $valueType): void
	{
		try {
			Type::checkType($value, 'Foo');
			$this->fail();
		} catch (\Consistence\InvalidArgumentTypeException $e) {
			$this->assertSame($value, $e->getValue());
			$this->assertSame($valueType, $e->getValueType());
			$this->assertSame('Foo', $e->getExpectedTypes());
		}
	}

	public function testAllowSubtypes(): void
	{
		$this->assertTrue(Type::hasType(new DateTimeImmutable(), DateTimeInterface::class));
	}

	public function testDisallowSubtypes(): void
	{
		$this->assertFalse(Type::hasType(new DateTimeImmutable(), DateTimeInterface::class, Type::SUBTYPES_DISALLOW));
	}

}
