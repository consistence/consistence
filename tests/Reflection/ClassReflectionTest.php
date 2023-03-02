<?php

declare(strict_types = 1);

namespace Consistence\Reflection;

use Generator;
use PHPUnit\Framework\Assert;
use ReflectionClass;

class ClassReflectionTest extends \PHPUnit\Framework\TestCase
{

	public function testStaticConstruct(): void
	{
		$this->expectException(\Consistence\StaticClassException::class);

		new ClassReflection();
	}

	public function testGetDeclaredMethods(): void
	{
		$classReflection = new ReflectionClass(Bar::class);
		$methods = ClassReflection::getDeclaredMethods($classReflection);
		Assert::assertCount(1, $methods);
		Assert::assertSame('barMethod', $methods[0]->name);
	}

	/**
	 * @return mixed[][]|\Generator
	 */
	public function hasDeclaredMethodWithCaseSensitivityDataProvider(): Generator
	{
		yield 'method exists' => [
			'className' => Bar::class,
			'methodName' => 'barMethod',
			'expectedCaseSensitiveResult' => true,
			'expectedCaseInsensitiveResult' => true,
		];
		yield 'method exists but name is in lowercase' => [
			'className' => Bar::class,
			'methodName' => 'barmethod',
			'expectedCaseSensitiveResult' => false,
			'expectedCaseInsensitiveResult' => true,
		];
		yield 'name of private method in parent class' => [
			'className' => Bar::class,
			'methodName' => 'fooMethod',
			'expectedCaseSensitiveResult' => false,
			'expectedCaseInsensitiveResult' => false,
		];
		yield 'method does not exist' => [
			'className' => Bar::class,
			'methodName' => 'xxx',
			'expectedCaseSensitiveResult' => false,
			'expectedCaseInsensitiveResult' => false,
		];
	}

	/**
	 * @return mixed[][]|\Generator
	 */
	public function hasDeclaredMethodDataProvider(): Generator
	{
		foreach ($this->hasDeclaredMethodWithCaseSensitivityDataProvider() as $caseName => $caseData) {
			yield $caseName => [
				'className' => $caseData['className'],
				'methodName' => $caseData['methodName'],
				'expectedHasDeclaredMethod' => $caseData['expectedCaseSensitiveResult'],
			];
		}
	}

	/**
	 * @dataProvider hasDeclaredMethodDataProvider
	 *
	 * @param string $className
	 * @param string $methodName
	 * @param bool $expectedHasDeclaredMethod
	 */
	public function testHasDeclaredMethod(
		string $className,
		string $methodName,
		bool $expectedHasDeclaredMethod
	): void
	{
		$classReflection = new ReflectionClass($className);

		Assert::assertSame($expectedHasDeclaredMethod, ClassReflection::hasDeclaredMethod($classReflection, $methodName));
	}

	/**
	 * @return mixed[][]|\Generator
	 */
	public function hasDeclaredMethodWithCaseSensitiveParameterDataProvider(): Generator
	{
		foreach ($this->hasDeclaredMethodWithCaseSensitivityDataProvider() as $caseName => $caseData) {
			yield $caseName . ' - CASE_SENSITIVE' => [
				'className' => $caseData['className'],
				'methodName' => $caseData['methodName'],
				'caseSensitive' => ClassReflection::CASE_SENSITIVE,
				'expectedHasDeclaredMethod' => $caseData['expectedCaseSensitiveResult'],
			];
			yield $caseName . ' - CASE_INSENSITIVE' => [
				'className' => $caseData['className'],
				'methodName' => $caseData['methodName'],
				'caseSensitive' => ClassReflection::CASE_INSENSITIVE,
				'expectedHasDeclaredMethod' => $caseData['expectedCaseInsensitiveResult'],
			];
		}
	}

	/**
	 * @dataProvider hasDeclaredMethodWithCaseSensitiveParameterDataProvider
	 *
	 * @param string $className
	 * @param string $methodName
	 * @param bool $caseSensitive
	 * @param bool $expectedHasDeclaredMethod
	 */
	public function testHasDeclaredMethodWithCaseSensitiveParameter(
		string $className,
		string $methodName,
		bool $caseSensitive,
		bool $expectedHasDeclaredMethod
	): void
	{
		$classReflection = new ReflectionClass($className);

		Assert::assertSame($expectedHasDeclaredMethod, ClassReflection::hasDeclaredMethod($classReflection, $methodName, $caseSensitive));
	}

	public function testGetDeclaredProperties(): void
	{
		$classReflection = new ReflectionClass(Bar::class);
		$properties = ClassReflection::getDeclaredProperties($classReflection);
		Assert::assertCount(1, $properties);
		Assert::assertSame('bar', $properties[0]->name);
	}

	/**
	 * @return mixed[][]|\Generator
	 */
	public function hasDeclaredPropertyDataProvider(): Generator
	{
		yield 'property exists' => [
			'className' => Bar::class,
			'propertyName' => 'bar',
			'expectedHasDeclaredProperty' => true,
		];
		yield 'name of private property in parent class' => [
			'className' => Bar::class,
			'propertyName' => 'foo',
			'expectedHasDeclaredProperty' => false,
		];
		yield 'property does not exist' => [
			'className' => Bar::class,
			'propertyName' => 'xxx',
			'expectedHasDeclaredProperty' => false,
		];
	}

	/**
	 * @dataProvider hasDeclaredPropertyDataProvider
	 *
	 * @param string $className
	 * @param string $propertyName
	 * @param bool $expectedHasDeclaredProperty
	 */
	public function testHasDeclaredProperty(
		string $className,
		string $propertyName,
		bool $expectedHasDeclaredProperty
	): void
	{
		$classReflection = new ReflectionClass($className);
		Assert::assertSame($expectedHasDeclaredProperty, ClassReflection::hasDeclaredProperty($classReflection, $propertyName));
	}

	public function testGetDeclaredConstants(): void
	{
		$classReflection = new ReflectionClass(Bar::class);
		$constants = ClassReflection::getDeclaredConstants($classReflection);
		Assert::assertCount(1, $constants);
		Assert::assertSame('BAR', $constants[0]->name);
	}

	/**
	 * @return mixed[][]|\Generator
	 */
	public function hasDeclaredConstantDataProvider(): Generator
	{
		yield 'constant exists' => [
			'className' => Bar::class,
			'constantName' => 'BAR',
			'expectedHasDeclaredConstant' => true,
		];
		yield 'name of public constant in parent class' => [
			'className' => Bar::class,
			'constantName' => 'FOO',
			'expectedHasDeclaredConstant' => false,
		];
		yield 'constant does not exist' => [
			'className' => Bar::class,
			'constantName' => 'XXX',
			'expectedHasDeclaredConstant' => false,
		];
	}

	/**
	 * @dataProvider hasDeclaredConstantDataProvider
	 *
	 * @param string $className
	 * @param string $constantName
	 * @param bool $expectedHasDeclaredConstant
	 */
	public function testHasDeclaredConstant(
		string $className,
		string $constantName,
		bool $expectedHasDeclaredConstant
	): void
	{
		$classReflection = new ReflectionClass($className);
		Assert::assertSame($expectedHasDeclaredConstant, ClassReflection::hasDeclaredConstant($classReflection, $constantName));
	}

}
