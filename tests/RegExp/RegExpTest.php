<?php

declare(strict_types = 1);

namespace Consistence\RegExp;

use PHPUnit\Framework\Assert;

class RegExpTest extends \PHPUnit\Framework\TestCase
{

	public function testStaticConstruct(): void
	{
		$this->expectException(\Consistence\StaticClassException::class);

		new RegExp();
	}

	public function testMatch(): void
	{
		$matches = RegExp::match('foo', '~o+~');
		Assert::assertCount(1, $matches);
		Assert::assertSame('oo', $matches[0]);
	}

	public function testMatchNotFound(): void
	{
		Assert::assertSame([], RegExp::match('foo', '~x+~'));
	}

	public function testMatchOffsetTooBig(): void
	{
		Assert::assertSame([], RegExp::match('foo', '~o+~', 0, 5));
	}

	public function testPregException(): void
	{
		$this->expectException(\Consistence\RegExp\Exception::class);
		$this->expectExceptionMessage('Backtrack limit was exhausted');

		RegExp::match('foobar foobar foobar', '/(?:\D+|<\d+>)*[!?]/');
	}

	public function testMatches(): void
	{
		Assert::assertTrue(RegExp::matches('foo', '~o+~'));
		Assert::assertFalse(RegExp::matches('foo', '~x+~'));
	}

}
