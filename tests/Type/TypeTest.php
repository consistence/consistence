<?php

namespace Consistence\Type;

use ArrayObject;
use DateTimeImmutable;
use DateTimeInterface;

class TypeTest extends \Consistence\TestCase
{

	public function testStaticConstruct()
	{
		$this->expectException(\Consistence\StaticClassException::class);

		new Type();
	}

	/**
	 * @return mixed[][]
	 */
	public function typesProvider()
	{
		return [
			['foo', 'string'],
			[1, 'integer'],
			[true, 'boolean'],
			[1.5, 'float'],
			[[], 'array'],
			[null, 'null'],
			[new DateTimeImmutable(), DateTimeImmutable::class],
		];
	}

	/**
	 * @return mixed[][]
	 */
	public function typeChecksProvider()
	{
		return array_merge(
			$this->typesProvider(),
			[
				[null, 'NULL'],
				[1, 'string|integer'],
				['foo', 'string|integer'],
				[2, 'null|integer'],
				[null, 'null|integer'],
				[null, 'NULL|integer'],
				[DateTimeImmutable::class, 'DateTimeImmutable|string'],
				[new DateTimeImmutable(), 'DateTimeImmutable|string'],
				[[], 'string[]'],
				['foo', 'string[]', false],
				[['foo'], 'string[]'],
				[[1], 'string[]', false],
				[['foo', 'bar'], 'string[]'],
				[[], 'string[]|integer[]'],
				[['foo', 'bar'], 'string[]|integer[]'],
				[2, 'string[]|integer'],
				[[1, 2], 'string[]|integer[]'],
				[[[1, 2]], 'integer[][]'],
				[[[1, 2], [3, 4]], 'integer[][]'],
				[[[1, 2], ['foo']], 'integer[][]', false],
				[[['foo']], 'integer[][]', false],
				[[1, 2], 'integer[][]', false],
				[new ArrayObject([]), 'string[]'],
				[new ArrayObject(['foo']), 'string[]'],
				[new ArrayObject([1]), 'string[]', false],
				[new ArrayObject(['foo', 'bar']), 'string[]'],
				[new ArrayObject([]), 'string[]|integer[]'],
				[new ArrayObject(['foo', 'bar']), 'string[]|integer[]'],
				[new ArrayObject([1, 2]), 'string[]|integer[]'],
				[new ArrayObject([new ArrayObject([1, 2])]), 'integer[][]'],
				[new ArrayObject([new ArrayObject([1, 2]), new ArrayObject([3, 4])]), 'integer[][]'],
				[new ArrayObject([new ArrayObject([1, 2]), new ArrayObject(['foo'])]), 'integer[][]', false],
				[new ArrayObject([new ArrayObject(['foo'])]), 'integer[][]', false],
				[new ArrayObject([1, 2]), 'integer[][]', false],
				[new ArrayObject([]), 'string[]'],
				[new ArrayObject(['foo']), 'string[]'],
				[new ArrayObject([1]), 'string[]', false],
				[new ArrayObject(['foo', 'bar']), 'string[]'],
				[new ArrayObject([]), 'string[]|integer[]'],
				[new ArrayObject(['foo', 'bar']), 'string[]|integer[]'],
				[new ArrayObject([1, 2]), 'string[]|integer[]'],
				[new ArrayObject([new ArrayObject([1, 2])]), 'integer[][]'],
				[new ArrayObject([new ArrayObject([1, 2]), new ArrayObject([3, 4])]), 'integer[][]'],
				[new ArrayObject([new ArrayObject([1, 2]), new ArrayObject(['foo'])]), 'integer[][]', false],
				[new ArrayObject([new ArrayObject(['foo'])]), 'integer[][]', false],
				[new ArrayObject([1, 2]), 'integer[][]', false],
				[[], 'integer:string[]'],
				['foo', 'integer:string[]', false],
				[['foo'], 'integer:string[]'],
				[[1], 'integer:string[]', false],
				[['foo', 'bar'], 'integer:string[]'],
				[[], 'integer:string[]|integer:integer[]'],
				[['foo', 'bar'], 'integer:string[]|integer:integer[]'],
				[[1, 2], 'integer:string[]|integer:integer[]'],
				[[1, 'foo'], 'integer:mixed[]'],
				[1, 'integer:mixed[]', false],
				[[[1, 2]], 'integer:integer:integer[][]'],
				[[[1, 2], [3, 4]], 'integer:integer:integer[][]'],
				[[[1, 2], ['foo']], 'integer:integer:integer[][]', false],
				[[['foo']], 'integer[][]', false],
				[[1, 2], 'integer[][]', false],
				[new ArrayObject([]), 'integer:string[]'],
				[new ArrayObject(['foo']), 'integer:string[]'],
				[new ArrayObject([1]), 'integer:string[]', false],
				[new ArrayObject(['foo', 'bar']), 'integer:string[]'],
				[new ArrayObject([]), 'integer:string[]|integer:integer[]'],
				[new ArrayObject(['foo', 'bar']), 'integer:string[]|integer:integer[]'],
				[new ArrayObject([1, 2]), 'integer:string[]|integer:integer[]'],
				[new ArrayObject([new ArrayObject([1, 2])]), 'integer:integer:integer[][]'],
				[new ArrayObject([new ArrayObject([1, 2]), new ArrayObject([3, 4])]), 'integer:integer:integer[][]'],
				[new ArrayObject([new ArrayObject([1, 2]), new ArrayObject(['foo'])]), 'integer:integer:integer[][]', false],
				[new ArrayObject([new ArrayObject(['foo'])]), 'integer:integer:integer[][]', false],
				[new ArrayObject([1, 2]), 'integer:integer:integer[][]', false],
				[['foo' => 'bar'], 'string:string[]'],
				[['foo', 'bar'], 'string:string[]', false],
				[['foo', 'bar'], 'string:string[]|integer:string[]'],
				[['foo' => 'bar'], 'string:string[]|integer:string[]'],
				[['foo' => ['bar']], 'string:integer:string[][]'],
				[['foo' => ['bar']], 'string:string:string[][]', false],
				[[['foo' => 'bar']], 'integer:string:string[][]'],
				[[['foo' => 'bar']], 'integer:integer:string[][]', false],
				[['foo' => ['bar']], 'string:string[][]'],
				[['foo' => ['bar']], 'integer:string[][]', false],
				[['foo' => ['bar']], 'mixed:integer:string[][]'],
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
	public function testTypes($type, $expected)
	{
		$this->assertSame($expected, Type::getType($type));
	}

	/**
	 * @dataProvider typeChecksProvider
	 *
	 * @param mixed $value
	 * @param string $expectedTypes
	 * @param boolean $result
	 */
	public function testHasType($value, $expectedTypes, $result = true)
	{
		$this->assertSame($result, Type::hasType($value, $expectedTypes));
	}

	public function testCheckTypeOk()
	{
		Type::checkType('foo', 'string');
		$this->ok();
	}

	public function testCheckTypeException()
	{
		$this->expectException(\Consistence\InvalidArgumentTypeException::class);
		$this->expectExceptionMessage('[string] given');

		Type::checkType('foo', 'integer');
	}

	/**
	 * @dataProvider typesProvider
	 *
	 * @param mixed $value
	 * @param string $valueType
	 */
	public function testCheckTypeExceptionValues($value, $valueType)
	{
		try {
			Type::checkType($value, 'resource');
			$this->fail();
		} catch (\Consistence\InvalidArgumentTypeException $e) {
			$this->assertSame($value, $e->getValue());
			$this->assertSame($valueType, $e->getValueType());
			$this->assertSame('resource', $e->getExpectedTypes());
		}
	}

	public function testAllowSubtypes()
	{
		$this->assertTrue(Type::hasType(new DateTimeImmutable(), DateTimeInterface::class));
	}

	public function testDisallowSubtypes()
	{
		$this->assertFalse(Type::hasType(new DateTimeImmutable(), DateTimeInterface::class, Type::SUBTYPES_DISALLOW));
	}

}
