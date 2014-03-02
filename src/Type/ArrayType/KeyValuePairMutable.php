<?php

namespace Consistence\Type\ArrayType;

class KeyValuePairMutable extends \Consistence\Type\ArrayType\KeyValuePair
{

	/**
	 * @param integer|string $key
	 * @param mixed $value
	 */
	public function setPair($key, $value)
	{
		parent::setPair($key, $value);
	}

}
