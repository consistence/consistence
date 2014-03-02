<?php

namespace Consistence\RegExp;

class RegExpTest extends \Consistence\TestCase
{

	public function testStaticConstruct()
	{
		$this->expectException(\Consistence\StaticClassException::class);

		new RegExp();
	}

	public function testMatch()
	{
		$matches = RegExp::match('foo', '~o+~');
		$this->assertCount(1, $matches);
		$this->assertSame('oo', $matches[0]);
	}

	public function testMatchNotFound()
	{
		$this->assertSame([], RegExp::match('foo', '~x+~'));
	}

	public function testMatchOffsetTooBig()
	{
		$this->assertSame([], RegExp::match('foo', '~o+~', 0, 5));
	}

	public function testPregException()
	{
		$this->expectException(\Consistence\RegExp\Exception::class);
		$this->expectExceptionMessage('Backtrack limit was exhausted');

		RegExp::match('foobar foobar foobar', '/(?:\D+|<\d+>)*[!?]/');
	}

	public function testMatches()
	{
		$this->assertTrue(RegExp::matches('foo', '~o+~'));
		$this->assertFalse(RegExp::matches('foo', '~x+~'));
	}

}
