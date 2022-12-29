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
			'DP #1' => [
				'value' => 'foo',
				'expectedType' => 'string',
			],
			'DP #2' => [
				'value' => 1,
				'expectedType' => 'int',
			],
			'DP #3' => [
				'value' => true,
				'expectedType' => 'bool',
			],
			'DP #4' => [
				'value' => 1.5,
				'expectedType' => 'float',
			],
			'DP #5' => [
				'value' => [],
				'expectedType' => 'array',
			],
			'DP #6' => [
				'value' => null,
				'expectedType' => 'null',
			],
			'DP #7' => [
				'value' => new DateTimeImmutable(),
				'expectedType' => DateTimeImmutable::class,
			],
			'DP #8' => [
				'value' => static function (): void {
					return;
				},
				'expectedType' => Closure::class,
			],
			'DP #9' => [
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
			'DP #1' => [
				'value' => null,
				'expectedType' => 'NULL',
			],
			'DP #2' => [
				'value' => 1,
				'expectedType' => 'string|int',
			],
			'DP #3' => [
				'value' => 1,
				'expectedType' => 'string|integer',
			],
			'DP #4' => [
				'value' => 'foo',
				'expectedType' => 'string|int',
			],
			'DP #5' => [
				'value' => 2,
				'expectedType' => 'null|int',
			],
			'DP #6' => [
				'value' => 2,
				'expectedType' => 'null|integer',
			],
			'DP #7' => [
				'value' => true,
				'expectedType' => 'null|bool',
			],
			'DP #8' => [
				'value' => false,
				'expectedType' => 'bool|null',
			],
			'DP #9' => [
				'value' => null,
				'expectedType' => 'null|int',
			],
			'DP #10' => [
				'value' => null,
				'expectedType' => 'NULL|int',
			],
			'DP #11' => [
				'value' => null,
				'expectedType' => 'NULL|integer',
			],
			'DP #12' => [
				'value' => DateTimeImmutable::class,
				'expectedType' => 'DateTimeImmutable|string',
			],
			'DP #13' => [
				'value' => new DateTimeImmutable(),
				'expectedType' => 'DateTimeImmutable|string',
			],
			'DP #14' => [
				'value' => new DateTimeImmutable(),
				'expectedType' => 'object',
			],
			'DP #16' => [
				'value' => 1,
				'expectedType' => 'mixed',
			],
			'DP #17' => [
				'value' => 'foo',
				'expectedType' => 'mixed',
			],
			'DP #18' => [
				'value' => DateTimeImmutable::class,
				'expectedType' => 'mixed',
			],
			'DP #19' => [
				'value' => 1,
				'expectedType' => 'string|mixed',
			],
			'DP #20' => [
				'value' => [],
				'expectedType' => 'string[]',
			],
			'DP #22' => [
				'value' => ['foo'],
				'expectedType' => 'string[]',
			],
			'DP #24' => [
				'value' => ['foo', 'bar'],
				'expectedType' => 'string[]',
			],
			'DP #25' => [
				'value' => [],
				'expectedType' => 'string[]|int[]',
			],
			'DP #26' => [
				'value' => ['foo', 'bar'],
				'expectedType' => 'string[]|int[]',
			],
			'DP #27' => [
				'value' => 2,
				'expectedType' => 'string[]|int',
			],
			'DP #28' => [
				'value' => [1, 2],
				'expectedType' => 'string[]|int[]',
			],
			'DP #29' => [
				'value' => [new DateTimeImmutable()],
				'expectedType' => 'object[]',
			],
			'DP #30' => [
				'value' => [new DateTimeImmutable(), new stdClass()],
				'expectedType' => 'object[]',
			],
			'DP #32' => [
				'value' => [1, 'foo'],
				'expectedType' => 'mixed[]',
			],
			'DP #34' => [
				'value' => [1, 'foo'],
				'expectedType' => 'mixed',
			],
			'DP #35' => [
				'value' => [[1, 2]],
				'expectedType' => 'int[][]',
			],
			'DP #36' => [
				'value' => [[1, 2], [3, 4]],
				'expectedType' => 'int[][]',
			],
			'DP #37' => [
				'value' => [[1, 2], [3, 4]],
				'expectedType' => 'integer[][]',
			],
			'DP #41' => [
				'value' => new ArrayObject([]),
				'expectedType' => 'string[]',
			],
			'DP #42' => [
				'value' => new ArrayObject(['foo']),
				'expectedType' => 'string[]',
			],
			'DP #44' => [
				'value' => new ArrayObject(['foo', 'bar']),
				'expectedType' => 'string[]',
			],
			'DP #45' => [
				'value' => new ArrayObject([]),
				'expectedType' => 'string[]|int[]',
			],
			'DP #46' => [
				'value' => new ArrayObject(['foo', 'bar']),
				'expectedType' => 'string[]|int[]',
			],
			'DP #47' => [
				'value' => new ArrayObject([1, 2]),
				'expectedType' => 'string[]|int[]',
			],
			'DP #48' => [
				'value' => new ArrayObject([new ArrayObject([1, 2])]),
				'expectedType' => 'int[][]',
			],
			'DP #49' => [
				'value' => new ArrayObject([new ArrayObject([1, 2]), new ArrayObject([3, 4])]),
				'expectedType' => 'int[][]',
			],
			'DP #53' => [
				'value' => new ArrayObject([]),
				'expectedType' => 'string[]',
			],
			'DP #54' => [
				'value' => new ArrayObject(['foo']),
				'expectedType' => 'string[]',
			],
			'DP #56' => [
				'value' => new ArrayObject(['foo', 'bar']),
				'expectedType' => 'string[]',
			],
			'DP #57' => [
				'value' => new ArrayObject([]),
				'expectedType' => 'string[]|int[]',
			],
			'DP #58' => [
				'value' => new ArrayObject(['foo', 'bar']),
				'expectedType' => 'string[]|int[]',
			],
			'DP #59' => [
				'value' => new ArrayObject([1, 2]),
				'expectedType' => 'string[]|int[]',
			],
			'DP #60' => [
				'value' => new ArrayObject([new ArrayObject([1, 2])]),
				'expectedType' => 'int[][]',
			],
			'DP #61' => [
				'value' => new ArrayObject([new ArrayObject([1, 2]), new ArrayObject([3, 4])]),
				'expectedType' => 'int[][]',
			],
			'DP #65' => [
				'value' => [],
				'expectedType' => 'int:string[]',
			],
			'DP #67' => [
				'value' => ['foo'],
				'expectedType' => 'int:string[]',
			],
			'DP #69' => [
				'value' => ['foo', 'bar'],
				'expectedType' => 'int:string[]',
			],
			'DP #70' => [
				'value' => [],
				'expectedType' => 'int:string[]|int:int[]',
			],
			'DP #71' => [
				'value' => ['foo', 'bar'],
				'expectedType' => 'int:string[]|int:int[]',
			],
			'DP #72' => [
				'value' => [1, 2],
				'expectedType' => 'int:string[]|int:int[]',
			],
			'DP #73' => [
				'value' => [1, 'foo'],
				'expectedType' => 'int:mixed[]',
			],
			'DP #75' => [
				'value' => [[1, 2]],
				'expectedType' => 'int:int:int[][]',
			],
			'DP #76' => [
				'value' => [[1, 2], [3, 4]],
				'expectedType' => 'int:int:int[][]',
			],
			'DP #80' => [
				'value' => new ArrayObject([]),
				'expectedType' => 'int:string[]',
			],
			'DP #81' => [
				'value' => new ArrayObject(['foo']),
				'expectedType' => 'int:string[]',
			],
			'DP #83' => [
				'value' => new ArrayObject(['foo', 'bar']),
				'expectedType' => 'int:string[]',
			],
			'DP #84' => [
				'value' => new ArrayObject([]),
				'expectedType' => 'int:string[]|int:int[]',
			],
			'DP #85' => [
				'value' => new ArrayObject(['foo', 'bar']),
				'expectedType' => 'int:string[]|int:int[]',
			],
			'DP #86' => [
				'value' => new ArrayObject([1, 2]),
				'expectedType' => 'int:string[]|int:int[]',
			],
			'DP #87' => [
				'value' => new ArrayObject([new ArrayObject([1, 2])]),
				'expectedType' => 'int:int:int[][]',
			],
			'DP #88' => [
				'value' => new ArrayObject([new ArrayObject([1, 2]), new ArrayObject([3, 4])]),
				'expectedType' => 'int:int:int[][]',
			],
			'DP #92' => [
				'value' => ['foo' => 'bar'],
				'expectedType' => 'string:string[]',
			],
			'DP #94' => [
				'value' => ['foo', 'bar'],
				'expectedType' => 'string:string[]|int:string[]',
			],
			'DP #95' => [
				'value' => ['foo' => 'bar'],
				'expectedType' => 'string:string[]|int:string[]',
			],
			'DP #96' => [
				'value' => ['foo' => ['bar']],
				'expectedType' => 'string:int:string[][]',
			],
			'DP #98' => [
				'value' => [['foo' => 'bar']],
				'expectedType' => 'int:string:string[][]',
			],
			'DP #100' => [
				'value' => ['foo' => ['bar']],
				'expectedType' => 'string:string[][]',
			],
			'DP #102' => [
				'value' => ['foo' => ['bar']],
				'expectedType' => 'mixed:int:string[][]',
			],
		];
	}

	/**
	 * @return mixed[][]
	 */
	public function typeChecksProviderIncorrect(): array
	{
		return [
			'DP #15' => [
				'value' => 'foo',
				'expectedType' => 'object',
			],
			'DP #21' => [
				'value' => 'foo',
				'expectedType' => 'string[]',
			],
			'DP #23' => [
				'value' => [1],
				'expectedType' => 'string[]',
			],
			'DP #31' => [
				'value' => [new DateTimeImmutable(), 'foo'],
				'expectedType' => 'object[]',
			],
			'DP #33' => [
				'value' => 1,
				'expectedType' => 'mixed[]',
			],
			'DP #38' => [
				'value' => [[1, 2], ['foo']],
				'expectedType' => 'int[][]',
			],
			'DP #39' => [
				'value' => [['foo']],
				'expectedType' => 'int[][]',
			],
			'DP #40' => [
				'value' => [1, 2],
				'expectedType' => 'int[][]',
			],
			'DP #43' => [
				'value' => new ArrayObject([1]),
				'expectedType' => 'string[]',
			],
			'DP #50' => [
				'value' => new ArrayObject([new ArrayObject([1, 2]), new ArrayObject(['foo'])]),
				'expectedType' => 'int[][]',
			],
			'DP #51' => [
				'value' => new ArrayObject([new ArrayObject(['foo'])]),
				'expectedType' => 'int[][]',
			],
			'DP #52' => [
				'value' => new ArrayObject([1, 2]),
				'expectedType' => 'int[][]',
			],
			'DP #55' => [
				'value' => new ArrayObject([1]),
				'expectedType' => 'string[]',
			],
			'DP #62' => [
				'value' => new ArrayObject([new ArrayObject([1, 2]), new ArrayObject(['foo'])]),
				'expectedType' => 'int[][]',
			],
			'DP #63' => [
				'value' => new ArrayObject([new ArrayObject(['foo'])]),
				'expectedType' => 'int[][]',
			],
			'DP #64' => [
				'value' => new ArrayObject([1, 2]),
				'expectedType' => 'int[][]',
			],
			'DP #66' => [
				'value' => 'foo',
				'expectedType' => 'int:string[]',
			],
			'DP #68' => [
				'value' => [1],
				'expectedType' => 'int:string[]',
			],
			'DP #74' => [
				'value' => 1,
				'expectedType' => 'int:mixed[]',
			],
			'DP #77' => [
				'value' => [[1, 2], ['foo']],
				'expectedType' => 'int:int:int[][]',
			],
			'DP #78' => [
				'value' => [['foo']],
				'expectedType' => 'int[][]',
			],
			'DP #79' => [
				'value' => [1, 2],
				'expectedType' => 'int[][]',
			],
			'DP #82' => [
				'value' => new ArrayObject([1]),
				'expectedType' => 'int:string[]',
			],
			'DP #89' => [
				'value' => new ArrayObject([new ArrayObject([1, 2]), new ArrayObject(['foo'])]),
				'expectedType' => 'int:int:int[][]',
			],
			'DP #90' => [
				'value' => new ArrayObject([new ArrayObject(['foo'])]),
				'expectedType' => 'int:int:int[][]',
			],
			'DP #91' => [
				'value' => new ArrayObject([1, 2]),
				'expectedType' => 'int:int:int[][]',
			],
			'DP #93' => [
				'value' => ['foo', 'bar'],
				'expectedType' => 'string:string[]',
			],
			'DP #97' => [
				'value' => ['foo' => ['bar']],
				'expectedType' => 'string:string:string[][]',
			],
			'DP #99' => [
				'value' => [['foo' => 'bar']],
				'expectedType' => 'int:int:string[][]',
			],
			'DP #101' => [
				'value' => ['foo' => ['bar']],
				'expectedType' => 'int:string[][]',
			],
			'DP #103' => [
				'value' => ['foo' => ['bar']],
				'expectedType' => 'mixed:string:string[][]',
			],
		];
	}

	/**
	 * @dataProvider typesProvider
	 *
	 * @param mixed $value
	 * @param string $expectedType
	 */
	public function testTypes($value, string $expectedType): void
	{
		$this->assertSame($expectedType, Type::getType($value));
	}

	/**
	 * @dataProvider typesProvider
	 * @dataProvider typeChecksProvider
	 *
	 * @param mixed $value
	 * @param string $expectedType
	 */
	public function testHasType($value, string $expectedType): void
	{
		$this->assertTrue(Type::hasType($value, $expectedType));
	}

	/**
	 * @dataProvider typeChecksProviderIncorrect
	 *
	 * @param mixed $value
	 * @param string $type
	 */
	public function testHasTypeIncorrect($value, string $type): void
	{
		$this->assertFalse(Type::hasType($value, $type));
	}

	/**
	 * @dataProvider typesProvider
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
	 * @dataProvider typesProvider
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
