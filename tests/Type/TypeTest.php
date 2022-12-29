<?php

declare(strict_types = 1);

namespace Consistence\Type;

use ArrayObject;
use Closure;
use DateTimeImmutable;
use DateTimeInterface;
use Generator;
use stdClass;

class TypeTest extends \Consistence\TestCase
{

	public function testStaticConstruct(): void
	{
		$this->expectException(\Consistence\StaticClassException::class);

		new Type();
	}

	/**
	 * @return mixed[][]|\Generator
	 */
	public function typesDataProvider(): Generator
	{
		yield 'DP #1' => [
			'value' => 'foo',
			'expectedType' => 'string',
		];
		yield 'DP #2' => [
			'value' => 1,
			'expectedType' => 'int',
		];
		yield 'DP #3' => [
			'value' => true,
			'expectedType' => 'bool',
		];
		yield 'DP #4' => [
			'value' => 1.5,
			'expectedType' => 'float',
		];
		yield 'DP #5' => [
			'value' => [],
			'expectedType' => 'array',
		];
		yield 'DP #6' => [
			'value' => null,
			'expectedType' => 'null',
		];
		yield 'DP #7' => [
			'value' => new DateTimeImmutable(),
			'expectedType' => DateTimeImmutable::class,
		];
		yield 'DP #8' => [
			'value' => static function (): void {
				return;
			},
			'expectedType' => Closure::class,
		];
		yield 'DP #9' => [
			'value' => fopen(__DIR__, 'r'),
			'expectedType' => 'resource',
		];
	}

	/**
	 * @return mixed[][]|\Generator
	 */
	public function hasTypesCorrectDataProvider(): Generator
	{
		yield 'DP #1' => [
			'value' => null,
			'expectedType' => 'NULL',
		];
		yield 'DP #2' => [
			'value' => 1,
			'expectedType' => 'string|int',
		];
		yield 'DP #3' => [
			'value' => 1,
			'expectedType' => 'string|integer',
		];
		yield 'DP #4' => [
			'value' => 'foo',
			'expectedType' => 'string|int',
		];
		yield 'DP #5' => [
			'value' => 2,
			'expectedType' => 'null|int',
		];
		yield 'DP #6' => [
			'value' => 2,
			'expectedType' => 'null|integer',
		];
		yield 'DP #7' => [
			'value' => true,
			'expectedType' => 'null|bool',
		];
		yield 'DP #8' => [
			'value' => false,
			'expectedType' => 'bool|null',
		];
		yield 'DP #9' => [
			'value' => null,
			'expectedType' => 'null|int',
		];
		yield 'DP #10' => [
			'value' => null,
			'expectedType' => 'NULL|int',
		];
		yield 'DP #11' => [
			'value' => null,
			'expectedType' => 'NULL|integer',
		];
		yield 'DP #12' => [
			'value' => DateTimeImmutable::class,
			'expectedType' => 'DateTimeImmutable|string',
		];
		yield 'DP #13' => [
			'value' => new DateTimeImmutable(),
			'expectedType' => 'DateTimeImmutable|string',
		];
		yield 'DP #14' => [
			'value' => new DateTimeImmutable(),
			'expectedType' => 'object',
		];
		yield 'DP #16' => [
			'value' => 1,
			'expectedType' => 'mixed',
		];
		yield 'DP #17' => [
			'value' => 'foo',
			'expectedType' => 'mixed',
		];
		yield 'DP #18' => [
			'value' => DateTimeImmutable::class,
			'expectedType' => 'mixed',
		];
		yield 'DP #19' => [
			'value' => 1,
			'expectedType' => 'string|mixed',
		];
		yield 'DP #20' => [
			'value' => [],
			'expectedType' => 'string[]',
		];
		yield 'DP #22' => [
			'value' => ['foo'],
			'expectedType' => 'string[]',
		];
		yield 'DP #24' => [
			'value' => ['foo', 'bar'],
			'expectedType' => 'string[]',
		];
		yield 'DP #25' => [
			'value' => [],
			'expectedType' => 'string[]|int[]',
		];
		yield 'DP #26' => [
			'value' => ['foo', 'bar'],
			'expectedType' => 'string[]|int[]',
		];
		yield 'DP #27' => [
			'value' => 2,
			'expectedType' => 'string[]|int',
		];
		yield 'DP #28' => [
			'value' => [1, 2],
			'expectedType' => 'string[]|int[]',
		];
		yield 'DP #29' => [
			'value' => [new DateTimeImmutable()],
			'expectedType' => 'object[]',
		];
		yield 'DP #30' => [
			'value' => [new DateTimeImmutable(), new stdClass()],
			'expectedType' => 'object[]',
		];
		yield 'DP #32' => [
			'value' => [1, 'foo'],
			'expectedType' => 'mixed[]',
		];
		yield 'DP #34' => [
			'value' => [1, 'foo'],
			'expectedType' => 'mixed',
		];
		yield 'DP #35' => [
			'value' => [[1, 2]],
			'expectedType' => 'int[][]',
		];
		yield 'DP #36' => [
			'value' => [[1, 2], [3, 4]],
			'expectedType' => 'int[][]',
		];
		yield 'DP #37' => [
			'value' => [[1, 2], [3, 4]],
			'expectedType' => 'integer[][]',
		];
		yield 'DP #41' => [
			'value' => new ArrayObject([]),
			'expectedType' => 'string[]',
		];
		yield 'DP #42' => [
			'value' => new ArrayObject(['foo']),
			'expectedType' => 'string[]',
		];
		yield 'DP #44' => [
			'value' => new ArrayObject(['foo', 'bar']),
			'expectedType' => 'string[]',
		];
		yield 'DP #45' => [
			'value' => new ArrayObject([]),
			'expectedType' => 'string[]|int[]',
		];
		yield 'DP #46' => [
			'value' => new ArrayObject(['foo', 'bar']),
			'expectedType' => 'string[]|int[]',
		];
		yield 'DP #47' => [
			'value' => new ArrayObject([1, 2]),
			'expectedType' => 'string[]|int[]',
		];
		yield 'DP #48' => [
			'value' => new ArrayObject([new ArrayObject([1, 2])]),
			'expectedType' => 'int[][]',
		];
		yield 'DP #49' => [
			'value' => new ArrayObject([new ArrayObject([1, 2]), new ArrayObject([3, 4])]),
			'expectedType' => 'int[][]',
		];
		yield 'DP #53' => [
			'value' => new ArrayObject([]),
			'expectedType' => 'string[]',
		];
		yield 'DP #54' => [
			'value' => new ArrayObject(['foo']),
			'expectedType' => 'string[]',
		];
		yield 'DP #56' => [
			'value' => new ArrayObject(['foo', 'bar']),
			'expectedType' => 'string[]',
		];
		yield 'DP #57' => [
			'value' => new ArrayObject([]),
			'expectedType' => 'string[]|int[]',
		];
		yield 'DP #58' => [
			'value' => new ArrayObject(['foo', 'bar']),
			'expectedType' => 'string[]|int[]',
		];
		yield 'DP #59' => [
			'value' => new ArrayObject([1, 2]),
			'expectedType' => 'string[]|int[]',
		];
		yield 'DP #60' => [
			'value' => new ArrayObject([new ArrayObject([1, 2])]),
			'expectedType' => 'int[][]',
		];
		yield 'DP #61' => [
			'value' => new ArrayObject([new ArrayObject([1, 2]), new ArrayObject([3, 4])]),
			'expectedType' => 'int[][]',
		];
		yield 'DP #65' => [
			'value' => [],
			'expectedType' => 'int:string[]',
		];
		yield 'DP #67' => [
			'value' => ['foo'],
			'expectedType' => 'int:string[]',
		];
		yield 'DP #69' => [
			'value' => ['foo', 'bar'],
			'expectedType' => 'int:string[]',
		];
		yield 'DP #70' => [
			'value' => [],
			'expectedType' => 'int:string[]|int:int[]',
		];
		yield 'DP #71' => [
			'value' => ['foo', 'bar'],
			'expectedType' => 'int:string[]|int:int[]',
		];
		yield 'DP #72' => [
			'value' => [1, 2],
			'expectedType' => 'int:string[]|int:int[]',
		];
		yield 'DP #73' => [
			'value' => [1, 'foo'],
			'expectedType' => 'int:mixed[]',
		];
		yield 'DP #75' => [
			'value' => [[1, 2]],
			'expectedType' => 'int:int:int[][]',
		];
		yield 'DP #76' => [
			'value' => [[1, 2], [3, 4]],
			'expectedType' => 'int:int:int[][]',
		];
		yield 'DP #80' => [
			'value' => new ArrayObject([]),
			'expectedType' => 'int:string[]',
		];
		yield 'DP #81' => [
			'value' => new ArrayObject(['foo']),
			'expectedType' => 'int:string[]',
		];
		yield 'DP #83' => [
			'value' => new ArrayObject(['foo', 'bar']),
			'expectedType' => 'int:string[]',
		];
		yield 'DP #84' => [
			'value' => new ArrayObject([]),
			'expectedType' => 'int:string[]|int:int[]',
		];
		yield 'DP #85' => [
			'value' => new ArrayObject(['foo', 'bar']),
			'expectedType' => 'int:string[]|int:int[]',
		];
		yield 'DP #86' => [
			'value' => new ArrayObject([1, 2]),
			'expectedType' => 'int:string[]|int:int[]',
		];
		yield 'DP #87' => [
			'value' => new ArrayObject([new ArrayObject([1, 2])]),
			'expectedType' => 'int:int:int[][]',
		];
		yield 'DP #88' => [
			'value' => new ArrayObject([new ArrayObject([1, 2]), new ArrayObject([3, 4])]),
			'expectedType' => 'int:int:int[][]',
		];
		yield 'DP #92' => [
			'value' => ['foo' => 'bar'],
			'expectedType' => 'string:string[]',
		];
		yield 'DP #94' => [
			'value' => ['foo', 'bar'],
			'expectedType' => 'string:string[]|int:string[]',
		];
		yield 'DP #95' => [
			'value' => ['foo' => 'bar'],
			'expectedType' => 'string:string[]|int:string[]',
		];
		yield 'DP #96' => [
			'value' => ['foo' => ['bar']],
			'expectedType' => 'string:int:string[][]',
		];
		yield 'DP #98' => [
			'value' => [['foo' => 'bar']],
			'expectedType' => 'int:string:string[][]',
		];
		yield 'DP #100' => [
			'value' => ['foo' => ['bar']],
			'expectedType' => 'string:string[][]',
		];
		yield 'DP #102' => [
			'value' => ['foo' => ['bar']],
			'expectedType' => 'mixed:int:string[][]',
		];
	}

	/**
	 * @return mixed[][]|\Generator
	 */
	public function hasTypesIncorrectDataProvider(): Generator
	{
		yield 'DP #15' => [
			'value' => 'foo',
			'expectedType' => 'object',
		];
		yield 'DP #21' => [
			'value' => 'foo',
			'expectedType' => 'string[]',
		];
		yield 'DP #23' => [
			'value' => [1],
			'expectedType' => 'string[]',
		];
		yield 'DP #31' => [
			'value' => [new DateTimeImmutable(), 'foo'],
			'expectedType' => 'object[]',
		];
		yield 'DP #33' => [
			'value' => 1,
			'expectedType' => 'mixed[]',
		];
		yield 'DP #38' => [
			'value' => [[1, 2], ['foo']],
			'expectedType' => 'int[][]',
		];
		yield 'DP #39' => [
			'value' => [['foo']],
			'expectedType' => 'int[][]',
		];
		yield 'DP #40' => [
			'value' => [1, 2],
			'expectedType' => 'int[][]',
		];
		yield 'DP #43' => [
			'value' => new ArrayObject([1]),
			'expectedType' => 'string[]',
		];
		yield 'DP #50' => [
			'value' => new ArrayObject([new ArrayObject([1, 2]), new ArrayObject(['foo'])]),
			'expectedType' => 'int[][]',
		];
		yield 'DP #51' => [
			'value' => new ArrayObject([new ArrayObject(['foo'])]),
			'expectedType' => 'int[][]',
		];
		yield 'DP #52' => [
			'value' => new ArrayObject([1, 2]),
			'expectedType' => 'int[][]',
		];
		yield 'DP #55' => [
			'value' => new ArrayObject([1]),
			'expectedType' => 'string[]',
		];
		yield 'DP #62' => [
			'value' => new ArrayObject([new ArrayObject([1, 2]), new ArrayObject(['foo'])]),
			'expectedType' => 'int[][]',
		];
		yield 'DP #63' => [
			'value' => new ArrayObject([new ArrayObject(['foo'])]),
			'expectedType' => 'int[][]',
		];
		yield 'DP #64' => [
			'value' => new ArrayObject([1, 2]),
			'expectedType' => 'int[][]',
		];
		yield 'DP #66' => [
			'value' => 'foo',
			'expectedType' => 'int:string[]',
		];
		yield 'DP #68' => [
			'value' => [1],
			'expectedType' => 'int:string[]',
		];
		yield 'DP #74' => [
			'value' => 1,
			'expectedType' => 'int:mixed[]',
		];
		yield 'DP #77' => [
			'value' => [[1, 2], ['foo']],
			'expectedType' => 'int:int:int[][]',
		];
		yield 'DP #78' => [
			'value' => [['foo']],
			'expectedType' => 'int[][]',
		];
		yield 'DP #79' => [
			'value' => [1, 2],
			'expectedType' => 'int[][]',
		];
		yield 'DP #82' => [
			'value' => new ArrayObject([1]),
			'expectedType' => 'int:string[]',
		];
		yield 'DP #89' => [
			'value' => new ArrayObject([new ArrayObject([1, 2]), new ArrayObject(['foo'])]),
			'expectedType' => 'int:int:int[][]',
		];
		yield 'DP #90' => [
			'value' => new ArrayObject([new ArrayObject(['foo'])]),
			'expectedType' => 'int:int:int[][]',
		];
		yield 'DP #91' => [
			'value' => new ArrayObject([1, 2]),
			'expectedType' => 'int:int:int[][]',
		];
		yield 'DP #93' => [
			'value' => ['foo', 'bar'],
			'expectedType' => 'string:string[]',
		];
		yield 'DP #97' => [
			'value' => ['foo' => ['bar']],
			'expectedType' => 'string:string:string[][]',
		];
		yield 'DP #99' => [
			'value' => [['foo' => 'bar']],
			'expectedType' => 'int:int:string[][]',
		];
		yield 'DP #101' => [
			'value' => ['foo' => ['bar']],
			'expectedType' => 'int:string[][]',
		];
		yield 'DP #103' => [
			'value' => ['foo' => ['bar']],
			'expectedType' => 'mixed:string:string[][]',
		];
	}

	/**
	 * @dataProvider typesDataProvider
	 *
	 * @param mixed $value
	 * @param string $expectedType
	 */
	public function testTypes($value, string $expectedType): void
	{
		$this->assertSame($expectedType, Type::getType($value));
	}

	/**
	 * @dataProvider typesDataProvider
	 * @dataProvider hasTypesCorrectDataProvider
	 *
	 * @param mixed $value
	 * @param string $expectedType
	 */
	public function testHasType($value, string $expectedType): void
	{
		$this->assertTrue(Type::hasType($value, $expectedType));
	}

	/**
	 * @dataProvider hasTypesIncorrectDataProvider
	 *
	 * @param mixed $value
	 * @param string $type
	 */
	public function testHasTypeIncorrect($value, string $type): void
	{
		$this->assertFalse(Type::hasType($value, $type));
	}

	/**
	 * @dataProvider typesDataProvider
	 *
	 * @param mixed $value
	 * @param string $expectedType
	 */
	public function testCheckTypeOk($value, string $expectedType): void
	{
		$this->expectNotToPerformAssertions();

		Type::checkType($value, $expectedType);
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
	 * @param string $expectedType
	 */
	public function testCheckTypeExceptionValues($value, string $expectedType): void
	{
		try {
			Type::checkType($value, 'Foo');
			$this->fail();
		} catch (\Consistence\InvalidArgumentTypeException $e) {
			$this->assertSame($value, $e->getValue());
			$this->assertSame($expectedType, $e->getValueType());
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
