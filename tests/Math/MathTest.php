<?php

declare(strict_types = 1);

namespace Consistence\Math;

use PHPUnit\Framework\Assert;

class MathTest extends \Consistence\TestCase
{

	/**
	 * @return int[][]
	 */
	public function moduloDataProvider(): array
	{
		return [
			[4, 2, 0],
			[5, 2, 1],
			[-5, 2, 1],
		];
	}

	/**
	 * @dataProvider moduloDataProvider
	 *
	 * @param int $dividend
	 * @param int $modulo
	 * @param int $result
	 */
	public function testModulo(int $dividend, int $modulo, int $result): void
	{
		Assert::assertSame(Math::modulo($dividend, $modulo), $result);
	}

	public function testModuloNegativeModulus(): void
	{
		try {
			Math::modulo(5, -1);
			Assert::fail();
		} catch (\Consistence\Math\NonNegativeIntegerExpectedException $e) {
			Assert::assertSame(-1, $e->getValue());
		}
	}

	/**
	 * @return mixed[][]
	 */
	public function powersOfTwoDataProvider(): array
	{
		return [
			[-2, false],
			[-1, false],
			[0, false],
			[1, true],
			[2, true],
			[3, false],
			[4, true],
			[6, false],
			[8, true],
			[10, false],
			[16, true],
			[32, true],
		];
	}

	/**
	 * @dataProvider powersOfTwoDataProvider
	 *
	 * @param int $value
	 * @param bool $result
	 */
	public function testIsPowerOfTwo(int $value, bool $result): void
	{
		Assert::assertSame(Math::isPowerOfTwo($value), $result);
	}

}
