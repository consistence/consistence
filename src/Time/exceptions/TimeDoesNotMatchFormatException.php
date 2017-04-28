<?php

declare(strict_types = 1);

namespace Consistence\Time;

class TimeDoesNotMatchFormatException extends \Consistence\PhpException implements \Consistence\Time\InvalidTimeForFormatException
{

	/** @var string */
	private $format;

	/** @var string */
	private $timeString;

	public function __construct(string $format, string $timeString, \Throwable $previous = null)
	{
		parent::__construct(
			sprintf('Time "%s" does not match format "%s"', $timeString, $format),
			$previous
		);
		$this->format = $format;
		$this->timeString = $timeString;
	}

	public function getFormat(): string
	{
		return $this->format;
	}

	public function getTimeString(): string
	{
		return $this->timeString;
	}

}
