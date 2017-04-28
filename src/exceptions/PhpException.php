<?php

declare(strict_types = 1);

namespace Consistence;

class PhpException extends \Exception
{

	use \Consistence\Type\ObjectMixinTrait;

	public function __construct(string $message = '', \Throwable $previous = null)
	{
		parent::__construct($message, 0, $previous);
	}

}
