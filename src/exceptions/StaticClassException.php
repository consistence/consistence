<?php

declare(strict_types = 1);

namespace Consistence;

/**
 * Should be thrown in constructors of pure-static classes to prevent creating instances
 */
class StaticClassException extends \Consistence\PhpException
{

}
