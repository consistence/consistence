<?php

namespace Consistence;

class PhpException extends \Exception
{

	use \Consistence\Type\ObjectMixinTrait;

	/**
	 * @param string|null $message
	 * @param \Exception|null $previous
	 */
	public function __construct($message = null, \Exception $previous = null)
	{
		parent::__construct($message, 0, $previous);
	}

}
