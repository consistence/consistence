<?php

declare(strict_types = 1);

namespace Consistence\Reflection;

use Consistence\Type\ArrayType\ArrayType;
use ReflectionClass;
use ReflectionClassConstant;
use ReflectionMethod;
use ReflectionProperty;

class ClassReflection extends \Consistence\ObjectPrototype
{

	public const CASE_SENSITIVE = true;
	public const CASE_INSENSITIVE = false;

	final public function __construct()
	{
		throw new \Consistence\StaticClassException();
	}

	/**
	 * Retrieves methods defined only at the same level as given ReflectionClass
	 *
	 * @param \ReflectionClass $classReflection
	 * @return \ReflectionMethod[]
	 */
	public static function getDeclaredMethods(ReflectionClass $classReflection): array
	{
		$methods = $classReflection->getMethods();
		$className = $classReflection->getName();
		return ArrayType::filterValuesByCallback($methods, function (ReflectionMethod $method) use ($className): bool {
			return $method->class === $className;
		});
	}

	/**
	 * Is method of this name defined in this class?
	 *
	 * @param \ReflectionClass $classReflection
	 * @param string $methodName
	 * @param bool $caseSensitive should the comparison be case-sensitive? (php methods are not by default, but you should)
	 * @return bool
	 */
	public static function hasDeclaredMethod(
		ReflectionClass $classReflection,
		string $methodName,
		bool $caseSensitive = self::CASE_SENSITIVE
	): bool
	{
		try {
			$methodReflection = $classReflection->getMethod($methodName);
			return $methodReflection->class === $classReflection->getName()
				&& (!$caseSensitive || $methodReflection->name === $methodName);
		} catch (\ReflectionException $e) {
			return false;
		}
	}

	/**
	 * Retrieves properties defined only at the same level as given ReflectionClass
	 *
	 * @param \ReflectionClass $classReflection
	 * @return \ReflectionProperty[]
	 */
	public static function getDeclaredProperties(ReflectionClass $classReflection): array
	{
		$properties = $classReflection->getProperties();
		$className = $classReflection->getName();
		return ArrayType::filterValuesByCallback($properties, function (ReflectionProperty $property) use ($className): bool {
			return $property->class === $className;
		});
	}

	/**
	 * Is property of this name defined in this class?
	 *
	 * @param \ReflectionClass $classReflection
	 * @param string $propertyName
	 * @return bool
	 */
	public static function hasDeclaredProperty(ReflectionClass $classReflection, string $propertyName): bool
	{
		try {
			return $classReflection->getProperty($propertyName)->class === $classReflection->getName();
		} catch (\ReflectionException $e) {
			return false;
		}
	}

	/**
	 * Retrieves constants defined only at the same level as given ReflectionClass
	 *
	 * @param \ReflectionClass $classReflection
	 * @return \ReflectionClassConstant[]
	 */
	public static function getDeclaredConstants(ReflectionClass $classReflection): array
	{
		$constants = $classReflection->getReflectionConstants();
		$className = $classReflection->getName();
		return ArrayType::filterValuesByCallback(
			$constants,
			function (ReflectionClassConstant $constant) use ($className): bool {
				return $constant->getDeclaringClass()->getName() === $className;
			}
		);
	}

	/**
	 * Is constant of this name defined in this class?
	 *
	 * @param \ReflectionClass $classReflection
	 * @param string $constantName
	 * @return bool
	 */
	public static function hasDeclaredConstant(ReflectionClass $classReflection, string $constantName): bool
	{
		$constantReflection = $classReflection->getReflectionConstant($constantName);
		if ($constantReflection === false) {
			return false;
		}

		return $constantReflection->getDeclaringClass()->getName() === $classReflection->getName();
	}

}
