<?php

namespace Consistence;

/**
 * Should be thrown in constructors of pure-static classes to prevent creating instances
 */
class StaticClassException extends \Consistence\PhpException implements \Consistence\Exception
{

}
