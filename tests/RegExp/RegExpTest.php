<?php

declare(strict_types = 1);

namespace Consistence\RegExp;

use Generator;
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

	/**
	 * @return mixed[][]|\Generator
	 */
	public function matchesDataProvider(): Generator
	{
		yield 'subject matches pattern' => [
			'subject' => 'foo',
			'pattern' => '~o+~',
			'expectedMatches' => true,
		];
		yield 'subject does not match pattern' => [
			'subject' => 'foo',
			'pattern' => '~x+~',
			'expectedMatches' => false,
		];
	}

	/**
	 * @dataProvider matchesDataProvider
	 *
	 * @param string $subject
	 * @param string $pattern
	 * @param bool $expectedMatches
	 */
	public function testMatches(
		string $subject,
		string $pattern,
		bool $expectedMatches
	): void
	{
		Assert::assertSame($expectedMatches, RegExp::matches($subject, $pattern));
	}

}
