<?php

declare(strict_types = 1);

namespace Consistence\Type\ArrayType;

class KeyValuePairTest extends \Consistence\TestCase
{

	public function testConstruct(): void
	{
		$pair = new KeyValuePair(0, 'foo');
		$this->assertInstanceOf(KeyValuePair::class, $pair);
		$this->assertSame(0, $pair->getKey());
		$this->assertSame('foo', $pair->getValue());
	}

	public function testConstructInvalidKey(): void
	{
		$this->expectException(\Consistence\InvalidArgumentTypeException::class);
		$this->expectExceptionMessage('int|string expected');

		new KeyValuePair([], 'foo');
	}

}
