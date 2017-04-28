<?php

declare(strict_types = 1);

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
	 * @param int $flags
	 * @param int $offset
	 * @return string[] array of matches
	 */
	public static function match(string $subject, string $pattern, int $flags = 0, int $offset = 0)
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

	public static function matches(string $subject, string $pattern, int $flags = 0, int $offset = 0): bool
	{
		$matches = self::match($subject, $pattern, $flags, $offset);
		return count($matches) !== 0;
	}

}
