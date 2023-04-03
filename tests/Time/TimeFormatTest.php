<?php

declare(strict_types = 1);

namespace Consistence\Time;

use DateTime;
use DateTimeImmutable;
use DateTimeZone;

class TimeFormatTest extends \Consistence\TestCase
{

	public function testCreateDateTimeFromTimestamp(): void
	{
		$original = new DateTime();
		$converted = TimeFormat::createDateTimeFromTimestamp($original->getTimestamp());
		$this->assertSame($original->getTimestamp(), $converted->getTimestamp());
		$this->assertEquals($original->getTimezone(), $converted->getTimezone());
	}

	public function testCreateDateTimeFromTimestampWithCustomTimezone(): void
	{
		$timezone = new DateTimeZone('UTC');
		$original = new DateTime('now', $timezone);

		$converted = TimeFormat::createDateTimeFromTimestamp($original->getTimestamp(), $timezone);

		$this->assertSame($original->getTimestamp(), $converted->getTimestamp());
		$this->assertEquals($timezone, $converted->getTimezone());
	}

	public function testCreateDateTimeImmutableFromTimestamp(): void
	{
		$original = new DateTimeImmutable();
		$converted = TimeFormat::createDateTimeImmutableFromTimestamp($original->getTimestamp());
		$this->assertSame($original->getTimestamp(), $converted->getTimestamp());
		$this->assertEquals($original->getTimezone(), $converted->getTimezone());
	}

	public function testCreateDateTimeImmutableFromTimestampWithCustomTimezone(): void
	{
		$timezone = new DateTimeZone('UTC');
		$original = new DateTimeImmutable('now', $timezone);

		$converted = TimeFormat::createDateTimeImmutableFromTimestamp($original->getTimestamp(), $timezone);

		$this->assertSame($original->getTimestamp(), $converted->getTimestamp());
		$this->assertEquals($timezone, $converted->getTimezone());
	}

	public function testCreateDateTimeFromDateTimeInterface(): void
	{
		$original = new DateTimeImmutable();
		$converted = TimeFormat::createDateTimeFromDateTimeInterface($original);
		$this->assertInstanceOf(DateTime::class, $converted);
		$this->assertEquals($original, $converted);
		$this->assertEquals($original->getTimezone(), $converted->getTimezone());
	}

	public function testCreateDateTimeImmutableFromDateTimeInterface(): void
	{
		$original = new DateTime();
		$converted = TimeFormat::createDateTimeImmutableFromDateTimeInterface($original);
		$this->assertInstanceOf(DateTimeImmutable::class, $converted);
		$this->assertEquals($original, $converted);
		$this->assertEquals($original->getTimezone(), $converted->getTimezone());
	}

	/**
	 * @return string[][]
	 */
	public function validTimeProvider(): array
	{
		return [
			['H:i', '02:00'],
			['Y-m-d', '2016-01-02'],
			['Y-m-d', '2016-02-29'],
			['Y-n-j', '2016-1-2'],
			[TimeFormat::ISO8601, '2016-03-21T14:30:32+0100'],
			[TimeFormat::ISO8601_TIMEZONE_WITH_COLON, '2016-03-21T14:30:32+01:00'],
			['Y-m-d\TH:i:se', '2016-03-21T14:30:32Z'],
			['Y-m-d H:i:s e', '2016-03-21 14:30:00 Europe/Prague'],
			['Y-m-d H:i:s T', '2016-03-21 14:30:00 CEST'],
			[TimeFormat::ISO8601, '2016-03-21T14:30:32-0100'],
			[TimeFormat::ISO8601_WITH_MICROSECONDS, '2016-03-21T14:30:32.000001+0100'],
			[TimeFormat::ISO8601_WITH_MICROSECONDS, '2016-03-21T14:30:32.100000+0100'],
			[TimeFormat::ISO8601_WITH_MICROSECONDS, '2016-03-21T14:30:32.123456+0100'],
			[TimeFormat::ISO8601_WITH_MICROSECONDS, '2016-03-21T14:30:32.999999+0100'],
		];
	}

	/**
	 * @return string[][]
	 */
	public function invalidTimeForFormatProvider(): array
	{
		return [
			[TimeFormat::ISO8601, '', 'empty string'],
			['H:i', '02;30', 'containing different hour-minute separator'],
			['H:i', '2:30', 'missing leading zero in hour'],
			['Y-m-d', '2016-1-2', 'there are missing zeroes at the beginning of day and month'],
			[TimeFormat::ISO8601_TIMEZONE_WITH_COLON, '2016-03-21T14:30:32+0100', '`:` is required as timezone hour:minute separator'],
			['s.u', '25.2', 'microseconds must have 6 digits'],
			['s.u', '25.1234567', 'microseconds must have 6 digits'],
		];
	}

	/**
	 * @return string[][]
	 */
	public function nonExistingTimeProvider(): array
	{
		return [
			['H:i', '25:00', 'there is no 25th hour in the day'],
			['Y-m-d', '2015-02-29', 'this day does not exist when not in leap year'],
			['Y-m-d H:i:s e', '2016-03-27 02:30:00 Europe/Prague', 'this time does not exist, the time is moving to DST, skipping from 2:00 to 3:00'],
		];
	}

	/**
	 * @return string[][]
	 */
	public function invalidTimeProvider(): array
	{
		return array_merge(
			$this->invalidTimeForFormatProvider(),
			$this->nonExistingTimeProvider()
		);
	}

	/**
	 * @dataProvider validTimeProvider
	 *
	 * @param string $format
	 * @param string $timeString
	 */
	public function testCheckValidTimes(string $format, string $timeString): void
	{
		TimeFormat::checkTime($format, $timeString);
		$this->ok();
	}

	/**
	 * @dataProvider invalidTimeForFormatProvider
	 *
	 * @param string $format
	 * @param string $timeString
	 * @param string $reason
	 */
	public function testCheckInvalidTimes(string $format, string $timeString, string $reason): void
	{
		try {
			TimeFormat::checkTime($format, $timeString);
			$this->fail(sprintf('Exception was expected for time %s given for format %s, because %s', $timeString, $format, $reason));
		} catch (\Consistence\Time\TimeDoesNotMatchFormatException $e) {
			$this->assertSame($format, $e->getFormat());
			$this->assertSame($timeString, $e->getTimeString());
		}
	}

	/**
	 * @dataProvider nonExistingTimeProvider
	 *
	 * @param string $format
	 * @param string $timeString
	 * @param string $reason
	 */
	public function testCheckNonExistingTimes(string $format, string $timeString, string $reason): void
	{
		try {
			TimeFormat::checkTime($format, $timeString);
			$this->fail(sprintf('Exception was expected for time %s given for format %s, because %s', $timeString, $format, $reason));
		} catch (\Consistence\Time\TimeDoesNotExistException $e) {
			$this->assertSame($timeString, $e->getTimeString());
		}
	}

	/**
	 * @dataProvider validTimeProvider
	 *
	 * @param string $format
	 * @param string $timeString
	 */
	public function testValidTimes(string $format, string $timeString): void
	{
		$this->assertTrue(TimeFormat::isValidTime($format, $timeString));
	}

	/**
	 * @dataProvider invalidTimeProvider
	 *
	 * @param string $format
	 * @param string $timeString
	 * @param string $reason
	 */
	public function testInvalidTimes(string $format, string $timeString, string $reason): void
	{
		$this->assertFalse(
			TimeFormat::isValidTime($format, $timeString),
			sprintf('Expected that time %s given for format %s is invalid, because %s', $timeString, $format, $reason)
		);
	}

	/**
	 * @dataProvider validTimeProvider
	 *
	 * @param string $format
	 * @param string $timeString
	 */
	public function testCreateValidDateTime(string $format, string $timeString): void
	{
		$time = TimeFormat::createDateTimeFromFormat(
			$format,
			$timeString,
			new DateTimeZone('UTC')
		);

		$this->assertInstanceOf(DateTime::class, $time);
		$this->assertSame(DateTime::createFromFormat(
			$format,
			$timeString,
			new DateTimeZone('UTC')
		)->format($format), $time->format($format));
	}

	public function testCreateDateTimeWithDefaultTimezone(): void
	{
		$this->assertInstanceOf(DateTime::class, TimeFormat::createDateTimeFromFormat('Y', '2016'));
	}

	/**
	 * @dataProvider invalidTimeProvider
	 *
	 * @param string $format
	 * @param string $timeString
	 * @param string $reason
	 */
	public function testCreateInvalidDateTime(string $format, string $timeString, string $reason): void
	{
		try {
			TimeFormat::createDateTimeFromFormat(
				$format,
				$timeString,
				new DateTimeZone('UTC')
			);

			$this->fail(sprintf('Exception was expected for time %s given for format %s, because %s', $timeString, $format, $reason));
		} catch (\Consistence\Time\InvalidTimeForFormatException $e) {
			$this->assertSame($timeString, $e->getTimeString());
		}
	}

	/**
	 * @dataProvider validTimeProvider
	 *
	 * @param string $format
	 * @param string $timeString
	 */
	public function testCreateValidDateTimeImmutable(string $format, string $timeString): void
	{
		$time = TimeFormat::createDateTimeImmutableFromFormat(
			$format,
			$timeString,
			new DateTimeZone('UTC')
		);

		$this->assertInstanceOf(DateTimeImmutable::class, $time);
		$this->assertSame(DateTimeImmutable::createFromFormat(
			$format,
			$timeString,
			new DateTimeZone('UTC')
		)->format($format), $time->format($format));
	}

	public function testCreateDateTimeImmutableWithDefaultTimezone(): void
	{
		$this->assertInstanceOf(DateTimeImmutable::class, TimeFormat::createDateTimeImmutableFromFormat('Y', '2016'));
	}

	/**
	 * @dataProvider invalidTimeProvider
	 *
	 * @param string $format
	 * @param string $timeString
	 * @param string $reason
	 */
	public function testCreateInvalidDateTimeImmutable(string $format, string $timeString, string $reason): void
	{
		try {
			TimeFormat::createDateTimeImmutableFromFormat(
				$format,
				$timeString,
				new DateTimeZone('UTC')
			);

			$this->fail(sprintf('Exception was expected for time %s given for format %s, because %s', $timeString, $format, $reason));
		} catch (\Consistence\Time\InvalidTimeForFormatException $e) {
			$this->assertSame($timeString, $e->getTimeString());
		}
	}

}
