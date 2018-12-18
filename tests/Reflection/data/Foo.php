<?php

declare(strict_types = 1);

namespace Consistence\Reflection;

class Foo
{

	public const FOO = 1;

	/** @var mixed */
	private $foo;

	private function fooMethod(): void
	{
		// ...
	}

}
