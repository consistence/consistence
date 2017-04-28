<?php

declare(strict_types = 1);

namespace Consistence\Type;

class ObjectMixin
{

	final public function __construct()
	{
		throw new \Consistence\StaticClassException();
	}

	/**
	 * Call to undefined method
	 *
	 * @param object $object
	 * @param string $name method name
	 */
	public static function magicCall($object, string $name)
	{
		Type::checkType($object, 'object');

		throw new \Consistence\UndefinedMethodException(get_class($object), $name);
	}

	/**
	 * Call to undefined static method
	 *
	 * @param string $class
	 * @param string $name method name
	 */
	public static function magicCallStatic(string $class, string $name)
	{
		throw new \Consistence\UndefinedMethodException($class, $name);
	}

	/**
	 * Access to undefined property
	 *
	 * @param object $object
	 * @param string $name property name
	 */
	public static function magicGet($object, string $name)
	{
		Type::checkType($object, 'object');

		throw new \Consistence\UndefinedPropertyException(get_class($object), $name);
	}

	/**
	 * Write to undefined property
	 *
	 * @param object $object
	 * @param string $name property name
	 */
	public static function magicSet($object, string $name)
	{
		Type::checkType($object, 'object');

		throw new \Consistence\UndefinedPropertyException(get_class($object), $name);
	}

	/**
	 * Isset undefined property
	 *
	 * @param object $object
	 * @param string $name property name
	 */
	public static function magicIsSet($object, string $name)
	{
		Type::checkType($object, 'object');

		throw new \Consistence\UndefinedPropertyException(get_class($object), $name);
	}

	/**
	 * Unset undefined property
	 *
	 * @param object $object
	 * @param string $name property name
	 */
	public static function magicUnset($object, string $name)
	{
		Type::checkType($object, 'object');

		throw new \Consistence\UndefinedPropertyException(get_class($object), $name);
	}

}
