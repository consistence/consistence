<?php

namespace Consistence\Type\String;

class Utf8StringType extends \Consistence\ObjectPrototype
{

	final public function __construct()
	{
		throw new \Consistence\StaticClassException();
	}

	/**
	 * @param string $string
	 * @return integer
	 */
	public static function length($string)
	{
		return strlen(utf8_decode($string));
	}

}
