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
	public function validValueDataProvider(): Generator
	{
		yield 'empty RolesEnum' => [
			'multiEnumClassName' => RolesEnum::class,
			'value' => 0,
		];
		yield 'RolesEnum, USER' => [
			'multiEnumClassName' => RolesEnum::class,
			'value' => 1,
		];
		yield 'RolesEnum, EMPLOYEE' => [
			'multiEnumClassName' => RolesEnum::class,
			'value' => 2,
		];
		yield 'RolesEnum, USER and EMPLOYEE' => [
			'multiEnumClassName' => RolesEnum::class,
			'value' => 3,
		];
		yield 'RolesEnum, ADMIN' => [
			'multiEnumClassName' => RolesEnum::class,
			'value' => 4,
		];
		yield 'RolesEnum, USER and ADMIN' => [
			'multiEnumClassName' => RolesEnum::class,
			'value' => 5,
		];
		yield 'RolesEnum, EMPLOYEE and ADMIN' => [
			'multiEnumClassName' => RolesEnum::class,
			'value' => 6,
		];
		yield 'RolesEnum, USER and EMPLOYEE and ADMIN' => [
			'multiEnumClassName' => RolesEnum::class,
			'value' => 7,
		];
		yield 'RolesEnum, EMPLOYEE as Enum constant' => [
			'multiEnumClassName' => RolesEnum::class,
			'value' => RoleEnum::EMPLOYEE,
		];
		yield 'RolesEnum, USER and ADMIN as bitwise OR of Enum constants' => [
			'multiEnumClassName' => RolesEnum::class,
			'value' => RoleEnum::USER | RoleEnum::ADMIN,
		];
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

	public function testGetMulti(): void
	{
		$userAndAdmin = RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN);
		Assert::assertInstanceOf(RolesEnum::class, $userAndAdmin);
	}

	public function testGetMultiByArray(): void
	{
		$userAndAdmin = RolesEnum::getMultiByArray([RoleEnum::USER, RoleEnum::ADMIN]);
		Assert::assertInstanceOf(RolesEnum::class, $userAndAdmin);
	}

	public function testGetMultiByEnum(): void
	{
		$user = RolesEnum::getMultiByEnum(RoleEnum::get(RoleEnum::USER));
		Assert::assertInstanceOf(RolesEnum::class, $user);
	}

	public function testGetMultiByEnums(): void
	{
		$userAndAdmin = RolesEnum::getMultiByEnums([RoleEnum::get(RoleEnum::USER), RoleEnum::get(RoleEnum::ADMIN)]);
		Assert::assertInstanceOf(RolesEnum::class, $userAndAdmin);
	}

	public function testGetValue(): void
	{
		$userAndAdmin = RolesEnum::get(RoleEnum::USER | RoleEnum::ADMIN);
		Assert::assertSame(RoleEnum::USER | RoleEnum::ADMIN, $userAndAdmin->getValue());
	}

	public function testGetValueSingle(): void
	{
		$employee = RolesEnum::get(RoleEnum::EMPLOYEE);
		Assert::assertSame(RoleEnum::EMPLOYEE, $employee->getValue());
	}

	public function testGetMultiValue(): void
	{
		$userAndAdmin = RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN);
		Assert::assertSame(RoleEnum::USER | RoleEnum::ADMIN, $userAndAdmin->getValue());
	}

	public function testGetMultiByArrayValue(): void
	{
		$userAndAdmin = RolesEnum::getMultiByArray([RoleEnum::USER, RoleEnum::ADMIN]);
		Assert::assertSame(RoleEnum::USER | RoleEnum::ADMIN, $userAndAdmin->getValue());
	}

	public function testGetEnums(): void
	{
		$userAndAdmin = RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN);
		Assert::assertEquals([
			'USER' => RoleEnum::get(RoleEnum::USER),
			'ADMIN' => RoleEnum::get(RoleEnum::ADMIN),
		], $userAndAdmin->getEnums());
	}

	public function testIterateTroughEnums(): void
	{
		$userAndAdmin = RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN);
		foreach ($userAndAdmin as $role) {
			Assert::assertInstanceOf(RoleEnum::class, $role);
		}
	}

	public function testGetValues(): void
	{
		$userAndAdmin = RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN);
		Assert::assertEquals([
			'USER' => RoleEnum::USER,
			'ADMIN' => RoleEnum::ADMIN,
		], $userAndAdmin->getValues());
	}

	public function testSameInstances(): void
	{
		$userAndAdmin1 = RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN);
		$userAndAdmin2 = RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN);

		Assert::assertSame($userAndAdmin1, $userAndAdmin2);
	}

	public function testSameInstancesIndependentOrder(): void
	{
		$userAndAdmin = RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN);
		$adminAndUser = RolesEnum::getMulti(RoleEnum::ADMIN, RoleEnum::USER);

		Assert::assertSame($userAndAdmin, $adminAndUser);
	}

	public function testDifferentInstances(): void
	{
		$userAndAdmin = RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN);
		$user = RolesEnum::getMulti(RoleEnum::USER);

		Assert::assertNotSame($userAndAdmin, $user);
	}

	public function testEquals(): void
	{
		$userAndAdmin1 = RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN);
		$userAndAdmin2 = RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN);

		Assert::assertTrue($userAndAdmin1->equals($userAndAdmin2));
	}

	public function testNotEquals(): void
	{
		$userAndAdmin = RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN);
		$user = RolesEnum::getMulti(RoleEnum::USER);

		Assert::assertFalse($userAndAdmin->equals($user));
	}

	public function testEqualsValue(): void
	{
		$userAndAdmin = RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN);

		Assert::assertTrue($userAndAdmin->equalsValue(RoleEnum::USER | RoleEnum::ADMIN));
	}

	public function testNotEqualsValue(): void
	{
		$userAndAdmin = RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN);

		Assert::assertFalse($userAndAdmin->equalsValue(RoleEnum::USER));
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

	public function testGetNegative(): void
	{
		try {
			RolesEnum::get(-1);
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

	public function testGetMultiNegative(): void
	{
		try {
			RolesEnum::getMulti(-1);
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

	public function testInvalidEnumValue(): void
	{
		try {
			RolesEnum::get(8);
			Assert::fail('Exception expected');
		} catch (\Consistence\Enum\InvalidEnumValueException $e) {
			Assert::assertSame(8, $e->getValue());
			Assert::assertEquals([
				'USER' => RoleEnum::USER,
				'EMPLOYEE' => RoleEnum::EMPLOYEE,
				'ADMIN' => RoleEnum::ADMIN,
			], $e->getAvailableValues());
			Assert::assertSame(RolesEnum::class, $e->getEnumClassName());
		}
	}

	public function testComparingDifferentEnums(): void
	{
		$userAndAdmin = RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN);
		$foo = FooEnum::get(FooEnum::FOO);
		try {
			$userAndAdmin->equals($foo);

			Assert::fail('Exception expected');
		} catch (\Consistence\Enum\OperationSupportedOnlyForSameEnumException $e) {
			Assert::assertSame($userAndAdmin, $e->getExpected());
			Assert::assertSame($foo, $e->getGiven());
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

	public function testAdd(): void
	{
		$userAndAdmin = RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN);
		$newRoles = $userAndAdmin->add(RolesEnum::get(RoleEnum::EMPLOYEE));

		Assert::assertSame(RolesEnum::getMulti(RoleEnum::USER, RoleEnum::EMPLOYEE, RoleEnum::ADMIN), $newRoles);
	}

	public function testAddExisting(): void
	{
		$userAndAdmin = RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN);
		$newRoles = $userAndAdmin->add(RolesEnum::get(RoleEnum::USER));

		Assert::assertSame($userAndAdmin, $newRoles);
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

	public function testAddEnum(): void
	{
		$userAndAdmin = RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN);
		$newRoles = $userAndAdmin->addEnum(RoleEnum::get(RoleEnum::EMPLOYEE));

		Assert::assertSame(RolesEnum::getMulti(RoleEnum::USER, RoleEnum::EMPLOYEE, RoleEnum::ADMIN), $newRoles);
	}

	public function testAddEnumExisting(): void
	{
		$userAndAdmin = RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN);
		$newRoles = $userAndAdmin->addEnum(RoleEnum::get(RoleEnum::USER));

		Assert::assertSame($userAndAdmin, $newRoles);
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

	public function testAddValue(): void
	{
		$userAndAdmin = RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN);
		$newRoles = $userAndAdmin->addValue(RoleEnum::EMPLOYEE);

		Assert::assertSame(RolesEnum::getMulti(RoleEnum::USER, RoleEnum::EMPLOYEE, RoleEnum::ADMIN), $newRoles);
	}

	public function testAddValueExisting(): void
	{
		$userAndAdmin = RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN);
		$newRoles = $userAndAdmin->addValue(RoleEnum::USER);

		Assert::assertSame($userAndAdmin, $newRoles);
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

	public function testRemove(): void
	{
		$userAndAdmin = RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN);
		$newRoles = $userAndAdmin->remove(RolesEnum::get(RoleEnum::USER));

		Assert::assertSame(RolesEnum::get(RoleEnum::ADMIN), $newRoles);
	}

	public function testRemoveDisabled(): void
	{
		$userAndAdmin = RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN);
		$newRoles = $userAndAdmin->remove(RolesEnum::get(RoleEnum::EMPLOYEE));

		Assert::assertSame($userAndAdmin, $newRoles);
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

	public function testRemoveEnum(): void
	{
		$userAndAdmin = RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN);
		$newRoles = $userAndAdmin->removeEnum(RoleEnum::get(RoleEnum::USER));

		Assert::assertSame(RolesEnum::get(RoleEnum::ADMIN), $newRoles);
	}

	public function testRemoveEnumDisabled(): void
	{
		$userAndAdmin = RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN);
		$newRoles = $userAndAdmin->removeEnum(RoleEnum::get(RoleEnum::EMPLOYEE));

		Assert::assertSame($userAndAdmin, $newRoles);
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

	public function testRemoveValue(): void
	{
		$userAndAdmin = RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN);
		$newRoles = $userAndAdmin->removeValue(RoleEnum::USER);

		Assert::assertSame(RolesEnum::get(RoleEnum::ADMIN), $newRoles);
	}

	public function testRemoveValueDisabled(): void
	{
		$userAndAdmin = RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN);
		$newRoles = $userAndAdmin->removeValue(RoleEnum::EMPLOYEE);

		Assert::assertSame($userAndAdmin, $newRoles);
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

	public function testIntersect(): void
	{
		$userAndAdmin = RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN);
		$newRoles = $userAndAdmin->intersect(RolesEnum::get(RoleEnum::USER));

		Assert::assertSame(RolesEnum::get(RoleEnum::USER), $newRoles);
	}

	public function testIntersectEmptyResult(): void
	{
		$userAndAdmin = RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN);
		$newRoles = $userAndAdmin->intersect(RolesEnum::get(RoleEnum::EMPLOYEE));

		Assert::assertSame(RolesEnum::getMulti(), $newRoles);
	}

	public function testIntersectWithEmpty(): void
	{
		$userAndAdmin = RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN);
		$newRoles = $userAndAdmin->intersect(RolesEnum::getMulti());

		Assert::assertSame(RolesEnum::getMulti(), $newRoles);
	}

	public function testIntersectWithSame(): void
	{
		$userAndAdmin = RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN);
		$newRoles = $userAndAdmin->intersect(RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN));

		Assert::assertSame($userAndAdmin, $newRoles);
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

	public function testIntersectEnum(): void
	{
		$userAndAdmin = RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN);
		$newRoles = $userAndAdmin->intersectEnum(RoleEnum::get(RoleEnum::USER));

		Assert::assertSame(RolesEnum::get(RoleEnum::USER), $newRoles);
	}

	public function testIntersectEnumEmptyResult(): void
	{
		$userAndAdmin = RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN);
		$newRoles = $userAndAdmin->intersectEnum(RoleEnum::get(RoleEnum::EMPLOYEE));

		Assert::assertSame(RolesEnum::getMulti(), $newRoles);
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

	public function testIntersectValue(): void
	{
		$userAndAdmin = RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN);
		$newRoles = $userAndAdmin->intersectValue(RoleEnum::USER);

		Assert::assertSame(RolesEnum::get(RoleEnum::USER), $newRoles);
	}

	public function testIntersectValueEmptyResult(): void
	{
		$userAndAdmin = RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN);
		$newRoles = $userAndAdmin->intersectValue(RoleEnum::EMPLOYEE);

		Assert::assertSame(RolesEnum::getMulti(), $newRoles);
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
