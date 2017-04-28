<?php

declare(strict_types = 1);

namespace Consistence\Type\String;

class Utf8StringTypeTest extends \Consistence\TestCase
{

	public function testStaticConstruct()
	{
		$this->expectException(\Consistence\StaticClassException::class);

		new Utf8StringType();
	}

	public function testUtf8StringLength()
	{
		$this->assertSame(30, Utf8StringType::length('Žluťoučký kůň pěl ďábělské ódy'));
	}

}
