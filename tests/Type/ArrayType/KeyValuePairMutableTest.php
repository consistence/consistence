<?php

declare(strict_types = 1);

namespace Consistence\Type\ArrayType;

use PHPUnit\Framework\Assert;

class KeyValuePairMutableTest extends \Consistence\TestCase
{

	public function testConstruct(): void
	{
		$pair = new KeyValuePairMutable(0, 'foo');
		Assert::assertSame(0, $pair->getKey());
		Assert::assertSame('foo', $pair->getValue());
	}

	public function testSetPair(): void
	{
		$pair = new KeyValuePairMutable(0, 'foo');
		$pair->setPair(1, 'bar');
		Assert::assertSame(1, $pair->getKey());
		Assert::assertSame('bar', $pair->getValue());
	}

	public function testSetInvalidKey(): void
	{
		$pair = new KeyValuePairMutable(0, 'foo');

		$this->expectException(\Consistence\InvalidArgumentTypeException::class);
		$this->expectExceptionMessage('int|string expected');

		$pair->setPair([], 'foo');
	}

}
