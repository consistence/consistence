<?php

namespace Consistence\Enum;

class MultiEnumTest extends \Consistence\TestCase
{

	public function testGet()
	{
		$userAndAdmin = RolesEnum::get(RoleEnum::USER | RoleEnum::ADMIN);
		$this->assertInstanceOf(RolesEnum::class, $userAndAdmin);
	}

	public function testGetSingle()
	{
		$employee = RolesEnum::get(RoleEnum::EMPLOYEE);
		$this->assertInstanceOf(RolesEnum::class, $employee);
	}

	public function testGetMulti()
	{
		$userAndAdmin = RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN);
		$this->assertInstanceOf(RolesEnum::class, $userAndAdmin);
	}

	public function testGetMultiByArray()
	{
		$userAndAdmin = RolesEnum::getMultiByArray([RoleEnum::USER, RoleEnum::ADMIN]);
		$this->assertInstanceOf(RolesEnum::class, $userAndAdmin);
	}

	public function testGetMultiByEnum()
	{
		$user = RolesEnum::getMultiByEnum(RoleEnum::get(RoleEnum::USER));
		$this->assertInstanceOf(RolesEnum::class, $user);
	}

	public function testGetMultiByEnums()
	{
		$userAndAdmin = RolesEnum::getMultiByEnums([RoleEnum::get(RoleEnum::USER), RoleEnum::get(RoleEnum::ADMIN)]);
		$this->assertInstanceOf(RolesEnum::class, $userAndAdmin);
	}

	public function testGetValue()
	{
		$userAndAdmin = RolesEnum::get(RoleEnum::USER | RoleEnum::ADMIN);
		$this->assertSame(RoleEnum::USER | RoleEnum::ADMIN, $userAndAdmin->getValue());
	}

	public function testGetValueSingle()
	{
		$employee = RolesEnum::get(RoleEnum::EMPLOYEE);
		$this->assertSame(RoleEnum::EMPLOYEE, $employee->getValue());
	}

	public function testGetMultiValue()
	{
		$userAndAdmin = RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN);
		$this->assertSame(RoleEnum::USER | RoleEnum::ADMIN, $userAndAdmin->getValue());
	}

	public function testGetMultiByArrayValue()
	{
		$userAndAdmin = RolesEnum::getMultiByArray([RoleEnum::USER, RoleEnum::ADMIN]);
		$this->assertSame(RoleEnum::USER | RoleEnum::ADMIN, $userAndAdmin->getValue());
	}

	public function testGetEnums()
	{
		$userAndAdmin = RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN);
		$this->assertSame([
			'USER' => RoleEnum::get(RoleEnum::USER),
			'ADMIN' => RoleEnum::get(RoleEnum::ADMIN),
		], $userAndAdmin->getEnums());
	}

	public function testIterateTroughEnums()
	{
		$userAndAdmin = RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN);
		foreach ($userAndAdmin as $role) {
			$this->assertInstanceOf(RoleEnum::class, $role);
		}
	}

	public function testGetValues()
	{
		$userAndAdmin = RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN);
		$this->assertEquals([
			'USER' => RoleEnum::USER,
			'ADMIN' => RoleEnum::ADMIN,
		], $userAndAdmin->getValues());
	}

	public function testSameInstances()
	{
		$userAndAdmin1 = RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN);
		$userAndAdmin2 = RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN);

		$this->assertSame($userAndAdmin1, $userAndAdmin2);
	}

	public function testSameInstancesIndependentOrder()
	{
		$userAndAdmin = RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN);
		$adminAndUser = RolesEnum::getMulti(RoleEnum::ADMIN, RoleEnum::USER);

		$this->assertSame($userAndAdmin, $adminAndUser);
	}

	public function testDifferentInstances()
	{
		$userAndAdmin = RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN);
		$user = RolesEnum::getMulti(RoleEnum::USER);

		$this->assertNotSame($userAndAdmin, $user);
	}

	public function testEquals()
	{
		$userAndAdmin1 = RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN);
		$userAndAdmin2 = RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN);

		$this->assertTrue($userAndAdmin1->equals($userAndAdmin2));
	}

	public function testNotEquals()
	{
		$userAndAdmin = RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN);
		$user = RolesEnum::getMulti(RoleEnum::USER);

		$this->assertFalse($userAndAdmin->equals($user));
	}

	public function testEqualsValue()
	{
		$userAndAdmin = RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN);

		$this->assertTrue($userAndAdmin->equalsValue(RoleEnum::USER | RoleEnum::ADMIN));
	}

	public function testNotEqualsValue()
	{
		$userAndAdmin = RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN);

		$this->assertFalse($userAndAdmin->equalsValue(RoleEnum::USER));
	}

	public function testGetAvailableValues()
	{
		$this->assertEquals([
			'USER' => RoleEnum::USER,
			'EMPLOYEE' => RoleEnum::EMPLOYEE,
			'ADMIN' => RoleEnum::ADMIN,
		], RolesEnum::getAvailableValues());
	}

	public function testGetAvailableEnums()
	{
		$this->assertEquals([
			'USER' => RolesEnum::get(RoleEnum::USER),
			'EMPLOYEE' => RolesEnum::get(RoleEnum::EMPLOYEE),
			'ADMIN' => RolesEnum::get(RoleEnum::ADMIN),
		], RolesEnum::getAvailableEnums());
	}

	public function testGetNoValue()
	{
		$empty = RolesEnum::get(0);
		$this->assertSame(0, $empty->getValue());
		$this->assertEquals([], $empty->getValues());
	}

	public function testGetMultiNoValue()
	{
		$empty = RolesEnum::getMulti();
		$this->assertSame(0, $empty->getValue());
		$this->assertEquals([], $empty->getValues());
	}

	public function testGetMultiByArrayNoValue()
	{
		$empty = RolesEnum::getMultiByArray([]);
		$this->assertSame(0, $empty->getValue());
		$this->assertEquals([], $empty->getValues());
	}

	public function testGetCombinations()
	{
		RolesEnum::get(0);
		RolesEnum::get(1);
		RolesEnum::get(2);
		RolesEnum::get(3);
		RolesEnum::get(4);
		RolesEnum::get(5);
		RolesEnum::get(6);
		RolesEnum::get(7);

		$this->expectException(\Consistence\Enum\InvalidEnumValueException::class);
		$this->expectExceptionMessage('8 [integer] is not a valid value');

		RolesEnum::get(8);
	}

	public function testGetNegative()
	{
		$this->expectException(\Consistence\Enum\InvalidEnumValueException::class);
		$this->expectExceptionMessage('-1 [integer] is not a valid value');

		RolesEnum::get(-1);
	}

	public function testGetMultiNegative()
	{
		$this->expectException(\Consistence\Enum\InvalidEnumValueException::class);
		$this->expectExceptionMessage('-1 [integer] is not a valid value');

		RolesEnum::getMulti(-1);
	}

	public function testInvalidEnumValue()
	{
		try {
			RolesEnum::get(8);
			$this->fail();
		} catch (\Consistence\Enum\InvalidEnumValueException $e) {
			$this->assertSame(8, $e->getValue());
			$this->assertEquals([
				'USER' => RoleEnum::USER,
				'EMPLOYEE' => RoleEnum::EMPLOYEE,
				'ADMIN' => RoleEnum::ADMIN,
			], $e->getAvailableValues());
		}
	}

	public function testComparingDifferentEnums()
	{
		$userAndAdmin = RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN);
		$foo = FooEnum::get(FooEnum::FOO);
		try {
			$userAndAdmin->equals($foo);

			$this->fail();
		} catch (\Consistence\Enum\OperationSupportedOnlyForSameEnumException $e) {
			$this->assertSame($userAndAdmin, $e->getExpected());
			$this->assertSame($foo, $e->getGiven());
		}
	}

	public function testNoSingleEnumDefinition()
	{
		try {
			FooEnum::getMulti(FooEnum::FOO)->getEnums();
			$this->fail();
		} catch (\Consistence\Enum\NoSingleEnumSpecifiedException $e) {
			$this->assertSame(FooEnum::class, $e->getClass());
		}
	}

	public function testContains()
	{
		$userAndAdmin = RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN);

		$this->assertTrue($userAndAdmin->contains(RolesEnum::get(RoleEnum::USER)));
		$this->assertTrue($userAndAdmin->contains(RolesEnum::get(RoleEnum::ADMIN)));
		$this->assertTrue($userAndAdmin->contains(RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN)));
		$this->assertFalse($userAndAdmin->contains(RolesEnum::get(RoleEnum::EMPLOYEE)));
		$this->assertFalse($userAndAdmin->contains(RolesEnum::getMulti(RoleEnum::USER, RoleEnum::EMPLOYEE)));
	}

	public function testContainsDifferentEnum()
	{
		$userAndAdmin = RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN);

		$this->expectException(\Consistence\Enum\OperationSupportedOnlyForSameEnumException::class);

		$userAndAdmin->contains(FooEnum::get(FooEnum::FOO));
	}

	public function testContainsEnum()
	{
		$userAndAdmin = RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN);

		$this->assertTrue($userAndAdmin->containsEnum(RoleEnum::get(RoleEnum::USER)));
		$this->assertTrue($userAndAdmin->containsEnum(RoleEnum::get(RoleEnum::ADMIN)));
		$this->assertFalse($userAndAdmin->containsEnum(RoleEnum::get(RoleEnum::EMPLOYEE)));
	}

	public function testContainsEnumSingleEnumNotDefined()
	{
		$this->expectException(\Consistence\Enum\NoSingleEnumSpecifiedException::class);

		FooEnum::getMulti(FooEnum::FOO)->containsEnum(FooEnum::get(FooEnum::FOO));
	}

	public function testContainsEnumDifferentEnum()
	{
		$userAndAdmin = RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN);

		$this->expectException(\Consistence\InvalidArgumentTypeException::class);
		$this->expectExceptionMessage('Consistence\Enum\RoleEnum expected');

		$userAndAdmin->containsEnum(FooEnum::get(FooEnum::FOO));
	}

	public function testContainsValue()
	{
		$userAndAdmin = RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN);

		$this->assertTrue($userAndAdmin->containsValue(RoleEnum::USER));
		$this->assertTrue($userAndAdmin->containsValue(RoleEnum::ADMIN));
		$this->assertFalse($userAndAdmin->containsValue(RoleEnum::EMPLOYEE));
	}

	public function testContainsInvalidValue()
	{
		$userAndAdmin = RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN);

		$this->expectException(\Consistence\Enum\InvalidEnumValueException::class);
		$this->expectExceptionMessage('-1 [integer] is not a valid value');

		$userAndAdmin->containsValue(-1);
	}

	public function testIsEmpty()
	{
		$this->assertTrue(RolesEnum::getMulti()->isEmpty());
		$this->assertTrue(RolesEnum::get(0)->isEmpty());
		$this->assertTrue(RolesEnum::getMultiByArray([])->isEmpty());
		$this->assertFalse(RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN)->isEmpty());
	}

	public function testAdd()
	{
		$userAndAdmin = RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN);
		$newRoles = $userAndAdmin->add(RolesEnum::get(RoleEnum::EMPLOYEE));

		$this->assertSame(RolesEnum::getMulti(RoleEnum::USER, RoleEnum::EMPLOYEE, RoleEnum::ADMIN), $newRoles);
	}

	public function testAddExisting()
	{
		$userAndAdmin = RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN);
		$newRoles = $userAndAdmin->add(RolesEnum::get(RoleEnum::USER));

		$this->assertSame($userAndAdmin, $newRoles);
	}

	public function testAddDifferentEnum()
	{
		$userAndAdmin = RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN);

		$this->expectException(\Consistence\Enum\OperationSupportedOnlyForSameEnumException::class);

		$userAndAdmin->add(FooEnum::get(FooEnum::FOO));
	}

	public function testAddEnum()
	{
		$userAndAdmin = RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN);
		$newRoles = $userAndAdmin->addEnum(RoleEnum::get(RoleEnum::EMPLOYEE));

		$this->assertSame(RolesEnum::getMulti(RoleEnum::USER, RoleEnum::EMPLOYEE, RoleEnum::ADMIN), $newRoles);
	}

	public function testAddEnumExisting()
	{
		$userAndAdmin = RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN);
		$newRoles = $userAndAdmin->addEnum(RoleEnum::get(RoleEnum::USER));

		$this->assertSame($userAndAdmin, $newRoles);
	}

	public function testAddEnumSingleEnumNotDefined()
	{
		$this->expectException(\Consistence\Enum\NoSingleEnumSpecifiedException::class);

		FooEnum::getMulti(FooEnum::FOO)->addEnum(FooEnum::get(FooEnum::FOO));
	}

	public function testAddEnumDifferentEnum()
	{
		$userAndAdmin = RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN);

		$this->expectException(\Consistence\InvalidArgumentTypeException::class);
		$this->expectExceptionMessage('Consistence\Enum\RoleEnum expected');

		$userAndAdmin->addEnum(FooEnum::get(FooEnum::FOO));
	}

	public function testAddValue()
	{
		$userAndAdmin = RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN);
		$newRoles = $userAndAdmin->addValue(RoleEnum::EMPLOYEE);

		$this->assertSame(RolesEnum::getMulti(RoleEnum::USER, RoleEnum::EMPLOYEE, RoleEnum::ADMIN), $newRoles);
	}

	public function testAddValueExisting()
	{
		$userAndAdmin = RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN);
		$newRoles = $userAndAdmin->addValue(RoleEnum::USER);

		$this->assertSame($userAndAdmin, $newRoles);
	}

	public function testAddInvalidValue()
	{
		$userAndAdmin = RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN);

		$this->expectException(\Consistence\Enum\InvalidEnumValueException::class);
		$this->expectExceptionMessage('5 [integer] is not a valid value');

		$userAndAdmin->addValue(RoleEnum::USER | RoleEnum::ADMIN);
	}

	public function testRemove()
	{
		$userAndAdmin = RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN);
		$newRoles = $userAndAdmin->remove(RolesEnum::get(RoleEnum::USER));

		$this->assertSame(RolesEnum::get(RoleEnum::ADMIN), $newRoles);
	}

	public function testRemoveDisabled()
	{
		$userAndAdmin = RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN);
		$newRoles = $userAndAdmin->remove(RolesEnum::get(RoleEnum::EMPLOYEE));

		$this->assertSame($userAndAdmin, $newRoles);
	}

	public function testRemoveDifferentEnum()
	{
		$userAndAdmin = RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN);

		$this->expectException(\Consistence\Enum\OperationSupportedOnlyForSameEnumException::class);

		$userAndAdmin->remove(FooEnum::get(FooEnum::FOO));
	}

	public function testRemoveEnum()
	{
		$userAndAdmin = RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN);
		$newRoles = $userAndAdmin->removeEnum(RoleEnum::get(RoleEnum::USER));

		$this->assertSame(RolesEnum::get(RoleEnum::ADMIN), $newRoles);
	}

	public function testRemoveEnumDisabled()
	{
		$userAndAdmin = RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN);
		$newRoles = $userAndAdmin->removeEnum(RoleEnum::get(RoleEnum::EMPLOYEE));

		$this->assertSame($userAndAdmin, $newRoles);
	}

	public function testRemoveEnumSingleEnumNotDefined()
	{
		$this->expectException(\Consistence\Enum\NoSingleEnumSpecifiedException::class);

		FooEnum::getMulti(FooEnum::FOO)->removeEnum(FooEnum::get(FooEnum::FOO));
	}

	public function testRemoveEnumDifferentEnum()
	{
		$userAndAdmin = RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN);

		$this->expectException(\Consistence\InvalidArgumentTypeException::class);
		$this->expectExceptionMessage('Consistence\Enum\RoleEnum expected');

		$userAndAdmin->removeEnum(FooEnum::get(FooEnum::FOO));
	}

	public function testRemoveValue()
	{
		$userAndAdmin = RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN);
		$newRoles = $userAndAdmin->removeValue(RoleEnum::USER);

		$this->assertSame(RolesEnum::get(RoleEnum::ADMIN), $newRoles);
	}

	public function testRemoveValueDisabled()
	{
		$userAndAdmin = RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN);
		$newRoles = $userAndAdmin->removeValue(RoleEnum::EMPLOYEE);

		$this->assertSame($userAndAdmin, $newRoles);
	}

	public function testRemoveInvalidValue()
	{
		$userAndAdmin = RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN);

		$this->expectException(\Consistence\Enum\InvalidEnumValueException::class);
		$this->expectExceptionMessage('5 [integer] is not a valid value');

		$userAndAdmin->removeValue(RoleEnum::USER | RoleEnum::ADMIN);
	}

	public function testIntersect()
	{
		$userAndAdmin = RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN);
		$newRoles = $userAndAdmin->intersect(RolesEnum::get(RoleEnum::USER));

		$this->assertSame(RolesEnum::get(RoleEnum::USER), $newRoles);
	}

	public function testIntersectEmptyResult()
	{
		$userAndAdmin = RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN);
		$newRoles = $userAndAdmin->intersect(RolesEnum::get(RoleEnum::EMPLOYEE));

		$this->assertSame(RolesEnum::getMulti(), $newRoles);
	}

	public function testIntersectWithEmpty()
	{
		$userAndAdmin = RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN);
		$newRoles = $userAndAdmin->intersect(RolesEnum::getMulti());

		$this->assertSame(RolesEnum::getMulti(), $newRoles);
	}

	public function testIntersectWithSame()
	{
		$userAndAdmin = RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN);
		$newRoles = $userAndAdmin->intersect(RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN));

		$this->assertSame($userAndAdmin, $newRoles);
	}

	public function testIntersectDifferentEnum()
	{
		$userAndAdmin = RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN);

		$this->expectException(\Consistence\Enum\OperationSupportedOnlyForSameEnumException::class);

		$userAndAdmin->intersect(FooEnum::get(FooEnum::FOO));
	}

	public function testIntersectEnum()
	{
		$userAndAdmin = RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN);
		$newRoles = $userAndAdmin->intersectEnum(RoleEnum::get(RoleEnum::USER));

		$this->assertSame(RolesEnum::get(RoleEnum::USER), $newRoles);
	}

	public function testIntersectEnumEmptyResult()
	{
		$userAndAdmin = RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN);
		$newRoles = $userAndAdmin->intersectEnum(RoleEnum::get(RoleEnum::EMPLOYEE));

		$this->assertSame(RolesEnum::getMulti(), $newRoles);
	}

	public function testIntersectEnumSingleEnumNotDefined()
	{
		$this->expectException(\Consistence\Enum\NoSingleEnumSpecifiedException::class);

		FooEnum::getMulti(FooEnum::FOO)->intersectEnum(FooEnum::get(FooEnum::FOO));
	}

	public function testIntersectEnumDifferentEnum()
	{
		$userAndAdmin = RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN);

		$this->expectException(\Consistence\InvalidArgumentTypeException::class);
		$this->expectExceptionMessage('Consistence\Enum\RoleEnum expected');

		$userAndAdmin->intersectEnum(FooEnum::get(FooEnum::FOO));
	}

	public function testIntersectValue()
	{
		$userAndAdmin = RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN);
		$newRoles = $userAndAdmin->intersectValue(RoleEnum::USER);

		$this->assertSame(RolesEnum::get(RoleEnum::USER), $newRoles);
	}

	public function testIntersectValueEmptyResult()
	{
		$userAndAdmin = RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN);
		$newRoles = $userAndAdmin->intersectValue(RoleEnum::EMPLOYEE);

		$this->assertSame(RolesEnum::getMulti(), $newRoles);
	}

	public function testIntersectInvalidValue()
	{
		$userAndAdmin = RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN);

		$this->expectException(\Consistence\Enum\InvalidEnumValueException::class);
		$this->expectExceptionMessage('5 [integer] is not a valid value');

		$userAndAdmin->intersectValue(RoleEnum::USER | RoleEnum::ADMIN);
	}

	public function testFilter()
	{
		$userAndAdmin = RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN);
		$newRoles = $userAndAdmin->filter(function (Enum $singleEnum) {
			return $singleEnum->equalsValue(RoleEnum::USER);
		});

		$this->assertSame(RolesEnum::get(RoleEnum::USER), $newRoles);
	}

	public function testFilterValue()
	{
		$userAndAdmin = RolesEnum::getMulti(RoleEnum::USER, RoleEnum::ADMIN);
		$newRoles = $userAndAdmin->filterValues(function ($value) {
			return $value === RoleEnum::USER;
		});

		$this->assertSame(RolesEnum::get(RoleEnum::USER), $newRoles);
	}

	public function testDuplicateSpecifiedValues()
	{
		try {
			MultiEnumWithValuesNotPowerOfTwo::get(MultiEnumWithValuesNotPowerOfTwo::FOO);
			$this->fail();
		} catch (\Consistence\Enum\MultiEnumValueIsNotPowerOfTwoException $e) {
			$this->assertSame(MultiEnumWithValuesNotPowerOfTwo::BAZ, $e->getValue());
			$this->assertSame(MultiEnumWithValuesNotPowerOfTwo::class, $e->getClass());
		}
	}

}
