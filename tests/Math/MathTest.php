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
			'dividend' => 4,
			'modulo' => 2,
			'result' => 0,
		];
		yield 'dividend positive, remainder after division by modulo' => [
			'dividend' => 5,
			'modulo' => 2,
			'result' => 1,
		];
		yield 'dividend negative, remainder after division by modulo' => [
			'dividend' => -5,
			'modulo' => 2,
			'result' => 1,
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
			'value' => -2,
			'result' => false,
		];
		yield '-1 - not power of two' => [
			'value' => -1,
			'result' => false,
		];
		yield '0 - not power of two' => [
			'value' => 0,
			'result' => false,
		];
		yield '1 - 2^0' => [
			'value' => 1,
			'result' => true,
		];
		yield '2 - 2^1' => [
			'value' => 2,
			'result' => true,
		];
		yield '3 - not power of two' => [
			'value' => 3,
			'result' => false,
		];
		yield '4 - 2^2' => [
			'value' => 4,
			'result' => true,
		];
		yield '6 - not power of two' => [
			'value' => 6,
			'result' => false,
		];
		yield '8 - 2^3' => [
			'value' => 8,
			'result' => true,
		];
		yield '10 - not power of two' => [
			'value' => 10,
			'result' => false,
		];
		yield '16 - 2^4' => [
			'value' => 16,
			'result' => true,
		];
		yield '32 - 2^5' => [
			'value' => 32,
			'result' => true,
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
