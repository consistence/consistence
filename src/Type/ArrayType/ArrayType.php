<?php

namespace Consistence\Type\ArrayType;

class ArrayType extends \Consistence\ObjectPrototype
{

	const STRICT_TRUE = true;
	const STRICT_FALSE = false;

	final public function __construct()
	{
		throw new \Consistence\StaticClassException();
	}

	/**
	 * Wrapper for PHP in_array, provides safer default parameter
	 *
	 * @param mixed[] $haystack
	 * @param mixed $needle
	 * @param boolean $strict
	 * @return boolean
	 */
	public static function inArray(array $haystack, $needle, $strict = self::STRICT_TRUE)
	{
		return in_array($needle, $haystack, $strict);
	}

}
