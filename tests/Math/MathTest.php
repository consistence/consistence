<?php

namespace Consistence\Math;

class MathTest extends \Consistence\TestCase
{

	/**
	 * @return integer[][]
	 */
	public function moduloProvider()
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
	 * @param integer $dividend
	 * @param integer $modulo
	 * @param integer $result
	 */
	public function testModulo($dividend, $modulo, $result)
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

}
