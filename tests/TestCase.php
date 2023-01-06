<?php

declare(strict_types = 1);

namespace Consistence;

use PHPUnit\Framework\Assert;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{

	/**
	 * This method can be called to provide at least one assertion in test
	 */
	protected function ok(): void
	{
		Assert::assertTrue(true);
	}

}
