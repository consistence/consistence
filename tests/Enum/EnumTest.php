<?php

declare(strict_types = 1);

namespace Consistence\Enum;

use DateTimeImmutable;
use Generator;
use PHPUnit\Framework\Assert;

class EnumTest extends \PHPUnit\Framework\TestCase
{

	/**
	 * @return mixed[][]|\Generator
	 */
	public function validEnumValueDataProvider(): Generator
	{
		yield 'StatusEnum::REVIEW' => [
			'enumClassName' => StatusEnum::class,
			'value' => StatusEnum::REVIEW,
		];
		yield 'TypeEnum::INTEGER' => [
			'enumClassName' => TypeEnum::class,
			'value' => TypeEnum::INTEGER,
		];
		yield 'TypeEnum::STRING' => [
			'enumClassName' => TypeEnum::class,
			'value' => TypeEnum::STRING,
		];
		yield 'TypeEnum::FLOAT' => [
			'enumClassName' => TypeEnum::class,
			'value' => TypeEnum::FLOAT,
		];
		yield 'TypeEnum::BOOLEAN' => [
			'enumClassName' => TypeEnum::class,
			'value' => TypeEnum::BOOLEAN,
		];
		yield 'TypeEnum::NULL' => [
			'enumClassName' => TypeEnum::class,
			'value' => TypeEnum::NULL,
		];
	}

	/**
	 * @dataProvider validEnumValueDataProvider
	 *
	 * @param string $enumClassName
	 * @param mixed $value
	 */
	public function testGet(
		string $enumClassName,
		$value
	): void
	{
		$enum = $enumClassName::get($value);
		Assert::assertInstanceOf($enumClassName, $enum);
	}

	/**
	 * @dataProvider validEnumValueDataProvider
	 *
	 * @param string $enumClassName
	 * @param mixed $value
	 */
	public function testGetValue(
		string $enumClassName,
		$value
	): void
	{
		$enum = $enumClassName::get($value);
		Assert::assertSame($value, $enum->getValue());
	}

	/**
	 * @return mixed[][]|\Generator
	 */
	public function compareDataProvider(): Generator
	{
		yield 'StatusEnum equal' => [
			'enum1' => StatusEnum::get(StatusEnum::REVIEW),
			'enum2' => StatusEnum::get(StatusEnum::REVIEW),
			'equals' => true,
		];
		yield 'StatusEnum not equal' => [
			'enum1' => StatusEnum::get(StatusEnum::REVIEW),
			'enum2' => StatusEnum::get(StatusEnum::DRAFT),
			'equals' => false,
		];
	}

	/**
	 * @dataProvider compareDataProvider
	 *
	 * @param \Consistence\Enum\Enum $enum1
	 * @param \Consistence\Enum\Enum $enum2
	 * @param bool $equals
	 */
	public function testSameInstances(
		Enum $enum1,
		Enum $enum2,
		bool $equals
	): void
	{
		Assert::assertSame($equals, $enum1 === $enum2);
	}

	/**
	 * @dataProvider compareDataProvider
	 *
	 * @param \Consistence\Enum\Enum $enum1
	 * @param \Consistence\Enum\Enum $enum2
	 * @param bool $expectedEquals
	 */
	public function testEquals(
		Enum $enum1,
		Enum $enum2,
		bool $expectedEquals
	): void
	{
		Assert::assertSame($expectedEquals, $enum1->equals($enum2));
	}

	/**
	 * @return mixed[][]|\Generator
	 */
	public function equalsValueDataProvider(): Generator
	{
		foreach ($this->compareDataProvider() as $caseName => $caseData) {
			yield $caseName => [
				'enum' => $caseData['enum1'],
				'value' => $caseData['enum2']->getValue(),
				'expectedEqualsValue' => $caseData['equals'],
			];
		}
	}

	/**
	 * @dataProvider equalsValueDataProvider
	 *
	 * @param \Consistence\Enum\Enum $enum
	 * @param mixed $value
	 * @param bool $expectedEqualsValue
	 */
	public function testEqualsValue(
		Enum $enum,
		$value,
		bool $expectedEqualsValue
	): void
	{
		Assert::assertSame($expectedEqualsValue, $enum->equalsValue($value));
	}

	public function testGetAvailableValues(): void
	{
		Assert::assertEquals([
			'DRAFT' => StatusEnum::DRAFT,
			'REVIEW' => StatusEnum::REVIEW,
			'PUBLISHED' => StatusEnum::PUBLISHED,
		], StatusEnum::getAvailableValues());
	}

	public function testGetAvailableEnums(): void
	{
		Assert::assertEquals([
			'DRAFT' => StatusEnum::get(StatusEnum::DRAFT),
			'REVIEW' => StatusEnum::get(StatusEnum::REVIEW),
			'PUBLISHED' => StatusEnum::get(StatusEnum::PUBLISHED),
		], StatusEnum::getAvailableEnums());
	}

	public function testIsValidValue(): void
	{
		Assert::assertTrue(StatusEnum::isValidValue(StatusEnum::DRAFT));
	}

	/**
	 * @return mixed[][]|\Generator
	 */
	public function invalidTypeDataProvider(): Generator
	{
		yield 'array' => [
			'value' => [],
		];
		yield 'object' => [
			'value' => new DateTimeImmutable(),
		];
		yield 'Closure' => [
			'value' => static function (): void {
				return;
			},
		];
		yield 'resource' => [
			'value' => fopen(__DIR__, 'r'),
		];
	}

	/**
	 * @return mixed[][]|\Generator
	 */
	public function invalidEnumValueDataProvider(): Generator
	{
		yield 'integer, which is not in available values' => [
			'value' => 0,
		];
		yield 'float' => [
			'value' => 1.5,
		];
		yield 'false' => [
			'value' => false,
		];
		yield 'true' => [
			'value' => true,
		];
		yield 'null' => [
			'value' => null,
		];
		yield 'string' => [
			'value' => 'foo',
		];

		foreach ($this->invalidTypeDataProvider() as $caseName => $caseData) {
			yield $caseName => [
				'value' => $caseData['value'],
			];
		}
	}

	/**
	 * @dataProvider invalidEnumValueDataProvider
	 *
	 * @param mixed $value
	 */
	public function testNotValidValue($value): void
	{
		Assert::assertFalse(StatusEnum::isValidValue($value));
	}

	/**
	 * @dataProvider invalidEnumValueDataProvider
	 *
	 * @param mixed $value
	 */
	public function testInvalidEnumValue($value): void
	{
		try {
			StatusEnum::get($value);
			Assert::fail('Exception expected');
		} catch (\Consistence\Enum\InvalidEnumValueException $e) {
			Assert::assertSame($value, $e->getValue());
			Assert::assertEquals([
				'DRAFT' => StatusEnum::DRAFT,
				'REVIEW' => StatusEnum::REVIEW,
				'PUBLISHED' => StatusEnum::PUBLISHED,
			], $e->getAvailableValues());
			Assert::assertSame(StatusEnum::class, $e->getEnumClassName());
		}
	}

	public function testCheckValue(): void
	{
		$this->expectNotToPerformAssertions();

		StatusEnum::checkValue(StatusEnum::DRAFT);
	}

	/**
	 * @dataProvider invalidEnumValueDataProvider
	 *
	 * @param mixed $value
	 */
	public function testCheckInvalidValue($value): void
	{
		try {
			StatusEnum::checkValue($value);
			Assert::fail('Exception expected');
		} catch (\Consistence\Enum\InvalidEnumValueException $e) {
			Assert::assertSame($value, $e->getValue());
			Assert::assertEquals([
				'DRAFT' => StatusEnum::DRAFT,
				'REVIEW' => StatusEnum::REVIEW,
				'PUBLISHED' => StatusEnum::PUBLISHED,
			], $e->getAvailableValues());
			Assert::assertSame(StatusEnum::class, $e->getEnumClassName());
		}
	}

	public function testComparingDifferentEnums(): void
	{
		$review = StatusEnum::get(StatusEnum::REVIEW);
		$foo = FooEnum::get(FooEnum::FOO);
		try {
			$review->equals($foo);

			Assert::fail('Exception expected');
		} catch (\Consistence\Enum\OperationSupportedOnlyForSameEnumException $e) {
			Assert::assertSame($review, $e->getExpected());
			Assert::assertSame($foo, $e->getGiven());
		}
	}

	public function testAvailableValuesFooEnum(): void
	{
		Assert::assertEquals([
			'FOO' => FooEnum::FOO,
		], FooEnum::getAvailableValues());
	}

	public function testIgnoredConstant(): void
	{
		try {
			StatusEnum::get('bar');
			Assert::fail('Exception expected');
		} catch (\Consistence\Enum\InvalidEnumValueException $e) {
			Assert::assertSame('bar', $e->getValue());
			Assert::assertEquals([
				'DRAFT' => StatusEnum::DRAFT,
				'REVIEW' => StatusEnum::REVIEW,
				'PUBLISHED' => StatusEnum::PUBLISHED,
			], $e->getAvailableValues());
		}
	}

	public function testDuplicateSpecifiedValues(): void
	{
		try {
			DuplicateValuesEnum::get(DuplicateValuesEnum::BAZ);
			Assert::fail('Exception expected');
		} catch (\Consistence\Enum\DuplicateValueSpecifiedException $e) {
			Assert::assertSame(DuplicateValuesEnum::FOO, $e->getValue());
			Assert::assertSame(DuplicateValuesEnum::class, $e->getClass());
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
		$enum = CoverageEnum::get(CoverageEnum::COVERAGE);
		Assert::assertInstanceOf(CoverageEnum::class, $enum);
	}

}
