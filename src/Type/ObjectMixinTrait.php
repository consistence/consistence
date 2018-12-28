<?php

declare(strict_types = 1);

namespace Consistence\Type;

trait ObjectMixinTrait
{

	/**
	 * Call to undefined method
	 *
	 * @param string $name method name
	 * @param mixed[] $args method args
	 */
	public function __call(string $name, array $args)
	{
		ObjectMixin::magicCall($this, $name);
		// @codeCoverageIgnoreStart
		// return from this method is never invoked (always throws exception)
	}
	// @codeCoverageIgnoreEnd

	/**
	 * Call to undefined static method
	 *
	 * @param string $name method name
	 * @param mixed[] $args method args
	 */
	public static function __callStatic(string $name, array $args)
	{
		ObjectMixin::magicCallStatic(get_called_class(), $name);
		// @codeCoverageIgnoreStart
		// return from this method is never invoked (always throws exception)
	}
	// @codeCoverageIgnoreEnd

	/**
	 * Access to undefined property
	 *
	 * @param string $name property name
	 */
	public function &__get(string $name)
	{
		ObjectMixin::magicGet($this, $name);
		// @codeCoverageIgnoreStart
		// return from this method is never invoked (always throws exception)
	}
	// @codeCoverageIgnoreEnd

	/**
	 * Write to undefined property
	 *
	 * @param string $name property name
	 * @param mixed $value property value
	 */
	public function __set(string $name, $value)
	{
		ObjectMixin::magicSet($this, $name);
		// @codeCoverageIgnoreStart
		// return from this method is never invoked (always throws exception)
	}
	// @codeCoverageIgnoreEnd

	/**
	 * Isset undefined property
	 *
	 * @param string $name property name
	 */
	public function __isset(string $name)
	{
		ObjectMixin::magicIsSet($this, $name);
		// @codeCoverageIgnoreStart
		// return from this method is never invoked (always throws exception)
	}
	// @codeCoverageIgnoreEnd

	/**
	 * Unset undefined property
	 *
	 * @param string $name property name
	 */
	public function __unset(string $name)
	{
		ObjectMixin::magicUnset($this, $name);
		// @codeCoverageIgnoreStart
		// return from this method is never invoked (always throws exception)
	}
	// @codeCoverageIgnoreEnd

}
