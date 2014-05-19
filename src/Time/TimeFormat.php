<?php

namespace Consistence\Time;

use Consistence\Type\Type;

use DateTime;
use DateTimeImmutable;
use DateTimeInterface;

/**
 * @see http://php.net/manual/en/function.date.php#refsect1-function.date-parameters
 * @see http://www.php.net/manual/en/class.datetime.php#datetime.constants.types
 */
class TimeFormat extends \Consistence\ObjectPrototype
{

	const ATOM = DATE_ATOM;
	const COOKIE = DATE_COOKIE;
	const ISO8601 = DATE_ISO8601;
	const ISO8601_TIMEZONE_WITH_COLON = DATE_RFC3339;
	const ISO8601_WITHOUT_TIMEZONE = 'Y-m-d\TH:i:s';
	const RFC822 = DATE_RFC822;
	const RFC850 = DATE_RFC850;
	const RFC1036 = DATE_RFC1036;
	const RFC1123 = DATE_RFC1123;
	const RFC2822 = DATE_RFC2822;
	const RFC3339 = DATE_RFC3339;
	const RSS = DATE_RSS;
	const W3C = DATE_W3C;

	const DAY_OF_MONTH = 'j';
	const DAY_OF_MONTH_LEADING_ZERO = 'd';
	const DAY_OF_WEEK = 'w';
	const DAY_OF_WEEK_ISO8601 = 'N';
	const DAY_OF_WEEK_TEXT_FULL = 'l';
	const DAY_OF_WEEK_TEXT_THREE_LETTERS = 'D';
	const DAY_OF_YEAR = 'z';
	const DAYS_IN_MONTH = 't';
	const MICROSECOND_OF_SECOND = 'u';
	const MINUTE_OF_HOUR_LEADING_ZERO = 'i';
	const MONTH_OF_YEAR = 'n';
	const MONTH_OF_YEAR_LEADING_ZERO = 'm';
	const MONTH_OF_YEAR_TEXT_FULL = 'F';
	const MONTH_OF_YEAR_THREE_LETTERS = 'M';
	const SECOND_OF_MINUTE_LEADING_ZERO = 's';
	const TIME_OF_DAY = 'G';
	const TIME_OF_DAY_LEADING_ZERO = 'H';
	const TIME_OF_DAY_12_HOUR = 'g';
	const TIME_OF_DAY_12_HOUR_LEADING_ZERO = 'h';
	const TIME_OF_DAY_AM_PM = 'a';
	const TIME_OF_DAY_AM_PM_UPPERCASE = 'A';
	const TIME_OF_DAY_SWATCH = 'B';
	const TIMEZONE = 'e';
	const TIMEZONE_ABBREVIATION = 'T';
	const TIMEZONE_IS_DAYLIGHT = 'I';
	const TIMEZONE_OFFSET = 'O';
	const TIMEZONE_OFFSET_SECONDS = 'Z';
	const TIMEZONE_OFFSET_WITH_COLON = 'P';
	const UNIX_TIMESTAMP = 'U';
	const WEEK_OF_YEAR = 'W';
	const YEAR = 'Y';
	const YEAR_IS_LEAP = 'L';
	const YEAR_ISO8601 = 'o';
	const YEAR_TWO_DIGIT = 'y';

	/**
	 * Convert unix timestamp to DateTime with current timezone
	 *
	 * @param integer $timestamp
	 * @return \DateTime
	 */
	public static function createDateTimeFromTimestamp($timestamp)
	{
		Type::checkType($timestamp, 'integer');
		return new DateTime(date(self::ISO8601_WITHOUT_TIMEZONE, $timestamp));
	}

	/**
	 * Convert unix timestamp to DateTimeImmutable with current timezone
	 *
	 * @param integer $timestamp
	 * @return \DateTimeImmutable
	 */
	public static function createDateTimeImmutableFromTimestamp($timestamp)
	{
		Type::checkType($timestamp, 'integer');
		return new DateTimeImmutable(date(self::ISO8601_WITHOUT_TIMEZONE, $timestamp));
	}

	/**
	 * Create DateTime from DateTimeInterface while preserving original Timezone
	 *
	 * @param \DateTimeInterface $date
	 * @return \DateTime
	 */
	public static function createDateTimeFromDateTimeInterface(DateTimeInterface $date)
	{
		return new DateTime($date->format(self::ISO8601_WITHOUT_TIMEZONE), $date->getTimezone());
	}

}
