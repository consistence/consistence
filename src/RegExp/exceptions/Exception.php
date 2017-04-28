<?php

declare(strict_types = 1);

namespace Consistence\RegExp;

class Exception extends \Exception
{

	/**
	 * @see http://php.net/manual/en/pcre.constants.php
	 *
	 * @var string[]
	 */
	private static $messages = [
		PREG_INTERNAL_ERROR => 'Internal error',
		PREG_BACKTRACK_LIMIT_ERROR => 'Backtrack limit was exhausted',
		PREG_RECURSION_LIMIT_ERROR => 'Recursion limit was exhausted',
		PREG_BAD_UTF8_ERROR => 'Malformed UTF-8 data (only when running a regex in UTF-8 mode)',
		PREG_BAD_UTF8_OFFSET_ERROR => 'Offset didn\'t correspond to the begin of a valid UTF-8 code point (only when running a regex in UTF-8 mode)',
	];

	public function __construct(string $pattern, int $code, \Throwable $previous = null)
	{
		$message = $pattern;
		if (isset(self::$messages[$code])) {
			$message .= ': ' . self::$messages[$code];
		}
		parent::__construct($message, $code, $previous);
	}

}
