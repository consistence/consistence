<?php

declare(strict_types = 1);

namespace Consistence\Type\String;

class Utf8StringType extends \Consistence\ObjectPrototype
{

	final public function __construct()
	{
		throw new \Consistence\StaticClassException();
	}

	public static function length(string $string): int
	{
		return strlen(utf8_decode($string));
	}

}
