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
	 * @dataProvider typesProvider
	 *
	 * @param mixed $type
	 * @param string $expected
	 */
	public function testTypes($type, $expected)
	{
		$this->assertSame($expected, Type::getType($type));
	}

}
