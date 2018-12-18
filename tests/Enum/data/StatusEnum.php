<?php

declare(strict_types = 1);

namespace Consistence\Enum;

class StatusEnum extends \Consistence\Enum\Enum
{

	public const DRAFT = 1;
	public const REVIEW = 2;
	public const PUBLISHED = 3;

	public const BAR = 'yy';

	/**
	 * @return string[]
	 */
	protected static function getIgnoredConstantNames(): array
	{
		return [
			'BAR',
		];
	}

}
