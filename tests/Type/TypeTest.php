<?php

namespace Consistence\Type;

use DateTimeImmutable;

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

}
