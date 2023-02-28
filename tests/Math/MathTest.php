<?php

declare(strict_types = 1);

namespace Consistence\Math;

use Generator;
use PHPUnit\Framework\Assert;

class MathTest extends \PHPUnit\Framework\TestCase
{

	/**
	 * @return int[][]|\Generator
	 */
	public function moduloDataProvider(): Generator
	{
		yield 'dividend positive, no remainder after division by modulo' => [
			4,
			2,
			0,
		];
		yield 'dividend positive, remainder after division by modulo' => [
			5,
			2,
			1,
		];
		yield 'dividend negative, remainder after division by modulo' => [
			-5,
			2,
			1,
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
			Assert::fail('Exception expected');
		} catch (\Consistence\Math\NonNegativeIntegerExpectedException $e) {
			Assert::assertSame(-1, $e->getValue());
		}
	}

	/**
	 * @return mixed[][]|\Generator
	 */
	public function powerOfTwoDataProvider(): Generator
	{
		yield '-2 - not power of two' => [
			-2,
			false,
		];
		yield '-1 - not power of two' => [
			-1,
			false,
		];
		yield '0 - not power of two' => [
			0,
			false,
		];
		yield '1 - 2^0' => [
			1,
			true,
		];
		yield '2 - 2^1' => [
			2,
			true,
		];
		yield '3 - not power of two' => [
			3,
			false,
		];
		yield '4 - 2^2' => [
			4,
			true,
		];
		yield '6 - not power of two' => [
			6,
			false,
		];
		yield '8 - 2^3' => [
			8,
			true,
		];
		yield '10 - not power of two' => [
			10,
			false,
		];
		yield '16 - 2^4' => [
			16,
			true,
		];
		yield '32 - 2^5' => [
			32,
			true,
		];
	}

	/**
	 * @dataProvider powerOfTwoDataProvider
	 *
	 * @param int $value
	 * @param bool $result
	 */
	public function testIsPowerOfTwo(int $value, bool $result): void
	{
		Assert::assertSame(Math::isPowerOfTwo($value), $result);
	}

}
