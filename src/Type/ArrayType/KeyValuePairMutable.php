<?php

declare(strict_types = 1);

namespace Consistence\Type\ArrayType;

class KeyValuePairMutable extends \Consistence\Type\ArrayType\KeyValuePair
{

	/**
	 * @param int|string $key
	 * @param mixed $value
	 */
	public function setPair($key, $value)
	{
		parent::setPair($key, $value);
	}

}
