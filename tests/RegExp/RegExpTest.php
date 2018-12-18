<?php

declare(strict_types = 1);

namespace Consistence\RegExp;

class RegExpTest extends \Consistence\TestCase
{

	public function testStaticConstruct(): void
	{
		$this->expectException(\Consistence\StaticClassException::class);

		new RegExp();
	}

	public function testMatch(): void
	{
		$matches = RegExp::match('foo', '~o+~');
		$this->assertCount(1, $matches);
		$this->assertSame('oo', $matches[0]);
	}

	public function testMatchNotFound(): void
	{
		$this->assertSame([], RegExp::match('foo', '~x+~'));
	}

	public function testMatchOffsetTooBig(): void
	{
		$this->assertSame([], RegExp::match('foo', '~o+~', 0, 5));
	}

	public function testPregException(): void
	{
		$this->expectException(\Consistence\RegExp\Exception::class);
		$this->expectExceptionMessage('Backtrack limit was exhausted');

		RegExp::match('foobar foobar foobar', '/(?:\D+|<\d+>)*[!?]/');
	}

	public function testMatches(): void
	{
		$this->assertTrue(RegExp::matches('foo', '~o+~'));
		$this->assertFalse(RegExp::matches('foo', '~x+~'));
	}

}
