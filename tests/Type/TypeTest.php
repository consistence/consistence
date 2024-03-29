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
			['foo', 'string'],
			[1, 'int'],
			[true, 'bool'],
			[1.5, 'float'],
			[[], 'array'],
			[null, 'null'],
			[new DateTimeImmutable(), DateTimeImmutable::class],
			[static function (): void {
				return;
			}, Closure::class],
			[fopen(__DIR__, 'r'), 'resource'],
		];
	}

	/**
	 * @return mixed[][]
	 */
	public function typeChecksProvider(): array
	{
		return array_merge(
			$this->typesProvider(),
			[
				[null, 'NULL'],
				[1, 'string|int'],
				[1, 'string|integer'],
				['foo', 'string|int'],
				[2, 'null|int'],
				[2, 'null|integer'],
				[true, 'null|bool'],
				[false, 'bool|null'],
				[null, 'null|int'],
				[null, 'NULL|int'],
				[null, 'NULL|integer'],
				[DateTimeImmutable::class, 'DateTimeImmutable|string'],
				[new DateTimeImmutable(), 'DateTimeImmutable|string'],
				[new DateTimeImmutable(), 'object'],
				['foo', 'object', false],
				[1, 'mixed'],
				['foo', 'mixed'],
				[DateTimeImmutable::class, 'mixed'],
				[1, 'string|mixed'],
				[[], 'string[]'],
				['foo', 'string[]', false],
				[['foo'], 'string[]'],
				[[1], 'string[]', false],
				[['foo', 'bar'], 'string[]'],
				[[], 'string[]|int[]'],
				[['foo', 'bar'], 'string[]|int[]'],
				[2, 'string[]|int'],
				[[1, 2], 'string[]|int[]'],
				[[new DateTimeImmutable()], 'object[]'],
				[[new DateTimeImmutable(), new stdClass()], 'object[]'],
				[[new DateTimeImmutable(), 'foo'], 'object[]', false],
				[[1, 'foo'], 'mixed[]'],
				[1, 'mixed[]', false],
				[[1, 'foo'], 'mixed'],
				[[[1, 2]], 'int[][]'],
				[[[1, 2], [3, 4]], 'int[][]'],
				[[[1, 2], [3, 4]], 'integer[][]'],
				[[[1, 2], ['foo']], 'int[][]', false],
				[[['foo']], 'int[][]', false],
				[[1, 2], 'int[][]', false],
				[new ArrayObject([]), 'string[]'],
				[new ArrayObject(['foo']), 'string[]'],
				[new ArrayObject([1]), 'string[]', false],
				[new ArrayObject(['foo', 'bar']), 'string[]'],
				[new ArrayObject([]), 'string[]|int[]'],
				[new ArrayObject(['foo', 'bar']), 'string[]|int[]'],
				[new ArrayObject([1, 2]), 'string[]|int[]'],
				[new ArrayObject([new ArrayObject([1, 2])]), 'int[][]'],
				[new ArrayObject([new ArrayObject([1, 2]), new ArrayObject([3, 4])]), 'int[][]'],
				[new ArrayObject([new ArrayObject([1, 2]), new ArrayObject(['foo'])]), 'int[][]', false],
				[new ArrayObject([new ArrayObject(['foo'])]), 'int[][]', false],
				[new ArrayObject([1, 2]), 'int[][]', false],
				[new ArrayObject([]), 'string[]'],
				[new ArrayObject(['foo']), 'string[]'],
				[new ArrayObject([1]), 'string[]', false],
				[new ArrayObject(['foo', 'bar']), 'string[]'],
				[new ArrayObject([]), 'string[]|int[]'],
				[new ArrayObject(['foo', 'bar']), 'string[]|int[]'],
				[new ArrayObject([1, 2]), 'string[]|int[]'],
				[new ArrayObject([new ArrayObject([1, 2])]), 'int[][]'],
				[new ArrayObject([new ArrayObject([1, 2]), new ArrayObject([3, 4])]), 'int[][]'],
				[new ArrayObject([new ArrayObject([1, 2]), new ArrayObject(['foo'])]), 'int[][]', false],
				[new ArrayObject([new ArrayObject(['foo'])]), 'int[][]', false],
				[new ArrayObject([1, 2]), 'int[][]', false],
				[[], 'int:string[]'],
				['foo', 'int:string[]', false],
				[['foo'], 'int:string[]'],
				[[1], 'int:string[]', false],
				[['foo', 'bar'], 'int:string[]'],
				[[], 'int:string[]|int:int[]'],
				[['foo', 'bar'], 'int:string[]|int:int[]'],
				[[1, 2], 'int:string[]|int:int[]'],
				[[1, 'foo'], 'int:mixed[]'],
				[1, 'int:mixed[]', false],
				[[[1, 2]], 'int:int:int[][]'],
				[[[1, 2], [3, 4]], 'int:int:int[][]'],
				[[[1, 2], ['foo']], 'int:int:int[][]', false],
				[[['foo']], 'int[][]', false],
				[[1, 2], 'int[][]', false],
				[new ArrayObject([]), 'int:string[]'],
				[new ArrayObject(['foo']), 'int:string[]'],
				[new ArrayObject([1]), 'int:string[]', false],
				[new ArrayObject(['foo', 'bar']), 'int:string[]'],
				[new ArrayObject([]), 'int:string[]|int:int[]'],
				[new ArrayObject(['foo', 'bar']), 'int:string[]|int:int[]'],
				[new ArrayObject([1, 2]), 'int:string[]|int:int[]'],
				[new ArrayObject([new ArrayObject([1, 2])]), 'int:int:int[][]'],
				[new ArrayObject([new ArrayObject([1, 2]), new ArrayObject([3, 4])]), 'int:int:int[][]'],
				[new ArrayObject([new ArrayObject([1, 2]), new ArrayObject(['foo'])]), 'int:int:int[][]', false],
				[new ArrayObject([new ArrayObject(['foo'])]), 'int:int:int[][]', false],
				[new ArrayObject([1, 2]), 'int:int:int[][]', false],
				[['foo' => 'bar'], 'string:string[]'],
				[['foo', 'bar'], 'string:string[]', false],
				[['foo', 'bar'], 'string:string[]|int:string[]'],
				[['foo' => 'bar'], 'string:string[]|int:string[]'],
				[['foo' => ['bar']], 'string:int:string[][]'],
				[['foo' => ['bar']], 'string:string:string[][]', false],
				[[['foo' => 'bar']], 'int:string:string[][]'],
				[[['foo' => 'bar']], 'int:int:string[][]', false],
				[['foo' => ['bar']], 'string:string[][]'],
				[['foo' => ['bar']], 'int:string[][]', false],
				[['foo' => ['bar']], 'mixed:int:string[][]'],
				[['foo' => ['bar']], 'mixed:string:string[][]', false],
			]
		);
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
