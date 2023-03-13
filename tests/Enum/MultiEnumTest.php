<?php

declare(strict_types = 1);

namespace Consistence\Enum;

use PHPUnit\Framework\Assert;

class MultiEnumTest extends \PHPUnit\Framework\TestCase
{

	public function testGet(): void
	{
		$userAndAdmin = RolesEnum::get(RoleEnum::USER | RoleEnum::ADMIN);
		Assert::assertInstanceOf(RolesEnum::class, $userAndAdmin);
	}

	public function testGetSingle(): void
	{
		$employee = RolesEnum::get(RoleEnum::EMPLOYEE);
		Assert::assertInstanceOf(RolesEnum::class, $employee);
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

	public function testGetCombinations(): void
	{
		RolesEnum::get(0);
		RolesEnum::get(1);
		RolesEnum::get(2);
		RolesEnum::get(3);
		RolesEnum::get(4);
		RolesEnum::get(5);
		RolesEnum::get(6);
		RolesEnum::get(7);

		$this->expectNotToPerformAssertions();
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

	public function testContains(): void
	{
		$userAndAdmin = RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN);

		Assert::assertTrue($userAndAdmin->contains(RolesEnum::get(RoleEnum::USER)));
		Assert::assertTrue($userAndAdmin->contains(RolesEnum::get(RoleEnum::ADMIN)));
		Assert::assertTrue($userAndAdmin->contains(RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN)));
		Assert::assertFalse($userAndAdmin->contains(RolesEnum::get(RoleEnum::EMPLOYEE)));
		Assert::assertFalse($userAndAdmin->contains(RolesEnum::getMulti(RoleEnum::USER, RoleEnum::EMPLOYEE)));
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

	public function testContainsEnum(): void
	{
		$userAndAdmin = RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN);

		Assert::assertTrue($userAndAdmin->containsEnum(RoleEnum::get(RoleEnum::USER)));
		Assert::assertTrue($userAndAdmin->containsEnum(RoleEnum::get(RoleEnum::ADMIN)));
		Assert::assertFalse($userAndAdmin->containsEnum(RoleEnum::get(RoleEnum::EMPLOYEE)));
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

	public function testContainsValue(): void
	{
		$userAndAdmin = RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN);

		Assert::assertTrue($userAndAdmin->containsValue(RoleEnum::USER));
		Assert::assertTrue($userAndAdmin->containsValue(RoleEnum::ADMIN));
		Assert::assertFalse($userAndAdmin->containsValue(RoleEnum::EMPLOYEE));
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

	public function testIsEmpty(): void
	{
		Assert::assertTrue(RolesEnum::getMulti()->isEmpty());
		Assert::assertTrue(RolesEnum::get(0)->isEmpty());
		Assert::assertTrue(RolesEnum::getMultiByArray([])->isEmpty());
		Assert::assertFalse(RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN)->isEmpty());
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

}
