<?php

namespace Consistence\Time;

use Consistence\Type\Type;

use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;

/**
 * @see http://php.net/manual/en/function.date.php#refsect1-function.date-parameters
 * @see http://www.php.net/manual/en/class.datetime.php#datetime.constants.types
 */
class TimeFormat extends \Consistence\ObjectPrototype
{

	const ATOM = DATE_ATOM;
	const COOKIE = DATE_COOKIE;
	const ISO8601 = DATE_ISO8601;
	const ISO8601_WITH_MICROSECONDS = 'Y-m-d\TH:i:s.uO';
	const ISO8601_WITH_MICROSECONDS_WITHOUT_TIMEZONE = 'Y-m-d\TH:i:s.u';
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

	const TIMEZONE_PHP_TYPE_OFFSET = 1;
	const TIMEZONE_PHP_TYPE_ABBREVIATION = 2;
	const TIMEZONE_PHP_TYPE_IDENTIFIER = 3;

	/**
	 * Convert unix timestamp to DateTime with current timezone
	 *
	 * @param integer $timestamp
	 * @param \DateTimeZone|null $timezone
	 * @return \DateTime
	 */
	public static function createDateTimeFromTimestamp($timestamp, DateTimeZone $timezone = null)
	{
		Type::checkType($timestamp, 'integer');

		$time = new DateTime(date(self::ISO8601_WITHOUT_TIMEZONE, $timestamp));
		if ($timezone !== null) {
			$time->setTimezone($timezone);
		}

		return $time;
	}

	/**
	 * Convert unix timestamp to DateTimeImmutable with current timezone
	 *
	 * @param integer $timestamp
	 * @param \DateTimeZone|null $timezone
	 * @return \DateTimeImmutable
	 */
	public static function createDateTimeImmutableFromTimestamp($timestamp, DateTimeZone $timezone = null)
	{
		Type::checkType($timestamp, 'integer');

		$time = new DateTimeImmutable(date(self::ISO8601_WITHOUT_TIMEZONE, $timestamp));
		if ($timezone !== null) {
			$time = $time->setTimezone($timezone);
		}

		return $time;
	}

	/**
	 * Create DateTime from DateTimeInterface while preserving original Timezone
	 *
	 * @param \DateTimeInterface $date
	 * @return \DateTime
	 */
	public static function createDateTimeFromDateTimeInterface(DateTimeInterface $date)
	{
		return new DateTime($date->format(self::ISO8601_WITH_MICROSECONDS_WITHOUT_TIMEZONE), $date->getTimezone());
	}

	/**
	 * Create DateTimeImmutable from DateTimeInterface while preserving original Timezone
	 *
	 * @param \DateTimeInterface $date
	 * @return \DateTimeImmutable
	 */
	public static function createDateTimeImmutableFromDateTimeInterface(DateTimeInterface $date)
	{
		return new DateTimeImmutable($date->format(self::ISO8601_WITH_MICROSECONDS_WITHOUT_TIMEZONE), $date->getTimezone());
	}

	/**
	 * Checks if given time string is valid, checking has two parts:
	 *
	 * 1) String must be parsable according to given format.
	 *
	 * 2) String must represent a valid point in time according to given details.
	 *    This can be broken by simply giving 25:00 (invalid hour every time) or more nuanced
	 *    ways such as giving 2015-02-29 - not existing year because 2015 is not a leap year.
	 *
	 * This is achieved by parsing the date and then filling missing time parts,
	 * because if some parts are missing then the current time according to the system
	 * is used to fill these, which is unpredictable. The chosen "neutral" date
	 * is 2016-07-17\T12:30:30.5Z which is a leap year and a date where no daylight savings time
	 * shifts occurred or are planned.
	 *
	 * This should ensure, that only time parts that are specified in the format are taken into
	 * account while validating.
	 *
	 * @see http://php.net/manual/en/datetime.createfromformat.php for format syntax, but note that this method checks variants strictly
	 *
	 * @param string $format
	 * @param string $timeString
	 */
	public static function checkTime($format, $timeString)
	{
		Type::checkType($format, 'string');
		Type::checkType($timeString, 'string');

		$parsedTime = date_parse_from_format($format, $timeString);
		if ($parsedTime['error_count'] > 0) {
			throw new \Consistence\Time\TimeDoesNotMatchFormatException($format, $timeString);
		}
		if ($parsedTime['warning_count'] > 0) {
			throw new \Consistence\Time\TimeDoesNotExistException($timeString);
		}
		switch (true) {
			case $parsedTime['is_localtime'] && $parsedTime['zone_type'] === self::TIMEZONE_PHP_TYPE_OFFSET:
				$timezoneOffsetInMinutes = (-1) * $parsedTime['zone'];
				$timezoneOffsetHours = (int) floor($timezoneOffsetInMinutes / 60);
				$timezoneOffsetMinutes = $timezoneOffsetInMinutes % 60;
				$timezone = sprintf('%+02d:%02d', $timezoneOffsetHours, $timezoneOffsetMinutes);
				break;
			case $parsedTime['is_localtime'] && $parsedTime['zone_type'] === self::TIMEZONE_PHP_TYPE_ABBREVIATION:
				$timezone = $parsedTime['tz_abbr'];
				break;
			case $parsedTime['is_localtime'] && $parsedTime['zone_type'] === self::TIMEZONE_PHP_TYPE_IDENTIFIER:
				$timezone = $parsedTime['tz_id'];
				break;
			default:
				$timezone = 'UTC';
		}
		$completeTime = sprintf(
			'%d-%02d-%02d %02d:%02d:%09.6f %s',
			$parsedTime['year'] !== false ? $parsedTime['year'] : 2016, // leap year
			$parsedTime['month'] !== false ? $parsedTime['month'] : 7, // no time shifts occurred on 7-17
			$parsedTime['day'] !== false ? $parsedTime['day'] : 17,
			$parsedTime['hour'] !== false ? $parsedTime['hour'] : 12,
			$parsedTime['minute'] !== false ? $parsedTime['minute'] : 30,
			(
				($parsedTime['second'] !== false ? $parsedTime['second'] : 30)
				+ ($parsedTime['fraction'] !== false ? $parsedTime['fraction'] : 0.5)
			),
			$timezone
		);
		// timezone is parsed as abbreviation, but that does not matter, while parsing it recognizes all formats
		$dateTime = DateTime::createFromFormat('Y-m-d H:i:s.u T', $completeTime);
		if (
			($parsedTime['year'] !== false && (int) $parsedTime['year'] !== (int) $dateTime->format(self::YEAR))
			|| ($parsedTime['month'] !== false && (int) $parsedTime['month'] !== (int) $dateTime->format(self::MONTH_OF_YEAR))
			|| ($parsedTime['day'] !== false && (int) $parsedTime['day'] !== (int) $dateTime->format(self::DAY_OF_MONTH))
			|| ($parsedTime['hour'] !== false && (int) $parsedTime['hour'] !== (int) $dateTime->format(self::TIME_OF_DAY))
			|| ($parsedTime['minute'] !== false && (int) $parsedTime['minute'] !== (int) $dateTime->format(self::MINUTE_OF_HOUR_LEADING_ZERO))
			|| ($parsedTime['second'] !== false && (int) $parsedTime['second'] !== (int) $dateTime->format(self::SECOND_OF_MINUTE_LEADING_ZERO))
			|| ($parsedTime['fraction'] !== false && (float) $parsedTime['fraction'] !== (float) $dateTime->format('0.' . self::MICROSECOND_OF_SECOND))
		) {
			throw new \Consistence\Time\TimeDoesNotExistException($timeString);
		}
		if ($timeString !== $dateTime->format($format)) {
			throw new \Consistence\Time\TimeDoesNotMatchFormatException($format, $timeString);
		}
	}

	/**
	 * @see self::checkTime() for description
	 *
	 * @param string $format
	 * @param string $timeString
	 * @return boolean
	 */
	public static function isValidTime($format, $timeString)
	{
		try {
			self::checkTime($format, $timeString);
			return true;

		} catch (\Consistence\Time\InvalidTimeForFormatException $e) {
			return false;
		}
	}

	/**
	 * Create DateTime from format, but check format strictly
	 *
	 * @see http://php.net/manual/en/datetime.createfromformat.php for signature description
	 * @see self::checkTime() for additional checks
	 *
	 * @param string $format
	 * @param string $timeString
	 * @param \DateTimeZone|null $timezone
	 * @return \DateTime
	 */
	public static function createDateTimeFromFormat($format, $timeString, DateTimeZone $timezone = null)
	{
		self::checkTime($format, $timeString);
		if ($timezone === null) {
			$timezone = new DateTimeZone(date_default_timezone_get());
		}
		return DateTime::createFromFormat($format, $timeString, $timezone);
	}

	/**
	 * Create DateTimeImmutable from format, but check format strictly
	 *
	 * @see http://php.net/manual/en/datetimeimmutable.createfromformat.php for signature description
	 * @see self::checkTime() for additional checks
	 *
	 * @param string $format
	 * @param string $timeString
	 * @param \DateTimeZone|null $timezone
	 * @return \DateTimeImmutable
	 */
	public static function createDateTimeImmutableFromFormat($format, $timeString, DateTimeZone $timezone = null)
	{
		self::checkTime($format, $timeString);
		if ($timezone === null) {
			$timezone = new DateTimeZone(date_default_timezone_get());
		}
		return DateTimeImmutable::createFromFormat($format, $timeString, $timezone);
	}

}
