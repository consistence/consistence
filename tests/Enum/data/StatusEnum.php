<?php

declare(strict_types = 1);

namespace Consistence\Enum;

class StatusEnum extends \Consistence\Enum\Enum
{

	const DRAFT = 1;
	const REVIEW = 2;
	const PUBLISHED = 3;

	const BAR = 'yy';

	/**
	 * @return string[]
	 */
	protected static function getIgnoredConstantNames()
	{
		return [
			'BAR',
		];
	}

}
