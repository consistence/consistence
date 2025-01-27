<?php

declare(strict_types = 1);

namespace Consistence\Type\ArrayType;

use PHPUnit\Framework\Assert;

class KeyValuePairMutableTest extends \PHPUnit\Framework\TestCase
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

		try {
			$pair->setPair([], 'foo');
			Assert::fail('Exception expected');
		} catch (\Consistence\InvalidArgumentTypeException $e) {
			Assert::assertSame([], $e->getValue());
			Assert::assertSame('array', $e->getValueType());
			Assert::assertSame('int|string', $e->getExpectedTypes());
		}
	}

}
