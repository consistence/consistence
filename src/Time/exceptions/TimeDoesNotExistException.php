<?php

declare(strict_types = 1);

namespace Consistence\Time;

class TimeDoesNotExistException extends \Consistence\PhpException implements \Consistence\Time\InvalidTimeForFormatException
{

	/** @var string */
	private $timeString;

	public function __construct(string $timeString, \Throwable $previous = null)
	{
		parent::__construct(
			sprintf('Time given in "%s" does not exist', $timeString),
			$previous
		);
		$this->timeString = $timeString;
	}

	public function getTimeString(): string
	{
		return $this->timeString;
	}

}
