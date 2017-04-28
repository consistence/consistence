<?php

declare(strict_types = 1);

namespace Consistence\Math;

class Math extends \Consistence\ObjectPrototype
{

	/**
	 * Modulo operation which returns always result between 0 and n-1 (contrary to the PHP % operator)
	 * @see http://en.wikipedia.org/wiki/Modulo_operation
	 *
	 * a mod n
	 *
	 * @param int $dividend a
	 * @param int $modulus n
	 * @return int
	 */
	public static function modulo(int $dividend, int $modulus): int
	{
		if ($modulus < 0) {
			throw new \Consistence\Math\NonNegativeIntegerExpectedException($modulus);
		}
		$result = $dividend % $modulus;
		if ($result < 0) {
			return $modulus + $result;
		}

		return $result;
	}

	public static function isPowerOfTwo(int $value): bool
	{
		if ($value < 1) {
			return false;
		}

		return ($value & ($value - 1)) === 0;
	}

}
