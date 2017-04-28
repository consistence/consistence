<?php

declare(strict_types = 1);

namespace Consistence\Reflection;

class Bar extends \Consistence\Reflection\Foo
{

	const BAR = 1;

	/** @var mixed */
	private $bar;

	private function barMethod()
	{
		// ...
	}

}
