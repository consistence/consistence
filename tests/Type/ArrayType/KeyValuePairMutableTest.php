<?php

declare(strict_types = 1);

namespace Consistence\Type\ArrayType;

class KeyValuePairMutableTest extends \Consistence\TestCase
{

	public function testConstruct()
	{
		$pair = new KeyValuePairMutable(0, 'foo');
		$this->assertSame(0, $pair->getKey());
		$this->assertSame('foo', $pair->getValue());
	}

	public function testSetPair()
	{
		$pair = new KeyValuePairMutable(0, 'foo');
		$pair->setPair(1, 'bar');
		$this->assertSame(1, $pair->getKey());
		$this->assertSame('bar', $pair->getValue());
	}

	public function testSetInvalidKey()
	{
		$pair = new KeyValuePairMutable(0, 'foo');

		$this->expectException(\Consistence\InvalidArgumentTypeException::class);
		$this->expectExceptionMessage('integer|string expected');

		$pair->setPair([], 'foo');
	}

}
