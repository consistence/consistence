<?php

namespace Consistence\Time;

interface InvalidTimeForFormatException extends \Consistence\Time\Exception
{

	/**
	 * @return string
	 */
	public function getTimeString();

}
