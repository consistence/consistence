<?php

namespace Consistence\RegExp;

class RegExp extends \Consistence\ObjectPrototype
{

	final public function __construct()
	{
		throw new \Consistence\StaticClassException();
	}

	/**
	 * @param string $subject
	 * @param string $pattern
	 * @param integer $flags
	 * @param integer $offset
	 * @return string[] array matches of matches
	 */
	public static function match($subject, $pattern, $flags = 0, $offset = 0)
	{
		if ($offset > strlen($subject)) {
			return [];
		}
		$matches = [];
		$result = preg_match($pattern, $subject, $matches, $flags, $offset);
		if ($result === false) {
			throw new \Consistence\RegExp\Exception($pattern, preg_last_error());
		}
		if ($result === 0 || $matches === null) {
			return [];
		}

		return $matches;
	}

	/**
	 * @param string $subject
	 * @param string $pattern
	 * @param integer $flags
	 * @param integer $offset
	 * @return boolean
	 */
	public static function matches($subject, $pattern, $flags = 0, $offset = 0)
	{
		$matches = self::match($subject, $pattern, $flags, $offset);
		return count($matches) !== 0;
	}

}
