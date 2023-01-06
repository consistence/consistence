<?php

declare(strict_types = 1);

namespace Consistence\Time;

use DateTime;
use DateTimeImmutable;
use DateTimeZone;
use PHPUnit\Framework\Assert;

class TimeFormatTest extends \Consistence\TestCase
{

	public function testCreateDateTimeFromTimestamp(): void
	{
		$original = new DateTime();
		$converted = TimeFormat::createDateTimeFromTimestamp($original->getTimestamp());
		Assert::assertSame($original->getTimestamp(), $converted->getTimestamp());
		Assert::assertEquals($original->getTimezone(), $converted->getTimezone());
	}

	public function testCreateDateTimeFromTimestampWithCustomTimezone(): void
	{
		$timezone = new DateTimeZone('UTC');
		$original = new DateTime('now', $timezone);

		$converted = TimeFormat::createDateTimeFromTimestamp($original->getTimestamp(), $timezone);

		Assert::assertSame($original->getTimestamp(), $converted->getTimestamp());
		Assert::assertEquals($timezone, $converted->getTimezone());
	}

	public function testCreateDateTimeImmutableFromTimestamp(): void
	{
		$original = new DateTimeImmutable();
		$converted = TimeFormat::createDateTimeImmutableFromTimestamp($original->getTimestamp());
		Assert::assertSame($original->getTimestamp(), $converted->getTimestamp());
		Assert::assertEquals($original->getTimezone(), $converted->getTimezone());
	}

	public function testCreateDateTimeImmutableFromTimestampWithCustomTimezone(): void
	{
		$timezone = new DateTimeZone('UTC');
		$original = new DateTimeImmutable('now', $timezone);

		$converted = TimeFormat::createDateTimeImmutableFromTimestamp($original->getTimestamp(), $timezone);

		Assert::assertSame($original->getTimestamp(), $converted->getTimestamp());
		Assert::assertEquals($timezone, $converted->getTimezone());
	}

	public function testCreateDateTimeFromDateTimeInterface(): void
	{
		$original = new DateTimeImmutable();
		$converted = TimeFormat::createDateTimeFromDateTimeInterface($original);
		Assert::assertInstanceOf(DateTime::class, $converted);
		Assert::assertEquals($original, $converted);
		Assert::assertEquals($original->getTimezone(), $converted->getTimezone());
	}

	public function testCreateDateTimeImmutableFromDateTimeInterface(): void
	{
		$original = new DateTime();
		$converted = TimeFormat::createDateTimeImmutableFromDateTimeInterface($original);
		Assert::assertInstanceOf(DateTimeImmutable::class, $converted);
		Assert::assertEquals($original, $converted);
		Assert::assertEquals($original->getTimezone(), $converted->getTimezone());
	}

	/**
	 * @return string[][]
	 */
	public function validTimesProvider(): array
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
	public function invalidTimesForFormatProvider(): array
	{
		return [
			['', TimeFormat::ISO8601, 'empty string'],
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
	public function nonExistingTimesProvider(): array
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
	public function invalidTimesProvider(): array
	{
		return array_merge(
			$this->invalidTimesForFormatProvider(),
			$this->nonExistingTimesProvider()
		);
	}

	/**
	 * @dataProvider validTimesProvider
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
	 * @dataProvider invalidTimesForFormatProvider
	 *
	 * @param string $format
	 * @param string $timeString
	 * @param string $reason
	 */
	public function testCheckInvalidTimes(string $format, string $timeString, string $reason): void
	{
		try {
			TimeFormat::checkTime($format, $timeString);
			Assert::fail(sprintf('Exception was expected for time %s given for format %s, because %s', $timeString, $format, $reason));
		} catch (\Consistence\Time\TimeDoesNotMatchFormatException $e) {
			Assert::assertSame($format, $e->getFormat());
			Assert::assertSame($timeString, $e->getTimeString());
		}
	}

	/**
	 * @dataProvider nonExistingTimesProvider
	 *
	 * @param string $format
	 * @param string $timeString
	 * @param string $reason
	 */
	public function testCheckNonExistingTimes(string $format, string $timeString, string $reason): void
	{
		try {
			TimeFormat::checkTime($format, $timeString);
			Assert::fail(sprintf('Exception was expected for time %s given for format %s, because %s', $timeString, $format, $reason));
		} catch (\Consistence\Time\TimeDoesNotExistException $e) {
			Assert::assertSame($timeString, $e->getTimeString());
		}
	}

	/**
	 * @dataProvider validTimesProvider
	 *
	 * @param string $format
	 * @param string $timeString
	 */
	public function testValidTimes(string $format, string $timeString): void
	{
		Assert::assertTrue(TimeFormat::isValidTime($format, $timeString));
	}

	/**
	 * @dataProvider invalidTimesProvider
	 *
	 * @param string $format
	 * @param string $timeString
	 * @param string $reason
	 */
	public function testInvalidTimes(string $format, string $timeString, string $reason): void
	{
		Assert::assertFalse(
			TimeFormat::isValidTime($format, $timeString),
			sprintf('Expected that time %s given for format %s is invalid, because %s', $timeString, $format, $reason)
		);
	}

	/**
	 * @dataProvider validTimesProvider
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

		Assert::assertInstanceOf(DateTime::class, $time);
		Assert::assertSame(DateTime::createFromFormat(
			$format,
			$timeString,
			new DateTimeZone('UTC')
		)->format($format), $time->format($format));
	}

	public function testCreateDateTimeWithDefaultTimezone(): void
	{
		Assert::assertInstanceOf(DateTime::class, TimeFormat::createDateTimeFromFormat('Y', '2016'));
	}

	/**
	 * @dataProvider invalidTimesProvider
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

			Assert::fail(sprintf('Exception was expected for time %s given for format %s, because %s', $timeString, $format, $reason));
		} catch (\Consistence\Time\InvalidTimeForFormatException $e) {
			Assert::assertSame($timeString, $e->getTimeString());
		}
	}

	/**
	 * @dataProvider validTimesProvider
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

		Assert::assertInstanceOf(DateTimeImmutable::class, $time);
		Assert::assertSame(DateTimeImmutable::createFromFormat(
			$format,
			$timeString,
			new DateTimeZone('UTC')
		)->format($format), $time->format($format));
	}

	public function testCreateDateTimeImmutableWithDefaultTimezone(): void
	{
		Assert::assertInstanceOf(DateTimeImmutable::class, TimeFormat::createDateTimeImmutableFromFormat('Y', '2016'));
	}

	/**
	 * @dataProvider invalidTimesProvider
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

			Assert::fail(sprintf('Exception was expected for time %s given for format %s, because %s', $timeString, $format, $reason));
		} catch (\Consistence\Time\InvalidTimeForFormatException $e) {
			Assert::assertSame($timeString, $e->getTimeString());
		}
	}

}
