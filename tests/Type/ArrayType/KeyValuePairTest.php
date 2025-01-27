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
		try {
			new KeyValuePair([], 'foo');
			Assert::fail('Exception expected');
		} catch (\Consistence\InvalidArgumentTypeException $e) {
			Assert::assertSame([], $e->getValue());
			Assert::assertSame('array', $e->getValueType());
			Assert::assertSame('int|string', $e->getExpectedTypes());
		}
	}

}
