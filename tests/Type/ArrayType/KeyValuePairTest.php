<?php

declare(strict_types = 1);

namespace Consistence\Type\ArrayType;

use PHPUnit\Framework\Assert;

class KeyValuePairTest extends \PHPUnit\Framework\TestCase
{

	public function testConstruct(): void
	{
		$pair = new KeyValuePair(0, 'foo');
		Assert::assertInstanceOf(KeyValuePair::class, $pair);
		Assert::assertSame(0, $pair->getKey());
		Assert::assertSame('foo', $pair->getValue());
	}

	public function testConstructInvalidKey(): void
	{
		$this->expectException(\Consistence\InvalidArgumentTypeException::class);
		$this->expectExceptionMessage('int|string expected');

		new KeyValuePair([], 'foo');
	}

}
