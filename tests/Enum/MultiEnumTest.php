<?php

declare(strict_types = 1);

namespace Consistence\Enum;

use Generator;
use PHPUnit\Framework\Assert;

class MultiEnumTest extends \PHPUnit\Framework\TestCase
{

	/**
	 * @return mixed[][]|\Generator
	 */
	public function validCaseDataProvider(): Generator
	{
		yield 'empty RolesEnum' => [
			'multiEnumClassName' => RolesEnum::class,
			'value' => 0,
			'values' => [],
			'singleEnumClassName' => RoleEnum::class,
			'enums' => [],
		];
		yield 'RolesEnum, USER' => [
			'multiEnumClassName' => RolesEnum::class,
			'value' => 1,
			'values' => [
				'USER' => RoleEnum::USER,
			],
			'singleEnumClassName' => RoleEnum::class,
			'enums' => [
				'USER' => RoleEnum::get(RoleEnum::USER),
			],
		];
		yield 'RolesEnum, EMPLOYEE' => [
			'multiEnumClassName' => RolesEnum::class,
			'value' => 2,
			'values' => [
				'EMPLOYEE' => RoleEnum::EMPLOYEE,
			],
			'singleEnumClassName' => RoleEnum::class,
			'enums' => [
				'EMPLOYEE' => RoleEnum::get(RoleEnum::EMPLOYEE),
			],
		];
		yield 'RolesEnum, USER and EMPLOYEE' => [
			'multiEnumClassName' => RolesEnum::class,
			'value' => 3,
			'values' => [
				'USER' => RoleEnum::USER,
				'EMPLOYEE' => RoleEnum::EMPLOYEE,
			],
			'singleEnumClassName' => RoleEnum::class,
			'enums' => [
				'USER' => RoleEnum::get(RoleEnum::USER),
				'EMPLOYEE' => RoleEnum::get(RoleEnum::EMPLOYEE),
			],
		];
		yield 'RolesEnum, ADMIN' => [
			'multiEnumClassName' => RolesEnum::class,
			'value' => 4,
			'values' => [
				'ADMIN' => RoleEnum::ADMIN,
			],
			'singleEnumClassName' => RoleEnum::class,
			'enums' => [
				'ADMIN' => RoleEnum::get(RoleEnum::ADMIN),
			],
		];
		yield 'RolesEnum, USER and ADMIN' => [
			'multiEnumClassName' => RolesEnum::class,
			'value' => 5,
			'values' => [
				'USER' => RoleEnum::USER,
				'ADMIN' => RoleEnum::ADMIN,
			],
			'singleEnumClassName' => RoleEnum::class,
			'enums' => [
				'USER' => RoleEnum::get(RoleEnum::USER),
				'ADMIN' => RoleEnum::get(RoleEnum::ADMIN),
			],
		];
		yield 'RolesEnum, EMPLOYEE and ADMIN' => [
			'multiEnumClassName' => RolesEnum::class,
			'value' => 6,
			'values' => [
				'EMPLOYEE' => RoleEnum::EMPLOYEE,
				'ADMIN' => RoleEnum::ADMIN,
			],
			'singleEnumClassName' => RoleEnum::class,
			'enums' => [
				'EMPLOYEE' => RoleEnum::get(RoleEnum::EMPLOYEE),
				'ADMIN' => RoleEnum::get(RoleEnum::ADMIN),
			],
		];
		yield 'RolesEnum, USER and EMPLOYEE and ADMIN' => [
			'multiEnumClassName' => RolesEnum::class,
			'value' => 7,
			'values' => [
				'USER' => RoleEnum::USER,
				'EMPLOYEE' => RoleEnum::EMPLOYEE,
				'ADMIN' => RoleEnum::ADMIN,
			],
			'singleEnumClassName' => RoleEnum::class,
			'enums' => [
				'USER' => RoleEnum::get(RoleEnum::USER),
				'EMPLOYEE' => RoleEnum::get(RoleEnum::EMPLOYEE),
				'ADMIN' => RoleEnum::get(RoleEnum::ADMIN),
			],
		];
		yield 'RolesEnum, EMPLOYEE as Enum constant' => [
			'multiEnumClassName' => RolesEnum::class,
			'value' => RoleEnum::EMPLOYEE,
			'values' => [
				'EMPLOYEE' => RoleEnum::EMPLOYEE,
			],
			'singleEnumClassName' => RoleEnum::class,
			'enums' => [
				'EMPLOYEE' => RoleEnum::get(RoleEnum::EMPLOYEE),
			],
		];
		yield 'RolesEnum, USER and ADMIN as bitwise OR of Enum constants' => [
			'multiEnumClassName' => RolesEnum::class,
			'value' => RoleEnum::USER | RoleEnum::ADMIN,
			'values' => [
				'USER' => RoleEnum::USER,
				'ADMIN' => RoleEnum::ADMIN,
			],
			'singleEnumClassName' => RoleEnum::class,
			'enums' => [
				'USER' => RoleEnum::get(RoleEnum::USER),
				'ADMIN' => RoleEnum::get(RoleEnum::ADMIN),
			],
		];
	}

	/**
	 * @dataProvider validCaseDataProvider
	 *
	 * @return mixed[][]|\Generator
	 */
	public function validValueDataProvider(): Generator
	{
		foreach ($this->validCaseDataProvider() as $caseName => $caseData) {
			yield $caseName => [
				'multiEnumClassName' => $caseData['multiEnumClassName'],
				'value' => $caseData['value'],
			];
		}
	}

	/**
	 * @dataProvider validValueDataProvider
	 *
	 * @param string $multiEnumClassName
	 * @param mixed $value
	 */
	public function testGet(
		string $multiEnumClassName,
		$value
	): void
	{
		$multiEnum = $multiEnumClassName::get($value);
		Assert::assertInstanceOf($multiEnumClassName, $multiEnum);
	}

	/**
	 * @return mixed[][]|\Generator
	 */
	public function validValuesDataProvider(): Generator
	{
		foreach ($this->validCaseDataProvider() as $caseName => $caseData) {
			yield $caseName => [
				'multiEnumClassName' => $caseData['multiEnumClassName'],
				'values' => array_values($caseData['values']),
			];
		}
	}

	/**
	 * @dataProvider validValuesDataProvider
	 *
	 * @param string $multiEnumClassName
	 * @param mixed[] $values
	 */
	public function testGetMulti(
		string $multiEnumClassName,
		array $values
	): void
	{
		$multiEnum = $multiEnumClassName::getMulti(...$values);
		Assert::assertInstanceOf($multiEnumClassName, $multiEnum);
	}

	/**
	 * @dataProvider validValuesDataProvider
	 *
	 * @param string $multiEnumClassName
	 * @param mixed[] $values
	 */
	public function testGetMultiByArray(
		string $multiEnumClassName,
		array $values
	): void
	{
		$multiEnum = $multiEnumClassName::getMultiByArray($values);
		Assert::assertInstanceOf($multiEnumClassName, $multiEnum);
	}

	/**
	 * @return mixed[][]|\Generator
	 */
	public function getMultiByEnumDataProvider(): Generator
	{
		foreach ($this->validCaseDataProvider() as $caseName => $caseData) {
			if (count($caseData['enums']) === 1) {
				yield $caseName => [
					'multiEnumClassName' => $caseData['multiEnumClassName'],
					'enum' => array_values($caseData['enums'])[0],
				];
			}
		}
	}

	/**
	 * @dataProvider getMultiByEnumDataProvider
	 *
	 * @param string $multiEnumClassName
	 * @param \Consistence\Enum\Enum $enum
	 */
	public function testGetMultiByEnum(
		string $multiEnumClassName,
		Enum $enum
	): void
	{
		$multiEnum = $multiEnumClassName::getMultiByEnum($enum);
		Assert::assertInstanceOf($multiEnumClassName, $multiEnum);
	}

	/**
	 * @return mixed[][]|\Generator
	 */
	public function getMultiByEnumsDataProvider(): Generator
	{
		foreach ($this->validCaseDataProvider() as $caseName => $caseData) {
			yield $caseName => [
				'multiEnumClassName' => $caseData['multiEnumClassName'],
				'enums' => $caseData['enums'],
			];
		}
	}

	/**
	 * @dataProvider getMultiByEnumsDataProvider
	 *
	 * @param string $multiEnumClassName
	 * @param \Consistence\Enum\Enum[] $enums
	 */
	public function testGetMultiByEnums(
		string $multiEnumClassName,
		array $enums
	): void
	{
		$multiEnum = $multiEnumClassName::getMultiByEnums($enums);
		Assert::assertInstanceOf($multiEnumClassName, $multiEnum);
	}

	/**
	 * @dataProvider validValueDataProvider
	 *
	 * @param string $multiEnumClassName
	 * @param mixed $value
	 */
	public function testGetValue(
		string $multiEnumClassName,
		$value
	): void
	{
		$multiEnum = $multiEnumClassName::get($value);
		Assert::assertSame($value, $multiEnum->getValue());
	}

	/**
	 * @return mixed[][]|\Generator
	 */
	public function getMultiValueDataProvider(): Generator
	{
		foreach ($this->validCaseDataProvider() as $caseName => $caseData) {
			yield $caseName => [
				'multiEnumClassName' => $caseData['multiEnumClassName'],
				'values' => array_values($caseData['values']),
				'expectedValue' => $caseData['value'],
			];
		}
	}

	/**
	 * @dataProvider getMultiValueDataProvider
	 *
	 * @param string $multiEnumClassName
	 * @param mixed[] $values
	 * @param mixed $expectedValue
	 */
	public function testGetMultiValue(
		string $multiEnumClassName,
		array $values,
		$expectedValue
	): void
	{
		$multiEnum = $multiEnumClassName::getMulti(...$values);
		Assert::assertSame($expectedValue, $multiEnum->getValue());
	}

	/**
	 * @dataProvider getMultiValueDataProvider
	 *
	 * @param string $multiEnumClassName
	 * @param mixed[] $values
	 * @param mixed $expectedValue
	 */
	public function testGetMultiByArrayValue(
		string $multiEnumClassName,
		array $values,
		$expectedValue
	): void
	{
		$multiEnum = $multiEnumClassName::getMultiByArray($values);
		Assert::assertSame($expectedValue, $multiEnum->getValue());
	}

	/**
	 * @return mixed[][]|\Generator
	 */
	public function getEnumsDataProvider(): Generator
	{
		foreach ($this->validCaseDataProvider() as $caseName => $caseData) {
			yield $caseName => [
				'multiEnumClassName' => $caseData['multiEnumClassName'],
				'values' => array_values($caseData['values']),
				'expectedEnums' => $caseData['enums'],
			];
		}
	}

	/**
	 * @dataProvider getEnumsDataProvider
	 *
	 * @param string $multiEnumClassName
	 * @param mixed[] $values
	 * @param \Consistence\Enum\Enum[] $expectedEnums
	 */
	public function testGetEnums(
		string $multiEnumClassName,
		array $values,
		array $expectedEnums
	): void
	{
		$multiEnum = $multiEnumClassName::getMulti(...$values);
		Assert::assertEquals($expectedEnums, $multiEnum->getEnums());
	}

	/**
	 * @return mixed[][]|\Generator
	 */
	public function iterateThroughEnumsDataProvider(): Generator
	{
		foreach ($this->validCaseDataProvider() as $caseName => $caseData) {
			if (count($caseData['values']) > 0) {
				yield $caseName => [
					'multiEnumClassName' => $caseData['multiEnumClassName'],
					'singleEnumClassName' => $caseData['singleEnumClassName'],
					'values' => array_values($caseData['values']),
				];
			}
		}
	}

	/**
	 * @dataProvider iterateThroughEnumsDataProvider
	 *
	 * @param string $multiEnumClassName
	 * @param string $enumClassName
	 * @param mixed[] $values
	 */
	public function testIterateTroughEnums(
		string $multiEnumClassName,
		string $enumClassName,
		array $values
	): void
	{
		$multiEnum = $multiEnumClassName::getMulti(...$values);
		foreach ($multiEnum as $enum) {
			Assert::assertInstanceOf($enumClassName, $enum);
		}
	}

	/**
	 * @return mixed[][]|\Generator
	 */
	public function getValuesDataProvider(): Generator
	{
		foreach ($this->validCaseDataProvider() as $caseName => $caseData) {
			yield $caseName => [
				'multiEnumClassName' => $caseData['multiEnumClassName'],
				'values' => array_values($caseData['values']),
				'expectedValues' => $caseData['values'],
			];
		}
	}

	/**
	 * @dataProvider getValuesDataProvider
	 *
	 * @param string $multiEnumClassName
	 * @param mixed[] $values
	 * @param mixed[] $expectedValues
	 */
	public function testGetValues(
		string $multiEnumClassName,
		array $values,
		array $expectedValues
	): void
	{
		$userAndAdmin = $multiEnumClassName::getMulti(...$values);
		Assert::assertEquals($expectedValues, $userAndAdmin->getValues());
	}

	/**
	 * @return mixed[][]|\Generator
	 */
	public function compareDataProvider(): Generator
	{
		yield 'equal multiEnums, values in same order' => [
			'multiEnum1' => RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN),
			'multiEnum2' => RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN),
			'equals' => true,
		];

		yield 'equal multiEnums, values in different order' => [
			'multiEnum1' => RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN),
			'multiEnum2' => RolesEnum::getMulti(RoleEnum::ADMIN, RoleEnum::USER),
			'equals' => true,
		];
		yield 'not equal multiEnums' => [
			'multiEnum1' => RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN),
			'multiEnum2' => RolesEnum::getMulti(RoleEnum::USER),
			'equals' => false,
		];
	}

	/**
	 * @dataProvider compareDataProvider
	 *
	 * @param \Consistence\Enum\MultiEnum $multiEnum1
	 * @param \Consistence\Enum\MultiEnum $multiEnum2
	 * @param bool $equals
	 */
	public function testSameInstances(
		MultiEnum $multiEnum1,
		MultiEnum $multiEnum2,
		bool $equals
	): void
	{
		Assert::assertSame($equals, $multiEnum1 === $multiEnum2);
	}

	/**
	 * @dataProvider compareDataProvider
	 *
	 * @param \Consistence\Enum\MultiEnum $multiEnum1
	 * @param \Consistence\Enum\MultiEnum $multiEnum2
	 * @param bool $equals
	 */
	public function testEquals(
		MultiEnum $multiEnum1,
		MultiEnum $multiEnum2,
		bool $equals
	): void
	{
		Assert::assertSame($equals, $multiEnum1->equals($multiEnum2));
	}

	/**
	 * @dataProvider compareDataProvider
	 *
	 * @param \Consistence\Enum\MultiEnum $multiEnum1
	 * @param \Consistence\Enum\MultiEnum $multiEnum2
	 * @param bool $equals
	 */
	public function testEqualsValue(
		MultiEnum $multiEnum1,
		MultiEnum $multiEnum2,
		bool $equals
	): void
	{
		Assert::assertSame($equals, $multiEnum1->equalsValue($multiEnum2->getValue()));
	}

	public function testGetAvailableValues(): void
	{
		Assert::assertEquals([
			'USER' => RoleEnum::USER,
			'EMPLOYEE' => RoleEnum::EMPLOYEE,
			'ADMIN' => RoleEnum::ADMIN,
		], RolesEnum::getAvailableValues());
	}

	public function testGetAvailableEnums(): void
	{
		Assert::assertEquals([
			'USER' => RolesEnum::get(RoleEnum::USER),
			'EMPLOYEE' => RolesEnum::get(RoleEnum::EMPLOYEE),
			'ADMIN' => RolesEnum::get(RoleEnum::ADMIN),
		], RolesEnum::getAvailableEnums());
	}

	public function testGetNoValue(): void
	{
		$empty = RolesEnum::get(0);
		Assert::assertSame(0, $empty->getValue());
		Assert::assertEquals([], $empty->getValues());
	}

	public function testGetMultiNoValue(): void
	{
		$empty = RolesEnum::getMulti();
		Assert::assertSame(0, $empty->getValue());
		Assert::assertEquals([], $empty->getValues());
	}

	public function testGetMultiByArrayNoValue(): void
	{
		$empty = RolesEnum::getMultiByArray([]);
		Assert::assertSame(0, $empty->getValue());
		Assert::assertEquals([], $empty->getValues());
	}

	/**
	 * @return mixed[][]|\Generator
	 */
	public function invalidValueSingleDataProvider(): Generator
	{
		yield 'negative value' => [
			'multiEnumClassName' => RolesEnum::class,
			'value' => -1,
			'expectedAvailableValues' => [
				'USER' => RoleEnum::USER,
				'EMPLOYEE' => RoleEnum::EMPLOYEE,
				'ADMIN' => RoleEnum::ADMIN,
			],
		];
		yield 'nonexistent Enum value' => [
			'multiEnumClassName' => RolesEnum::class,
			'value' => 8,
			'expectedAvailableValues' => [
				'USER' => RoleEnum::USER,
				'EMPLOYEE' => RoleEnum::EMPLOYEE,
				'ADMIN' => RoleEnum::ADMIN,
			],
		];
	}

	/**
	 * @dataProvider invalidValueSingleDataProvider
	 *
	 * @param string $multiEnumClassName
	 * @param mixed $value
	 * @param mixed[] $expectedAvailableValues
	 */
	public function testGetInvalidValue(
		string $multiEnumClassName,
		$value,
		array $expectedAvailableValues
	): void
	{
		try {
			$multiEnumClassName::get($value);
			Assert::fail('Exception expected');
		} catch (\Consistence\Enum\InvalidEnumValueException $e) {
			Assert::assertSame($value, $e->getValue());
			Assert::assertEquals($expectedAvailableValues, $e->getAvailableValues());
			Assert::assertSame($multiEnumClassName, $e->getEnumClassName());
		}
	}

	/**
	 * @return mixed[][]|\Generator
	 */
	public function invalidValueMultiDataProvider(): Generator
	{
		yield 'single negative value' => [
			'multiEnumClassName' => RolesEnum::class,
			'values' => [-1],
			'expectedInvalidValue' => -1,
			'expectedAvailableValues' => [
				'USER' => RoleEnum::USER,
				'EMPLOYEE' => RoleEnum::EMPLOYEE,
				'ADMIN' => RoleEnum::ADMIN,
			],
		];
		yield 'single nonexistent Enum value' => [
			'multiEnumClassName' => RolesEnum::class,
			'values' => [8],
			'expectedInvalidValue' => 8,
			'expectedAvailableValues' => [
				'USER' => RoleEnum::USER,
				'EMPLOYEE' => RoleEnum::EMPLOYEE,
				'ADMIN' => RoleEnum::ADMIN,
			],
		];
	}

	/**
	 * @dataProvider invalidValueMultiDataProvider
	 *
	 * @param string $multiEnumClassName
	 * @param mixed[] $values
	 * @param mixed $expectedInvalidValue
	 * @param mixed[] $expectedAvailableValues
	 */
	public function testGetMultiInvalidValue(
		string $multiEnumClassName,
		array $values,
		$expectedInvalidValue,
		array $expectedAvailableValues
	): void
	{
		try {
			$multiEnumClassName::getMulti(...$values);
			Assert::fail('Exception expected');
		} catch (\Consistence\Enum\InvalidEnumValueException $e) {
			Assert::assertSame($expectedInvalidValue, $e->getValue());
			Assert::assertEquals($expectedAvailableValues, $e->getAvailableValues());
			Assert::assertSame($multiEnumClassName, $e->getEnumClassName());
		}
	}

	/**
	 * @return mixed[][]|\Generator
	 */
	public function comparingDifferentEnumsDataProvider(): Generator
	{
		yield 'two different MultiEnum classes' => [
			'enum1' => RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN),
			'enum2' => FooEnum::get(FooEnum::FOO),
		];
		yield 'MultiEnum class and Enum class' => [
			'enum1' => RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN),
			'enum2' => RoleEnum::get(RoleEnum::USER),
		];
	}

	/**
	 * @dataProvider comparingDifferentEnumsDataProvider
	 *
	 * @param \Consistence\Enum\Enum $enum1
	 * @param \Consistence\Enum\Enum $enum2
	 */
	public function testComparingDifferentEnums(
		Enum $enum1,
		Enum $enum2
	): void
	{
		try {
			$enum1->equals($enum2);

			Assert::fail('Exception expected');
		} catch (\Consistence\Enum\OperationSupportedOnlyForSameEnumException $e) {
			Assert::assertSame($enum1, $e->getExpected());
			Assert::assertSame($enum2, $e->getGiven());
		}
	}

	public function testNoSingleEnumDefinition(): void
	{
		try {
			FooEnum::getMulti(FooEnum::FOO)->getEnums();
			Assert::fail('Exception expected');
		} catch (\Consistence\Enum\NoSingleEnumSpecifiedException $e) {
			Assert::assertSame(FooEnum::class, $e->getClass());
		}
	}

	/**
	 * @return mixed[][]|\Generator
	 */
	public function containsDataProvider(): Generator
	{
		yield 'first of two contained values' => [
			'existingMultiEnum' => RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN),
			'operandMultiEnum' => RolesEnum::get(RoleEnum::USER),
			'contains' => true,
		];
		yield 'second of two contained values' => [
			'existingMultiEnum' => RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN),
			'operandMultiEnum' => RolesEnum::get(RoleEnum::ADMIN),
			'contains' => true,
		];
		yield 'both of two contained values' => [
			'existingMultiEnum' => RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN),
			'operandMultiEnum' => RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN),
			'contains' => true,
		];
		yield 'one different value' => [
			'existingMultiEnum' => RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN),
			'operandMultiEnum' => RolesEnum::get(RoleEnum::EMPLOYEE),
			'contains' => false,
		];
		yield 'one different and one contained value' => [
			'existingMultiEnum' => RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN),
			'operandMultiEnum' => RolesEnum::getMulti(RoleEnum::USER, RoleEnum::EMPLOYEE),
			'contains' => false,
		];
	}

	/**
	 * @dataProvider containsDataProvider
	 *
	 * @param \Consistence\Enum\MultiEnum $existingMultiEnum
	 * @param \Consistence\Enum\MultiEnum $operandMultiEnum
	 * @param bool $contains
	 */
	public function testContains(
		MultiEnum $existingMultiEnum,
		MultiEnum $operandMultiEnum,
		bool $contains
	): void
	{
		Assert::assertSame($contains, $existingMultiEnum->contains($operandMultiEnum));
	}

	public function testContainsDifferentEnum(): void
	{
		$userAndAdmin = RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN);
		$foo = FooEnum::get(FooEnum::FOO);

		try {
			$userAndAdmin->contains($foo);
			Assert::fail('Exception expected');
		} catch (\Consistence\Enum\OperationSupportedOnlyForSameEnumException $e) {
			Assert::assertSame($userAndAdmin, $e->getExpected());
			Assert::assertSame($foo, $e->getGiven());
		}
	}

	/**
	 * @return mixed[][]|\Generator
	 */
	public function containsEnumDataProvider(): Generator
	{
		yield 'first of two contained values' => [
			'existingMultiEnum' => RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN),
			'operandEnum' => RoleEnum::get(RoleEnum::USER),
			'contains' => true,
		];
		yield 'second of two contained values' => [
			'existingMultiEnum' => RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN),
			'operandEnum' => RoleEnum::get(RoleEnum::ADMIN),
			'contains' => true,
		];
		yield 'different value' => [
			'existingMultiEnum' => RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN),
			'operandEnum' => RoleEnum::get(RoleEnum::EMPLOYEE),
			'contains' => false,
		];
	}

	/**
	 * @dataProvider containsEnumDataProvider
	 *
	 * @param \Consistence\Enum\MultiEnum $existingMultiEnum
	 * @param \Consistence\Enum\Enum $operandEnum
	 * @param bool $contains
	 */
	public function testContainsEnum(
		MultiEnum $existingMultiEnum,
		Enum $operandEnum,
		bool $contains
	): void
	{
		Assert::assertSame($contains, $existingMultiEnum->containsEnum($operandEnum));
	}

	public function testContainsEnumSingleEnumNotDefined(): void
	{
		try {
			FooEnum::getMulti(FooEnum::FOO)->containsEnum(FooEnum::get(FooEnum::FOO));
			Assert::fail('Exception expected');
		} catch (\Consistence\Enum\NoSingleEnumSpecifiedException $e) {
			Assert::assertSame(FooEnum::class, $e->getClass());
		}
	}

	public function testContainsEnumDifferentEnum(): void
	{
		$userAndAdmin = RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN);

		try {
			$userAndAdmin->containsEnum(FooEnum::get(FooEnum::FOO));
			Assert::fail('Exception expected');
		} catch (\Consistence\InvalidArgumentTypeException $e) {
			Assert::assertSame(FooEnum::get(FooEnum::FOO), $e->getValue());
			Assert::assertSame(FooEnum::class, $e->getValueType());
			Assert::assertSame(RoleEnum::class, $e->getExpectedTypes());
		}
	}

	/**
	 * @return mixed[][]|\Generator
	 */
	public function containsValueDataProvider(): Generator
	{
		foreach ($this->containsEnumDataProvider() as $caseName => $caseData) {
			yield $caseName => [
				'existingMultiEnum' => $caseData['existingMultiEnum'],
				'value' => $caseData['operandEnum']->getValue(),
				'contains' => $caseData['contains'],
			];
		}
	}

	/**
	 * @dataProvider containsValueDataProvider
	 *
	 * @param \Consistence\Enum\MultiEnum $existingMultiEnum
	 * @param int $value
	 * @param bool $contains
	 */
	public function testContainsValue(
		MultiEnum $existingMultiEnum,
		int $value,
		bool $contains
	): void
	{
		Assert::assertSame($contains, $existingMultiEnum->containsValue($value));
	}

	public function testContainsInvalidValue(): void
	{
		$userAndAdmin = RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN);

		try {
			$userAndAdmin->containsValue(-1);
			Assert::fail('Exception expected');
		} catch (\Consistence\Enum\InvalidEnumValueException $e) {
			Assert::assertSame(-1, $e->getValue());
			Assert::assertEquals([
				'USER' => RoleEnum::USER,
				'EMPLOYEE' => RoleEnum::EMPLOYEE,
				'ADMIN' => RoleEnum::ADMIN,
			], $e->getAvailableValues());
			Assert::assertSame(RolesEnum::class, $e->getEnumClassName());
		}
	}

	/**
	 * @return mixed[][]|\Generator
	 */
	public function isEmptyDataProvider(): Generator
	{
		yield 'empty RolesEnum created using getMulti()' => [
			'multiEnum' => RolesEnum::getMulti(),
			'empty' => true,
		];
		yield 'empty RolesEnum created using get(0)' => [
			'multiEnum' => RolesEnum::get(0),
			'empty' => true,
		];
		yield 'empty RolesEnum created using getMultiByArray([])' => [
			'multiEnum' => RolesEnum::getMultiByArray([]),
			'empty' => true,
		];
		yield 'non-empty RolesEnum' => [
			'multiEnum' => RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN),
			'empty' => false,
		];
	}

	/**
	 * @dataProvider isEmptyDataProvider
	 *
	 * @param \Consistence\Enum\RolesEnum $multiEnum
	 * @param bool $empty
	 */
	public function testIsEmpty(
		MultiEnum $multiEnum,
		bool $empty
	): void
	{
		Assert::assertSame($empty, $multiEnum->isEmpty());
	}

	/**
	 * @return mixed[][]|\Generator
	 */
	public function enumOperationsDataProvider(): Generator
	{
		yield 'operand MultiEnum with one contained Enum' => [
			'existingMultiEnum' => RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN),
			'operandMultiEnum' => RolesEnum::get(RoleEnum::USER),
			'operandEnum' => RoleEnum::get(RoleEnum::USER),
			'expectedAddResult' => RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN),
			'expectedRemoveResult' => RolesEnum::getMulti(RoleEnum::ADMIN),
			'expectedIntersectResult' => RolesEnum::getMulti(RoleEnum::USER),
		];

		yield 'operand MultiEnum with not contained Enum' => [
			'existingMultiEnum' => RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN),
			'operandMultiEnum' => RolesEnum::get(RoleEnum::EMPLOYEE),
			'operandEnum' => RoleEnum::get(RoleEnum::EMPLOYEE),
			'expectedAddResult' => RolesEnum::getMulti(RoleEnum::USER, RoleEnum::EMPLOYEE, RoleEnum::ADMIN),
			'expectedRemoveResult' => RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN),
			'expectedIntersectResult' => RolesEnum::getMulti(),
		];
	}

	/**
	 * @return mixed[][]|\Generator
	 */
	public function multiEnumOperationsDataProvider(): Generator
	{
		foreach ($this->enumOperationsDataProvider() as $caseName => $caseData) {
			yield $caseName => [
				'existingMultiEnum' => $caseData['existingMultiEnum'],
				'operandMultiEnum' => $caseData['operandMultiEnum'],
				'expectedAddResult' => $caseData['expectedAddResult'],
				'expectedRemoveResult' => $caseData['expectedRemoveResult'],
				'expectedIntersectResult' => $caseData['expectedIntersectResult'],
			];
		}

		yield 'operand MultiEnum with both of contained Enums' => [
			'existingMultiEnum' => RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN),
			'operandMultiEnum' => RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN),
			'expectedAddResult' => RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN),
			'expectedRemoveResult' => RolesEnum::getMulti(),
			'expectedIntersectResult' => RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN),
		];

		yield 'operand MultiEnum with one contained Enum and one not contained Enum' => [
			'existingMultiEnum' => RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN),
			'operandMultiEnum' => RolesEnum::getMulti(RoleEnum::USER, RoleEnum::EMPLOYEE),
			'expectedAddResult' => RolesEnum::getMulti(RoleEnum::USER, RoleEnum::EMPLOYEE, RoleEnum::ADMIN),
			'expectedRemoveResult' => RolesEnum::getMulti(RoleEnum::ADMIN),
			'expectedIntersectResult' => RolesEnum::getMulti(RoleEnum::USER),
		];

		yield 'empty operand MultiEnum' => [
			'existingMultiEnum' => RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN),
			'operandMultiEnum' => RolesEnum::getMulti(),
			'expectedAddResult' => RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN),
			'expectedRemoveResult' => RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN),
			'expectedIntersectResult' => RolesEnum::getMulti(),
		];
	}

	/**
	 * @return mixed[][]|\Generator
	 */
	public function addDataProvider(): Generator
	{
		foreach ($this->multiEnumOperationsDataProvider() as $caseName => $caseData) {
			yield $caseName => [
				'existingMultiEnum' => $caseData['existingMultiEnum'],
				'operandMultiEnum' => $caseData['operandMultiEnum'],
				'expectedMultiEnum' => $caseData['expectedAddResult'],
			];
		}
	}

	/**
	 * @dataProvider addDataProvider
	 *
	 * @param \Consistence\Enum\MultiEnum $existingMultiEnum
	 * @param \Consistence\Enum\MultiEnum $operandMultiEnum
	 * @param \Consistence\Enum\MultiEnum $expectedMultiEnum
	 */
	public function testAdd(
		MultiEnum $existingMultiEnum,
		MultiEnum $operandMultiEnum,
		MultiEnum $expectedMultiEnum
	): void
	{
		Assert::assertSame($expectedMultiEnum, $existingMultiEnum->add($operandMultiEnum));
	}

	public function testAddDifferentEnum(): void
	{
		$userAndAdmin = RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN);
		$foo = FooEnum::get(FooEnum::FOO);

		try {
			$userAndAdmin->add($foo);
			Assert::fail('Exception expected');
		} catch (\Consistence\Enum\OperationSupportedOnlyForSameEnumException $e) {
			Assert::assertSame($userAndAdmin, $e->getExpected());
			Assert::assertSame($foo, $e->getGiven());
		}
	}

	/**
	 * @return mixed[][]|\Generator
	 */
	public function addEnumDataProvider(): Generator
	{
		foreach ($this->enumOperationsDataProvider() as $caseName => $caseData) {
			yield $caseName => [
				'existingMultiEnum' => $caseData['existingMultiEnum'],
				'operandEnum' => $caseData['operandEnum'],
				'expectedMultiEnum' => $caseData['expectedAddResult'],
			];
		}
	}

	/**
	 * @dataProvider addEnumDataProvider
	 *
	 * @param \Consistence\Enum\MultiEnum $existingMultiEnum
	 * @param \Consistence\Enum\Enum $operandEnum
	 * @param \Consistence\Enum\MultiEnum $expectedMultiEnum
	 */
	public function testAddEnum(
		MultiEnum $existingMultiEnum,
		Enum $operandEnum,
		MultiEnum $expectedMultiEnum
	): void
	{
		Assert::assertSame($expectedMultiEnum, $existingMultiEnum->addEnum($operandEnum));
	}

	public function testAddEnumSingleEnumNotDefined(): void
	{
		try {
			FooEnum::getMulti(FooEnum::FOO)->addEnum(FooEnum::get(FooEnum::FOO));
			Assert::fail('Exception expected');
		} catch (\Consistence\Enum\NoSingleEnumSpecifiedException $e) {
			Assert::assertSame(FooEnum::class, $e->getClass());
		}
	}

	public function testAddEnumDifferentEnum(): void
	{
		$userAndAdmin = RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN);
		$foo = FooEnum::get(FooEnum::FOO);

		try {
			$userAndAdmin->addEnum($foo);
			Assert::fail('Exception expected');
		} catch (\Consistence\InvalidArgumentTypeException $e) {
			Assert::assertSame($foo, $e->getValue());
			Assert::assertSame(FooEnum::class, $e->getValueType());
			Assert::assertSame(RoleEnum::class, $e->getExpectedTypes());
		}
	}

	/**
	 * @return mixed[][]|\Generator
	 */
	public function addValueDataProvider(): Generator
	{
		foreach ($this->enumOperationsDataProvider() as $caseName => $caseData) {
			yield $caseName => [
				'existingMultiEnum' => $caseData['existingMultiEnum'],
				'operandValue' => $caseData['operandEnum']->getValue(),
				'expectedMultiEnum' => $caseData['expectedAddResult'],
			];
		}
	}

	/**
	 * @dataProvider addValueDataProvider
	 *
	 * @param \Consistence\Enum\MultiEnum $existingMultiEnum
	 * @param int $operandValue
	 * @param \Consistence\Enum\MultiEnum $expectedMultiEnum
	 */
	public function testAddValue(
		MultiEnum $existingMultiEnum,
		int $operandValue,
		MultiEnum $expectedMultiEnum
	): void
	{
		Assert::assertSame($expectedMultiEnum, $existingMultiEnum->addValue($operandValue));
	}

	public function testAddInvalidValue(): void
	{
		$userAndAdmin = RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN);

		try {
			$userAndAdmin->addValue(RoleEnum::USER | RoleEnum::ADMIN);
			Assert::fail('Exception expected');
		} catch (\Consistence\Enum\InvalidEnumValueException $e) {
			Assert::assertSame(5, $e->getValue());
			Assert::assertEquals([
				'USER' => RoleEnum::USER,
				'EMPLOYEE' => RoleEnum::EMPLOYEE,
				'ADMIN' => RoleEnum::ADMIN,
			], $e->getAvailableValues());
			Assert::assertSame(RolesEnum::class, $e->getEnumClassName());
		}
	}

	/**
	 * @return mixed[][]|\Generator
	 */
	public function removeDataProvider(): Generator
	{
		foreach ($this->multiEnumOperationsDataProvider() as $caseName => $caseData) {
			yield $caseName => [
				'existingMultiEnum' => $caseData['existingMultiEnum'],
				'operandMultiEnum' => $caseData['operandMultiEnum'],
				'expectedMultiEnum' => $caseData['expectedRemoveResult'],
			];
		}
	}

	/**
	 * @dataProvider removeDataProvider
	 *
	 * @param \Consistence\Enum\MultiEnum $existingMultiEnum
	 * @param \Consistence\Enum\MultiEnum $operandMultiEnum
	 * @param \Consistence\Enum\MultiEnum $expectedMultiEnum
	 */
	public function testRemove(
		MultiEnum $existingMultiEnum,
		MultiEnum $operandMultiEnum,
		MultiEnum $expectedMultiEnum
	): void
	{
		Assert::assertSame($expectedMultiEnum, $existingMultiEnum->remove($operandMultiEnum));
	}

	public function testRemoveDifferentEnum(): void
	{
		$userAndAdmin = RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN);
		$foo = FooEnum::get(FooEnum::FOO);

		try {
			$userAndAdmin->remove($foo);
			Assert::fail('Exception expected');
		} catch (\Consistence\Enum\OperationSupportedOnlyForSameEnumException $e) {
			Assert::assertSame($userAndAdmin, $e->getExpected());
			Assert::assertSame($foo, $e->getGiven());
		}
	}

	/**
	 * @return mixed[][]|\Generator
	 */
	public function removeEnumDataProvider(): Generator
	{
		foreach ($this->enumOperationsDataProvider() as $caseName => $caseData) {
			yield $caseName => [
				'existingMultiEnum' => $caseData['existingMultiEnum'],
				'operandEnum' => $caseData['operandEnum'],
				'expectedMultiEnum' => $caseData['expectedRemoveResult'],
			];
		}
	}

	/**
	 * @dataProvider removeEnumDataProvider
	 *
	 * @param \Consistence\Enum\MultiEnum $existingMultiEnum
	 * @param \Consistence\Enum\Enum $operandEnum
	 * @param \Consistence\Enum\MultiEnum $expectedMultiEnum
	 */
	public function testRemoveEnum(
		MultiEnum $existingMultiEnum,
		Enum $operandEnum,
		MultiEnum $expectedMultiEnum
	): void
	{
		Assert::assertSame($expectedMultiEnum, $existingMultiEnum->removeEnum($operandEnum));
	}

	public function testRemoveEnumSingleEnumNotDefined(): void
	{
		try {
			FooEnum::getMulti(FooEnum::FOO)->removeEnum(FooEnum::get(FooEnum::FOO));
			Assert::fail('Exception expected');
		} catch (\Consistence\Enum\NoSingleEnumSpecifiedException $e) {
			Assert::assertSame(FooEnum::class, $e->getClass());
		}
	}

	public function testRemoveEnumDifferentEnum(): void
	{
		$userAndAdmin = RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN);
		$foo = FooEnum::get(FooEnum::FOO);

		try {
			$userAndAdmin->removeEnum($foo);
			Assert::fail('Exception expected');
		} catch (\Consistence\InvalidArgumentTypeException $e) {
			Assert::assertSame($foo, $e->getValue());
			Assert::assertSame(FooEnum::class, $e->getValueType());
			Assert::assertSame(RoleEnum::class, $e->getExpectedTypes());
		}
	}

	/**
	 * @return mixed[][]|\Generator
	 */
	public function removeValueDataProvider(): Generator
	{
		foreach ($this->enumOperationsDataProvider() as $caseName => $caseData) {
			yield $caseName => [
				'existingMultiEnum' => $caseData['existingMultiEnum'],
				'operandValue' => $caseData['operandEnum']->getValue(),
				'expectedMultiEnum' => $caseData['expectedRemoveResult'],
			];
		}
	}

	/**
	 * @dataProvider removeValueDataProvider
	 *
	 * @param \Consistence\Enum\MultiEnum $existingMultiEnum
	 * @param int $operandValue
	 * @param \Consistence\Enum\MultiEnum $expectedMultiEnum
	 */
	public function testRemoveValue(
		MultiEnum $existingMultiEnum,
		int $operandValue,
		MultiEnum $expectedMultiEnum
	): void
	{
		Assert::assertSame($expectedMultiEnum, $existingMultiEnum->removeValue($operandValue));
	}

	public function testRemoveInvalidValue(): void
	{
		$userAndAdmin = RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN);

		try {
			$userAndAdmin->removeValue(RoleEnum::USER | RoleEnum::ADMIN);
			Assert::fail('Exception expected');
		} catch (\Consistence\Enum\InvalidEnumValueException $e) {
			Assert::assertSame(5, $e->getValue());
			Assert::assertEquals([
				'USER' => RoleEnum::USER,
				'EMPLOYEE' => RoleEnum::EMPLOYEE,
				'ADMIN' => RoleEnum::ADMIN,
			], $e->getAvailableValues());
			Assert::assertSame(RolesEnum::class, $e->getEnumClassName());
		}
	}

	/**
	 * @return mixed[][]|\Generator
	 */
	public function intersectDataProvider(): Generator
	{
		foreach ($this->multiEnumOperationsDataProvider() as $caseName => $caseData) {
			yield $caseName => [
				'existingMultiEnum' => $caseData['existingMultiEnum'],
				'operandMultiEnum' => $caseData['operandMultiEnum'],
				'expectedMultiEnum' => $caseData['expectedIntersectResult'],
			];
		}
	}

	/**
	 * @dataProvider intersectDataProvider
	 *
	 * @param \Consistence\Enum\MultiEnum $existingMultiEnum
	 * @param \Consistence\Enum\MultiEnum $operandMultiEnum
	 * @param \Consistence\Enum\MultiEnum $expectedMultiEnum
	 */
	public function testIntersect(
		MultiEnum $existingMultiEnum,
		MultiEnum $operandMultiEnum,
		MultiEnum $expectedMultiEnum
	): void
	{
		Assert::assertSame($expectedMultiEnum, $existingMultiEnum->intersect($operandMultiEnum));
	}

	public function testIntersectDifferentEnum(): void
	{
		$userAndAdmin = RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN);
		$foo = FooEnum::get(FooEnum::FOO);

		try {
			$userAndAdmin->intersect($foo);
			Assert::fail('Exception expected');
		} catch (\Consistence\Enum\OperationSupportedOnlyForSameEnumException $e) {
			Assert::assertSame($userAndAdmin, $e->getExpected());
			Assert::assertSame($foo, $e->getGiven());
		}
	}

	/**
	 * @return mixed[][]|\Generator
	 */
	public function intersectEnumDataProvider(): Generator
	{
		foreach ($this->enumOperationsDataProvider() as $caseName => $caseData) {
			yield $caseName => [
				'existingMultiEnum' => $caseData['existingMultiEnum'],
				'operandEnum' => $caseData['operandEnum'],
				'expectedMultiEnum' => $caseData['expectedIntersectResult'],
			];
		}
	}

	/**
	 * @dataProvider intersectEnumDataProvider
	 *
	 * @param \Consistence\Enum\MultiEnum $existingMultiEnum
	 * @param \Consistence\Enum\Enum $operandEnum
	 * @param \Consistence\Enum\MultiEnum $expectedMultiEnum
	 */
	public function testIntersectEnum(
		MultiEnum $existingMultiEnum,
		Enum $operandEnum,
		MultiEnum $expectedMultiEnum
	): void
	{
		Assert::assertSame($expectedMultiEnum, $existingMultiEnum->intersectEnum($operandEnum));
	}

	public function testIntersectEnumSingleEnumNotDefined(): void
	{
		try {
			FooEnum::getMulti(FooEnum::FOO)->intersectEnum(FooEnum::get(FooEnum::FOO));
			Assert::fail('Exception expected');
		} catch (\Consistence\Enum\NoSingleEnumSpecifiedException $e) {
			Assert::assertSame(FooEnum::class, $e->getClass());
		}
	}

	public function testIntersectEnumDifferentEnum(): void
	{
		$userAndAdmin = RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN);
		$foo = FooEnum::get(FooEnum::FOO);

		try {
			$userAndAdmin->intersectEnum($foo);
			Assert::fail('Exception expected');
		} catch (\Consistence\InvalidArgumentTypeException $e) {
			Assert::assertSame($foo, $e->getValue());
			Assert::assertSame(FooEnum::class, $e->getValueType());
			Assert::assertSame(RoleEnum::class, $e->getExpectedTypes());
		}
	}

	/**
	 * @return mixed[][]|\Generator
	 */
	public function intersectValueDataProvider(): Generator
	{
		foreach ($this->enumOperationsDataProvider() as $caseName => $caseData) {
			yield $caseName => [
				'existingMultiEnum' => $caseData['existingMultiEnum'],
				'operandValue' => $caseData['operandEnum']->getValue(),
				'expectedMultiEnum' => $caseData['expectedIntersectResult'],
			];
		}
	}

	/**
	 * @dataProvider intersectValueDataProvider
	 *
	 * @param \Consistence\Enum\MultiEnum $existingMultiEnum
	 * @param int $operandValue
	 * @param \Consistence\Enum\MultiEnum $expectedMultiEnum
	 */
	public function testIntersectValue(
		MultiEnum $existingMultiEnum,
		int $operandValue,
		MultiEnum $expectedMultiEnum
	): void
	{
		Assert::assertSame($expectedMultiEnum, $existingMultiEnum->intersectValue($operandValue));
	}

	public function testIntersectInvalidValue(): void
	{
		$userAndAdmin = RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN);

		try {
			$userAndAdmin->intersectValue(RoleEnum::USER | RoleEnum::ADMIN);
			Assert::fail('Exception expected');
		} catch (\Consistence\Enum\InvalidEnumValueException $e) {
			Assert::assertSame(5, $e->getValue());
			Assert::assertEquals([
				'USER' => RoleEnum::USER,
				'EMPLOYEE' => RoleEnum::EMPLOYEE,
				'ADMIN' => RoleEnum::ADMIN,
			], $e->getAvailableValues());
			Assert::assertSame(RolesEnum::class, $e->getEnumClassName());
		}
	}

	public function testFilter(): void
	{
		$userAndAdmin = RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN);
		$newRoles = $userAndAdmin->filter(function (Enum $singleEnum): bool {
			return $singleEnum->equalsValue(RoleEnum::USER);
		});

		Assert::assertSame(RolesEnum::get(RoleEnum::USER), $newRoles);
	}

	public function testFilterValue(): void
	{
		$userAndAdmin = RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN);
		$newRoles = $userAndAdmin->filterValues(function (int $value): bool {
			return $value === RoleEnum::USER;
		});

		Assert::assertSame(RolesEnum::get(RoleEnum::USER), $newRoles);
	}

	public function testDuplicateSpecifiedValues(): void
	{
		try {
			MultiEnumWithValuesNotPowerOfTwo::get(MultiEnumWithValuesNotPowerOfTwo::FOO);
			Assert::fail('Exception expected');
		} catch (\Consistence\Enum\MultiEnumValueIsNotPowerOfTwoException $e) {
			Assert::assertSame(MultiEnumWithValuesNotPowerOfTwo::BAZ, $e->getValue());
			Assert::assertSame(MultiEnumWithValuesNotPowerOfTwo::class, $e->getClass());
		}
	}

	/**
	 * This test is here only because code executed in data providers is not counted as covered and since Enum
	 * is implemented using flyweight pattern, each enum type's initialization can be done only once. Also, data
	 * providers are executed before all tests, so the initialization of every enum used in data providers will always
	 * be done there.
	 *
	 * This test ensures that there is at least one enum type constructed outside of data provider and therefore covered.
	 * The enum type used here should not be used for any other test.
	 */
	public function testGetWithoutDataProvider(): void
	{
		$enum = CoverageMultiEnum::get(CoverageMultiEnum::COVERAGE);
		Assert::assertInstanceOf(CoverageMultiEnum::class, $enum);
	}

	/**
	 * @see self::testGetWithoutDataProvider()
	 *
	 * For MultiEnum there is implementation difference in construction of mapped and unmapped, so this test covers
	 * mapped variant.
	 */
	public function testGetMappedWithoutDataProvider(): void
	{
		$enum = CoverageMappedMultiEnum::get(CoverageEnum::COVERAGE);
		Assert::assertInstanceOf(CoverageMappedMultiEnum::class, $enum);
	}

}
