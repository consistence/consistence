<?php

namespace Consistence\Time;

class TimeDoesNotExistException extends \Consistence\PhpException implements \Consistence\Time\InvalidTimeForFormatException
{

	/** @var string */
	private $timeString;

	/**
	 * @param string $timeString
	 * @param \Exception|null $previous
	 */
	public function __construct($timeString, \Exception $previous = null)
	{
		parent::__construct(
			sprintf('Time given in "%s" does not exist', $timeString),
			$previous
		);
		$this->timeString = $timeString;
	}

	/**
	 * @return string
	 */
	public function getTimeString()
	{
		return $this->timeString;
	}

}
