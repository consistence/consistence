<?php

declare(strict_types = 1);

namespace Consistence\Enum;

class DuplicateValuesEnum extends \Consistence\Enum\Enum
{

	public const FOO = 'foo';
	public const BAR = 'foo';
	public const BAZ = 'baz';

}
