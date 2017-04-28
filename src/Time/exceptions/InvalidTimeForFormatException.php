<?php

declare(strict_types = 1);

namespace Consistence\Time;

interface InvalidTimeForFormatException
{

	public function getTimeString(): string;

}
