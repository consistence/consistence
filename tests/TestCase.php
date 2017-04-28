<?php

declare(strict_types = 1);

namespace Consistence;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{

	/**
	 * This method can be called to provide at least one assertion in test
	 */
	protected function ok()
	{
		$this->assertTrue(true);
	}

}
