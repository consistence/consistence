<?php

namespace Consistence\Reflection;

use Consistence\Type\ArrayType\ArrayType;

use ReflectionClass;
use ReflectionMethod;

class ClassReflection extends \Consistence\ObjectPrototype
{

	const FILTER_VISIBILITY_NONE = -1;

	final public function __construct()
	{
		throw new \Consistence\StaticClassException();
	}

	/**
	 * Retrieves methods defined only at the same level as given ReflectionClass
	 *
	 * @param \ReflectionClass $classReflection
	 * @param integer $filter
	 * @return \ReflectionMethod[]
	 */
	public static function getDeclaredMethods(ReflectionClass $classReflection, $filter = self::FILTER_VISIBILITY_NONE)
	{
		$methods = $classReflection->getMethods($filter);
		$className = $classReflection->getName();
		return ArrayType::filterValuesByCallback($methods, function (ReflectionMethod $method) use ($className) {
			return $method->class === $className;
		});
	}

	/**
	 * Is method of this name defined in this class?
	 *
	 * @param \ReflectionClass $classReflection
	 * @param string $methodName
	 * @return boolean
	 */
	public static function hasDeclaredMethod(ReflectionClass $classReflection, $methodName)
	{
		try {
			return $classReflection->getMethod($methodName)->class === $classReflection->getName();
		} catch (\ReflectionException $e) {
			return false;
		}
	}

}
