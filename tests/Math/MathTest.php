<?php

declare(strict_types = 1);

namespace Consistence\Math;

class MathTest extends \Consistence\TestCase
{

	/**
	 * @return int[][]
	 */
	public function moduloProvider(): array
	{
		return [
			[4, 2, 0],
			[5, 2, 1],
			[-5, 2, 1],
		];
	}

	/**
	 * @dataProvider moduloProvider
	 *
	 * @param int $dividend
	 * @param int $modulo
	 * @param int $result
	 */
	public function testModulo(int $dividend, int $modulo, int $result)
	{
		$this->assertSame(Math::modulo($dividend, $modulo), $result);
	}

	public function testModuloNegativeModulus()
	{
		try {
			Math::modulo(5, -1);
			$this->fail();
		} catch (\Consistence\Math\NonNegativeIntegerExpectedException $e) {
			$this->assertSame(-1, $e->getValue());
		}
	}

	/**
	 * @return mixed[][]
	 */
	public function powersOfTwoProvider(): array
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
	 * @dataProvider powersOfTwoProvider
	 *
	 * @param int $value
	 * @param bool $result
	 */
	public function testIsPowerOfTwo(int $value, bool $result)
	{
		$this->assertSame(Math::isPowerOfTwo($value), $result);
	}

	public function factorialProvider(): array
	{
		return [
			[5,120],
			[0,1],
			[1,1],	
			[4,24],	
			[10,3628800],	
			[7,5040],	
		];
	}

	/**
	 * @dataProvider factorialProvider
	 *
	 * @param int $value
	 * @param int $result
	 */
	public function testFactorial(int $value, int $result)
	{
		$this->assertSame(Math::factorial($value), $result);
	}

	public function testNNegativeFactorial()
	{
		try {
			Math::factorial(-1);
			$this->fail();
		} catch (\Consistence\Math\NonNegativeIntegerExpectedException $e) {
			$this->assertSame(-1, $e->getValue());
		}
	}

}
