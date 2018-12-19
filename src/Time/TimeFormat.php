<?php

declare(strict_types = 1);

namespace Consistence\Time;

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

	public const ATOM = DATE_ATOM;
	public const COOKIE = DATE_COOKIE;
	public const ISO8601 = DATE_ISO8601;
	public const ISO8601_WITH_MICROSECONDS = 'Y-m-d\TH:i:s.uO';
	public const ISO8601_WITH_MICROSECONDS_WITHOUT_TIMEZONE = 'Y-m-d\TH:i:s.u';
	public const ISO8601_TIMEZONE_WITH_COLON = DATE_RFC3339;
	public const ISO8601_WITHOUT_TIMEZONE = 'Y-m-d\TH:i:s';
	public const RFC822 = DATE_RFC822;
	public const RFC850 = DATE_RFC850;
	public const RFC1036 = DATE_RFC1036;
	public const RFC1123 = DATE_RFC1123;
	public const RFC2822 = DATE_RFC2822;
	public const RFC3339 = DATE_RFC3339;
	public const RSS = DATE_RSS;
	public const W3C = DATE_W3C;

	public const DAY_OF_MONTH = 'j';
	public const DAY_OF_MONTH_LEADING_ZERO = 'd';
	public const DAY_OF_WEEK = 'w';
	public const DAY_OF_WEEK_ISO8601 = 'N';
	public const DAY_OF_WEEK_TEXT_FULL = 'l';
	public const DAY_OF_WEEK_TEXT_THREE_LETTERS = 'D';
	public const DAY_OF_YEAR = 'z';
	public const DAYS_IN_MONTH = 't';
	public const MICROSECOND_OF_SECOND = 'u';
	public const MINUTE_OF_HOUR_LEADING_ZERO = 'i';
	public const MONTH_OF_YEAR = 'n';
	public const MONTH_OF_YEAR_LEADING_ZERO = 'm';
	public const MONTH_OF_YEAR_TEXT_FULL = 'F';
	public const MONTH_OF_YEAR_THREE_LETTERS = 'M';
	public const SECOND_OF_MINUTE_LEADING_ZERO = 's';
	public const TIME_OF_DAY = 'G';
	public const TIME_OF_DAY_LEADING_ZERO = 'H';
	public const TIME_OF_DAY_12_HOUR = 'g';
	public const TIME_OF_DAY_12_HOUR_LEADING_ZERO = 'h';
	public const TIME_OF_DAY_AM_PM = 'a';
	public const TIME_OF_DAY_AM_PM_UPPERCASE = 'A';
	public const TIME_OF_DAY_SWATCH = 'B';
	public const TIMEZONE = 'e';
	public const TIMEZONE_ABBREVIATION = 'T';
	public const TIMEZONE_IS_DAYLIGHT = 'I';
	public const TIMEZONE_OFFSET = 'O';
	public const TIMEZONE_OFFSET_SECONDS = 'Z';
	public const TIMEZONE_OFFSET_WITH_COLON = 'P';
	public const UNIX_TIMESTAMP = 'U';
	public const WEEK_OF_YEAR = 'W';
	public const YEAR = 'Y';
	public const YEAR_IS_LEAP = 'L';
	public const YEAR_ISO8601 = 'o';
	public const YEAR_TWO_DIGIT = 'y';

	public const TIMEZONE_PHP_TYPE_OFFSET = 1;
	public const TIMEZONE_PHP_TYPE_ABBREVIATION = 2;
	public const TIMEZONE_PHP_TYPE_IDENTIFIER = 3;

	/**
	 * Convert unix timestamp to DateTime with current timezone
	 *
	 * @param int $timestamp
	 * @param \DateTimeZone|null $timezone
	 * @return \DateTime
	 */
	public static function createDateTimeFromTimestamp(int $timestamp, ?DateTimeZone $timezone = null): DateTime
	{
		$time = new DateTime(date(self::ISO8601_WITHOUT_TIMEZONE, $timestamp));
		if ($timezone !== null) {
			$time->setTimezone($timezone);
		}

		return $time;
	}

	/**
	 * Convert unix timestamp to DateTimeImmutable with current timezone
	 *
	 * @param int $timestamp
	 * @param \DateTimeZone|null $timezone
	 * @return \DateTimeImmutable
	 */
	public static function createDateTimeImmutableFromTimestamp(
		int $timestamp,
		?DateTimeZone $timezone = null
	): DateTimeImmutable
	{
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
	public static function createDateTimeFromDateTimeInterface(DateTimeInterface $date): DateTime
	{
		return new DateTime($date->format(self::ISO8601_WITH_MICROSECONDS_WITHOUT_TIMEZONE), $date->getTimezone());
	}

	/**
	 * Create DateTimeImmutable from DateTimeInterface while preserving original Timezone
	 *
	 * @param \DateTimeInterface $date
	 * @return \DateTimeImmutable
	 */
	public static function createDateTimeImmutableFromDateTimeInterface(DateTimeInterface $date): DateTimeImmutable
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
	public static function checkTime(string $format, string $timeString): void
	{
		$parsedTime = date_parse_from_format($format, $timeString);
		if ($parsedTime['error_count'] > 0) {
			throw new \Consistence\Time\TimeDoesNotMatchFormatException($format, $timeString);
		}
		if ($parsedTime['warning_count'] > 0) {
			throw new \Consistence\Time\TimeDoesNotExistException($timeString);
		}
		switch (true) {
			case $parsedTime['is_localtime'] && $parsedTime['zone_type'] === self::TIMEZONE_PHP_TYPE_OFFSET:
				$timezoneOffsetInSeconds = $parsedTime['zone'];
				$timezoneOffsetInMinutes = (int) floor($timezoneOffsetInSeconds / 60);
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
	 * @return bool
	 */
	public static function isValidTime(string $format, string $timeString): bool
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
	public static function createDateTimeFromFormat(
		string $format,
		string $timeString,
		?DateTimeZone $timezone = null
	): DateTime
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
	public static function createDateTimeImmutableFromFormat(
		string $format,
		string $timeString,
		?DateTimeZone $timezone = null
	): DateTimeImmutable
	{
		self::checkTime($format, $timeString);
		if ($timezone === null) {
			$timezone = new DateTimeZone(date_default_timezone_get());
		}
		return DateTimeImmutable::createFromFormat($format, $timeString, $timezone);
	}

}
