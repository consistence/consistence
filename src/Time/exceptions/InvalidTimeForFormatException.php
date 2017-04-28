<?php

declare(strict_types = 1);

namespace Consistence\Time;

interface InvalidTimeForFormatException extends \Consistence\Time\Exception
{

	public function getTimeString(): string;

}
