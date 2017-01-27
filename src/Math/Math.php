<?php

namespace Consistence\Math;

use Consistence\Type\Type;

class Math extends \Consistence\ObjectPrototype
{

	/**
	 * Modulo operation which returns always result between 0 and n-1 (contrary to the PHP % operator)
	 * @see http://en.wikipedia.org/wiki/Modulo_operation
	 *
	 * a mod n
	 *
	 * @param integer $dividend a
	 * @param integer $modulus n
	 * @return integer
	 */
	public static function modulo($dividend, $modulus)
	{
		Type::checkType($dividend, 'integer');
		Type::checkType($modulus, 'integer');
		if ($modulus < 0) {
			throw new \Consistence\Math\NonNegativeIntegerExpectedException($modulus);
		}
		$result = $dividend % $modulus;
		if ($result < 0) {
			return $modulus + $result;
		}

		return $result;
	}

	/**
	 * @param integer $value
	 * @return boolean
	 */
	public static function isPowerOfTwo($value)
	{
		Type::checkType($value, 'integer');

		if ($value < 1) {
			return false;
		}

		return ($value & ($value - 1)) === 0;
	}

}
