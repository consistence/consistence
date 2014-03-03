<?php

namespace Consistence\Enum;

class MultiEnumTest extends \Consistence\TestCase
{

	public function testGet()
	{
		$userAndAdmin = RolesEnum::get(RolesEnum::USER | RolesEnum::ADMIN);
		$this->assertInstanceOf(RolesEnum::class, $userAndAdmin);
	}

	public function testGetSingle()
	{
		$employee = RolesEnum::get(RolesEnum::EMPLOYEE);
		$this->assertInstanceOf(RolesEnum::class, $employee);
	}

	public function testGetMulti()
	{
		$userAndAdmin = RolesEnum::getMulti(RolesEnum::USER, RolesEnum::ADMIN);
		$this->assertInstanceOf(RolesEnum::class, $userAndAdmin);
	}

	public function testGetMultiByArray()
	{
		$userAndAdmin = RolesEnum::getMultiByArray([RolesEnum::USER, RolesEnum::ADMIN]);
		$this->assertInstanceOf(RolesEnum::class, $userAndAdmin);
	}

	public function testGetValue()
	{
		$userAndAdmin = RolesEnum::get(RolesEnum::USER | RolesEnum::ADMIN);
		$this->assertSame(RolesEnum::USER | RolesEnum::ADMIN, $userAndAdmin->getValue());
	}

	public function testGetValueSingle()
	{
		$employee = RolesEnum::get(RolesEnum::EMPLOYEE);
		$this->assertSame(RolesEnum::EMPLOYEE, $employee->getValue());
	}

	public function testGetMultiValue()
	{
		$userAndAdmin = RolesEnum::getMulti(RolesEnum::USER, RolesEnum::ADMIN);
		$this->assertSame(RolesEnum::USER | RolesEnum::ADMIN, $userAndAdmin->getValue());
	}

	public function testGetMultiByArrayValue()
	{
		$userAndAdmin = RolesEnum::getMultiByArray([RolesEnum::USER, RolesEnum::ADMIN]);
		$this->assertSame(RolesEnum::USER | RolesEnum::ADMIN, $userAndAdmin->getValue());
	}

	public function testGetValues()
	{
		$userAndAdmin = RolesEnum::getMulti(RolesEnum::USER, RolesEnum::ADMIN);
		$this->assertEquals([
			'USER' => RolesEnum::USER,
			'ADMIN' => RolesEnum::ADMIN
		], $userAndAdmin->getValues());
	}

	public function testSameInstances()
	{
		$userAndAdmin1 = RolesEnum::getMulti(RolesEnum::USER, RolesEnum::ADMIN);
		$userAndAdmin2 = RolesEnum::getMulti(RolesEnum::USER, RolesEnum::ADMIN);

		$this->assertSame($userAndAdmin1, $userAndAdmin2);
	}

	public function testSameInstancesIndependentOrder()
	{
		$userAndAdmin = RolesEnum::getMulti(RolesEnum::USER, RolesEnum::ADMIN);
		$adminAndUser = RolesEnum::getMulti(RolesEnum::ADMIN, RolesEnum::USER);

		$this->assertSame($userAndAdmin, $adminAndUser);
	}

	public function testDifferentInstances()
	{
		$userAndAdmin = RolesEnum::getMulti(RolesEnum::USER, RolesEnum::ADMIN);
		$user = RolesEnum::getMulti(RolesEnum::USER);

		$this->assertNotSame($userAndAdmin, $user);
	}

	public function testEquals()
	{
		$userAndAdmin1 = RolesEnum::getMulti(RolesEnum::USER, RolesEnum::ADMIN);
		$userAndAdmin2 = RolesEnum::getMulti(RolesEnum::USER, RolesEnum::ADMIN);

		$this->assertTrue($userAndAdmin1->equals($userAndAdmin2));
	}

	public function testNotEquals()
	{
		$userAndAdmin = RolesEnum::getMulti(RolesEnum::USER, RolesEnum::ADMIN);
		$user = RolesEnum::getMulti(RolesEnum::USER);

		$this->assertFalse($userAndAdmin->equals($user));
	}

	public function testEqualsValue()
	{
		$userAndAdmin = RolesEnum::getMulti(RolesEnum::USER, RolesEnum::ADMIN);

		$this->assertTrue($userAndAdmin->equalsValue(RolesEnum::USER | RolesEnum::ADMIN));
	}

	public function testNotEqualsValue()
	{
		$userAndAdmin = RolesEnum::getMulti(RolesEnum::USER, RolesEnum::ADMIN);

		$this->assertFalse($userAndAdmin->equalsValue(RolesEnum::USER));
	}

	public function testGetAvailableValues()
	{
		$this->assertEquals([
			'USER' => RolesEnum::USER,
			'EMPLOYEE' => RolesEnum::EMPLOYEE,
			'ADMIN' => RolesEnum::ADMIN,
		], RolesEnum::getAvailableValues());
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
				'USER' => RolesEnum::USER,
				'EMPLOYEE' => RolesEnum::EMPLOYEE,
				'ADMIN' => RolesEnum::ADMIN,
			], $e->getAvailableValues());
		}
	}

	public function testComparingDifferentEnums()
	{
		$userAndAdmin = RolesEnum::getMulti(RolesEnum::USER, RolesEnum::ADMIN);
		$foo = FooEnum::get(FooEnum::FOO);
		try {
			$userAndAdmin->equals($foo);

			$this->fail();
		} catch (\Consistence\Enum\OperationSupportedOnlyForSameEnumException $e) {
			$this->assertSame($userAndAdmin, $e->getExpected());
			$this->assertSame($foo, $e->getGiven());
		}
	}

	public function testContains()
	{
		$userAndAdmin = RolesEnum::getMulti(RolesEnum::USER, RolesEnum::ADMIN);

		$this->assertTrue($userAndAdmin->contains(RolesEnum::get(RolesEnum::USER)));
		$this->assertTrue($userAndAdmin->contains(RolesEnum::get(RolesEnum::ADMIN)));
		$this->assertTrue($userAndAdmin->contains(RolesEnum::getMulti(RolesEnum::USER, RolesEnum::ADMIN)));
		$this->assertFalse($userAndAdmin->contains(RolesEnum::get(RolesEnum::EMPLOYEE)));
		$this->assertFalse($userAndAdmin->contains(RolesEnum::getMulti(RolesEnum::USER, RolesEnum::EMPLOYEE)));
	}

	public function testContainsDifferentEnum()
	{
		$userAndAdmin = RolesEnum::getMulti(RolesEnum::USER, RolesEnum::ADMIN);

		$this->expectException(\Consistence\Enum\OperationSupportedOnlyForSameEnumException::class);

		$userAndAdmin->contains(FooEnum::get(FooEnum::FOO));
	}

	public function testContainsValue()
	{
		$userAndAdmin = RolesEnum::getMulti(RolesEnum::USER, RolesEnum::ADMIN);

		$this->assertTrue($userAndAdmin->containsValue(RolesEnum::USER));
		$this->assertTrue($userAndAdmin->containsValue(RolesEnum::ADMIN));
		$this->assertFalse($userAndAdmin->containsValue(RolesEnum::EMPLOYEE));
	}

	public function testContainsInvalidValue()
	{
		$userAndAdmin = RolesEnum::getMulti(RolesEnum::USER, RolesEnum::ADMIN);

		$this->expectException(\Consistence\Enum\InvalidEnumValueException::class);
		$this->expectExceptionMessage('-1 [integer] is not a valid value');

		$userAndAdmin->containsValue(-1);
	}

	public function testIsEmpty()
	{
		$this->assertTrue(RolesEnum::getMulti()->isEmpty());
		$this->assertTrue(RolesEnum::get(0)->isEmpty());
		$this->assertTrue(RolesEnum::getMultiByArray([])->isEmpty());
		$this->assertFalse(RolesEnum::getMulti(RolesEnum::USER, RolesEnum::ADMIN)->isEmpty());
	}

	public function testAdd()
	{
		$userAndAdmin = RolesEnum::getMulti(RolesEnum::USER, RolesEnum::ADMIN);
		$newRoles = $userAndAdmin->add(RolesEnum::get(RolesEnum::EMPLOYEE));

		$this->assertSame(RolesEnum::getMulti(RolesEnum::USER, RolesEnum::EMPLOYEE, RolesEnum::ADMIN), $newRoles);
	}

	public function testAddExisting()
	{
		$userAndAdmin = RolesEnum::getMulti(RolesEnum::USER, RolesEnum::ADMIN);
		$newRoles = $userAndAdmin->add(RolesEnum::get(RolesEnum::USER));

		$this->assertSame($userAndAdmin, $newRoles);
	}

	public function testAddDifferentEnum()
	{
		$userAndAdmin = RolesEnum::getMulti(RolesEnum::USER, RolesEnum::ADMIN);

		$this->expectException(\Consistence\Enum\OperationSupportedOnlyForSameEnumException::class);

		$userAndAdmin->add(FooEnum::get(FooEnum::FOO));
	}

	public function testAddValue()
	{
		$userAndAdmin = RolesEnum::getMulti(RolesEnum::USER, RolesEnum::ADMIN);
		$newRoles = $userAndAdmin->addValue(RolesEnum::EMPLOYEE);

		$this->assertSame(RolesEnum::getMulti(RolesEnum::USER, RolesEnum::EMPLOYEE, RolesEnum::ADMIN), $newRoles);
	}

	public function testAddValueExisting()
	{
		$userAndAdmin = RolesEnum::getMulti(RolesEnum::USER, RolesEnum::ADMIN);
		$newRoles = $userAndAdmin->addValue(RolesEnum::USER);

		$this->assertSame($userAndAdmin, $newRoles);
	}

	public function testAddInvalidValue()
	{
		$userAndAdmin = RolesEnum::getMulti(RolesEnum::USER, RolesEnum::ADMIN);

		$this->expectException(\Consistence\Enum\InvalidEnumValueException::class);
		$this->expectExceptionMessage('5 [integer] is not a valid value');

		$userAndAdmin->addValue(RolesEnum::USER | RolesEnum::ADMIN);
	}

	public function testRemove()
	{
		$userAndAdmin = RolesEnum::getMulti(RolesEnum::USER, RolesEnum::ADMIN);
		$newRoles = $userAndAdmin->remove(RolesEnum::get(RolesEnum::USER));

		$this->assertSame(RolesEnum::get(RolesEnum::ADMIN), $newRoles);
	}

	public function testRemoveDisabled()
	{
		$userAndAdmin = RolesEnum::getMulti(RolesEnum::USER, RolesEnum::ADMIN);
		$newRoles = $userAndAdmin->remove(RolesEnum::get(RolesEnum::EMPLOYEE));

		$this->assertSame($userAndAdmin, $newRoles);
	}

	public function testRemoveDifferentEnum()
	{
		$userAndAdmin = RolesEnum::getMulti(RolesEnum::USER, RolesEnum::ADMIN);

		$this->expectException(\Consistence\Enum\OperationSupportedOnlyForSameEnumException::class);

		$userAndAdmin->remove(FooEnum::get(FooEnum::FOO));
	}

	public function testRemoveValue()
	{
		$userAndAdmin = RolesEnum::getMulti(RolesEnum::USER, RolesEnum::ADMIN);
		$allRoles = $userAndAdmin->removeValue(RolesEnum::USER);

		$this->assertSame(RolesEnum::get(RolesEnum::ADMIN), $allRoles);
	}

	public function testRemoveValueDisabled()
	{
		$userAndAdmin = RolesEnum::getMulti(RolesEnum::USER, RolesEnum::ADMIN);
		$newRoles = $userAndAdmin->removeValue(RolesEnum::EMPLOYEE);

		$this->assertSame($userAndAdmin, $newRoles);
	}

	public function testRemoveInvalidValue()
	{
		$userAndAdmin = RolesEnum::getMulti(RolesEnum::USER, RolesEnum::ADMIN);

		$this->expectException(\Consistence\Enum\InvalidEnumValueException::class);
		$this->expectExceptionMessage('5 [integer] is not a valid value');

		$userAndAdmin->removeValue(RolesEnum::USER | RolesEnum::ADMIN);
	}

	public function testIntersect()
	{
		$userAndAdmin = RolesEnum::getMulti(RolesEnum::USER, RolesEnum::ADMIN);
		$newRoles = $userAndAdmin->intersect(RolesEnum::get(RolesEnum::USER));

		$this->assertSame(RolesEnum::get(RolesEnum::USER), $newRoles);
	}

	public function testIntersectEmptyResult()
	{
		$userAndAdmin = RolesEnum::getMulti(RolesEnum::USER, RolesEnum::ADMIN);
		$newRoles = $userAndAdmin->intersect(RolesEnum::get(RolesEnum::EMPLOYEE));

		$this->assertSame(RolesEnum::getMulti(), $newRoles);
	}

	public function testIntersectWithEmpty()
	{
		$userAndAdmin = RolesEnum::getMulti(RolesEnum::USER, RolesEnum::ADMIN);
		$newRoles = $userAndAdmin->intersect(RolesEnum::getMulti());

		$this->assertSame(RolesEnum::getMulti(), $newRoles);
	}

	public function testIntersectWithSame()
	{
		$userAndAdmin = RolesEnum::getMulti(RolesEnum::USER, RolesEnum::ADMIN);
		$newRoles = $userAndAdmin->intersect(RolesEnum::getMulti(RolesEnum::USER, RolesEnum::ADMIN));

		$this->assertSame($userAndAdmin, $newRoles);
	}

	public function testIntersectDifferentEnum()
	{
		$userAndAdmin = RolesEnum::getMulti(RolesEnum::USER, RolesEnum::ADMIN);

		$this->expectException(\Consistence\Enum\OperationSupportedOnlyForSameEnumException::class);

		$userAndAdmin->intersect(FooEnum::get(FooEnum::FOO));
	}

	public function testIntersectValue()
	{
		$userAndAdmin = RolesEnum::getMulti(RolesEnum::USER, RolesEnum::ADMIN);
		$newRoles = $userAndAdmin->intersectValue(RolesEnum::USER);

		$this->assertSame(RolesEnum::get(RolesEnum::USER), $newRoles);
	}

	public function testIntersectValueEmptyResult()
	{
		$userAndAdmin = RolesEnum::getMulti(RolesEnum::USER, RolesEnum::ADMIN);
		$newRoles = $userAndAdmin->intersectValue(RolesEnum::EMPLOYEE);

		$this->assertSame(RolesEnum::getMulti(), $newRoles);
	}

	public function testIntersectInvalidValue()
	{
		$userAndAdmin = RolesEnum::getMulti(RolesEnum::USER, RolesEnum::ADMIN);

		$this->expectException(\Consistence\Enum\InvalidEnumValueException::class);
		$this->expectExceptionMessage('5 [integer] is not a valid value');

		$userAndAdmin->intersectValue(RolesEnum::USER | RolesEnum::ADMIN);
	}

}
