<?php

declare(strict_types = 1);

namespace Consistence\Enum;

class DuplicateValuesEnum extends \Consistence\Enum\Enum
{

	const FOO = 'foo';
	const BAR = 'foo';
	const BAZ = 'baz';

}
