<?php

namespace Consistence\Time;

use DateTime;
use DateTimeImmutable;

class TimeFormatTest extends \Consistence\TestCase
{

	public function testCreateDateTimeFromTimestamp()
	{
		$original = new DateTime();
		$converted = TimeFormat::createDateTimeFromTimestamp($original->getTimestamp());
		$this->assertSame($original->getTimestamp(), $converted->getTimestamp());
		$this->assertEquals($original->getTimezone(), $converted->getTimezone());
	}

	public function testCreateDateTimeImmutableFromTimestamp()
	{
		$original = new DateTimeImmutable();
		$converted = TimeFormat::createDateTimeImmutableFromTimestamp($original->getTimestamp());
		$this->assertSame($original->getTimestamp(), $converted->getTimestamp());
		$this->assertEquals($original->getTimezone(), $converted->getTimezone());
	}

}
