<?php

namespace Consistence\Time;

class TimeDoesNotMatchFormatException extends \Consistence\PhpException implements \Consistence\Time\InvalidTimeForFormatException
{

	/** @var string */
	private $format;

	/** @var string */
	private $timeString;

	/**
	 * @param string $format
	 * @param string $timeString
	 * @param \Exception|null $previous
	 */
	public function __construct($format, $timeString, \Exception $previous = null)
	{
		parent::__construct(
			sprintf('Time "%s" does not match format "%s"', $timeString, $format),
			$previous
		);
		$this->format = $format;
		$this->timeString = $timeString;
	}

	/**
	 * @return string
	 */
	public function getFormat()
	{
		return $this->format;
	}

	/**
	 * @return string
	 */
	public function getTimeString()
	{
		return $this->timeString;
	}

}
