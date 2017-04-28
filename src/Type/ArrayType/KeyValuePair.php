<?php

declare(strict_types = 1);

namespace Consistence\Type\ArrayType;

use Consistence\Type\Type;

class KeyValuePair extends \Consistence\ObjectPrototype
{

	/** @var int|string */
	private $key;

	/** @var mixed */
	private $value;

	/**
	 * @param int|string $key
	 * @param mixed $value
	 */
	public function __construct($key, $value)
	{
		$this->setPair($key, $value);
	}

	/**
	 * @param int|string $key
	 * @param mixed $value
	 */
	protected function setPair($key, $value)
	{
		Type::checkType($key, 'int|string');
		$this->key = $key;
		$this->value = $value;
	}

	/**
	 * @return int|string
	 */
	public function getKey()
	{
		return $this->key;
	}

	/**
	 * @return mixed
	 */
	public function getValue()
	{
		return $this->value;
	}

}
