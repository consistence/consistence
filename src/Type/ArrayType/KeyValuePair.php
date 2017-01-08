<?php

namespace Consistence\Type\ArrayType;

use Consistence\Type\Type;

class KeyValuePair extends \Consistence\ObjectPrototype
{

	/** @var integer|string */
	private $key;

	/** @var mixed */
	private $value;

	/**
	 * @param integer|string $key
	 * @param mixed $value
	 */
	public function __construct($key, $value)
	{
		$this->setPair($key, $value);
	}

	/**
	 * @param integer|string $key
	 * @param mixed $value
	 */
	protected function setPair($key, $value)
	{
		Type::checkType($key, 'integer|string');
		$this->key = $key;
		$this->value = $value;
	}

	/**
	 * @return integer|string
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
