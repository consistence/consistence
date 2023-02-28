<?php

declare(strict_types = 1);

namespace Consistence\Time;

use DateTime;
use DateTimeImmutable;
use DateTimeZone;
use Generator;
use PHPUnit\Framework\Assert;

class TimeFormatTest extends \PHPUnit\Framework\TestCase
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
	 * @return string[][]|\Generator
	 */
	public function validTimeDataProvider(): Generator
	{
		yield 'hour:minute' => [
			'H:i',
			'02:00',
		];
		yield 'year-month-day' => [
			'Y-m-d',
			'2016-01-02',
		];
		yield 'year-month-day, leap year' => [
			'Y-m-d',
			'2016-02-29',
		];
		yield 'year-month-day without leading zeros' => [
			'Y-n-j',
			'2016-1-2',
		];
		yield 'ISO8601 with +1 hour offset without colon' => [
			TimeFormat::ISO8601,
			'2016-03-21T14:30:32+0100',
		];
		yield 'ISO8601 with +1 hour offset with colon' => [
			TimeFormat::ISO8601_TIMEZONE_WITH_COLON,
			'2016-03-21T14:30:32+01:00',
		];
		yield 'year-month-dayThour:minute:second with Zulu offset' => [
			'Y-m-d\TH:i:se',
			'2016-03-21T14:30:32Z',
		];
		yield 'year-month-day hour:minute:second with timezone identifier separated by whitespace' => [
			'Y-m-d H:i:s e',
			'2016-03-21 14:30:00 Europe/Prague',
		];
		yield 'year-month-day hour:minute:second with timezone abbreviation' => [
			'Y-m-d H:i:s T',
			'2016-03-21 14:30:00 CEST',
		];
		yield 'ISO8601 with -1 hour offset without colon' => [
			TimeFormat::ISO8601,
			'2016-03-21T14:30:32-0100',
		];
		yield 'ISO8601 with microseconds - first microsecond' => [
			TimeFormat::ISO8601_WITH_MICROSECONDS,
			'2016-03-21T14:30:32.000001+0100',
		];
		yield 'ISO8601 with microseconds - first 1/10 of a second' => [
			TimeFormat::ISO8601_WITH_MICROSECONDS,
			'2016-03-21T14:30:32.100000+0100',
		];
		yield 'ISO8601 with microseconds - middle microsecond' => [
			TimeFormat::ISO8601_WITH_MICROSECONDS,
			'2016-03-21T14:30:32.123456+0100',
		];
		yield 'ISO8601 with microseconds - last microsecond' => [
			TimeFormat::ISO8601_WITH_MICROSECONDS,
			'2016-03-21T14:30:32.999999+0100',
		];
	}

	/**
	 * @return string[][]|\Generator
	 */
	public function invalidTimeForFormatDataProvider(): Generator
	{
		yield 'empty string' => [
			TimeFormat::ISO8601,
			'',
		];
		yield 'containing different hour-minute separator' => [
			'H:i',
			'02;30',
		];
		yield 'missing leading zero in hour' => [
			'H:i',
			'2:30',
		];
		yield 'there are missing zeroes at the beginning of day and month' => [
			'Y-m-d',
			'2016-1-2',
		];
		yield '`:` is missing as timezone hour:minute separator' => [
			TimeFormat::ISO8601_TIMEZONE_WITH_COLON,
			'2016-03-21T14:30:32+0100',
		];
		yield 'microseconds must have 6 digits, there are too few' => [
			's.u',
			'25.2',
		];
		yield 'microseconds must have 6 digits, there are too many' => [
			's.u',
			'25.1234567',
		];
	}

	/**
	 * @return string[][]|\Generator
	 */
	public function nonExistingTimeDataProvider(): Generator
	{
		yield 'there is no 25th hour in the day' => [
			'H:i',
			'25:00',
		];
		yield 'this day does not exist when not in leap year' => [
			'Y-m-d',
			'2015-02-29',
		];
		yield 'this time does not exist, the time is moving to DST, skipping from 2:00 to 3:00' => [
			'Y-m-d H:i:s e',
			'2016-03-27 02:30:00 Europe/Prague',
		];
	}

	/**
	 * @return string[][]|\Generator
	 */
	public function invalidTimeDataProvider(): Generator
	{
		foreach ($this->invalidTimeForFormatDataProvider() as $caseName => $caseData) {
			yield $caseName => $caseData;
		}

		foreach ($this->nonExistingTimeDataProvider() as $caseName => $caseData) {
			yield $caseName => $caseData;
		}
	}

	/**
	 * @dataProvider validTimeDataProvider
	 *
	 * @param string $format
	 * @param string $timeString
	 */
	public function testCheckValidTimes(string $format, string $timeString): void
	{
		$this->expectNotToPerformAssertions();

		TimeFormat::checkTime($format, $timeString);
	}

	/**
	 * @dataProvider invalidTimeForFormatDataProvider
	 *
	 * @param string $format
	 * @param string $timeString
	 */
	public function testCheckInvalidTimes(string $format, string $timeString): void
	{
		try {
			TimeFormat::checkTime($format, $timeString);
			Assert::fail(sprintf('Exception was expected for time %s given for format %s', $timeString, $format));
		} catch (\Consistence\Time\TimeDoesNotMatchFormatException $e) {
			Assert::assertSame($format, $e->getFormat());
			Assert::assertSame($timeString, $e->getTimeString());
		}
	}

	/**
	 * @dataProvider nonExistingTimeDataProvider
	 *
	 * @param string $format
	 * @param string $timeString
	 */
	public function testCheckNonExistingTimes(string $format, string $timeString): void
	{
		try {
			TimeFormat::checkTime($format, $timeString);
			Assert::fail(sprintf('Exception was expected for time %s given for format %s', $timeString, $format));
		} catch (\Consistence\Time\TimeDoesNotExistException $e) {
			Assert::assertSame($timeString, $e->getTimeString());
		}
	}

	/**
	 * @dataProvider validTimeDataProvider
	 *
	 * @param string $format
	 * @param string $timeString
	 */
	public function testValidTimes(string $format, string $timeString): void
	{
		Assert::assertTrue(TimeFormat::isValidTime($format, $timeString));
	}

	/**
	 * @dataProvider invalidTimeDataProvider
	 *
	 * @param string $format
	 * @param string $timeString
	 */
	public function testInvalidTimes(string $format, string $timeString): void
	{
		Assert::assertFalse(
			TimeFormat::isValidTime($format, $timeString),
			sprintf('Expected that time %s given for format %s is invalid', $timeString, $format)
		);
	}

	/**
	 * @dataProvider validTimeDataProvider
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
	 * @dataProvider invalidTimeDataProvider
	 *
	 * @param string $format
	 * @param string $timeString
	 */
	public function testCreateInvalidDateTime(string $format, string $timeString): void
	{
		try {
			TimeFormat::createDateTimeFromFormat(
				$format,
				$timeString,
				new DateTimeZone('UTC')
			);

			Assert::fail(sprintf('Exception was expected for time %s given for format %s', $timeString, $format));
		} catch (\Consistence\Time\InvalidTimeForFormatException $e) {
			Assert::assertSame($timeString, $e->getTimeString());
		}
	}

	/**
	 * @dataProvider validTimeDataProvider
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
	 * @dataProvider invalidTimeDataProvider
	 *
	 * @param string $format
	 * @param string $timeString
	 */
	public function testCreateInvalidDateTimeImmutable(string $format, string $timeString): void
	{
		try {
			TimeFormat::createDateTimeImmutableFromFormat(
				$format,
				$timeString,
				new DateTimeZone('UTC')
			);

			Assert::fail(sprintf('Exception was expected for time %s given for format %s', $timeString, $format));
		} catch (\Consistence\Time\InvalidTimeForFormatException $e) {
			Assert::assertSame($timeString, $e->getTimeString());
		}
	}

}
