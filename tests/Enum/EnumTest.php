<?php

declare(strict_types = 1);

namespace Consistence\Enum;

use DateTimeImmutable;
use Generator;
use PHPUnit\Framework\Assert;

class EnumTest extends \PHPUnit\Framework\TestCase
{

	public function testGet(): void
	{
		$review = StatusEnum::get(StatusEnum::REVIEW);
		Assert::assertInstanceOf(StatusEnum::class, $review);
	}

	public function testGetValue(): void
	{
		$review = StatusEnum::get(StatusEnum::REVIEW);
		Assert::assertSame(StatusEnum::REVIEW, $review->getValue());
	}

	public function testSameInstances(): void
	{
		$review1 = StatusEnum::get(StatusEnum::REVIEW);
		$review2 = StatusEnum::get(StatusEnum::REVIEW);

		Assert::assertSame($review1, $review2);
	}

	public function testDifferentInstances(): void
	{
		$review = StatusEnum::get(StatusEnum::REVIEW);
		$draft = StatusEnum::get(StatusEnum::DRAFT);

		Assert::assertNotSame($review, $draft);
	}

	public function testEquals(): void
	{
		$review1 = StatusEnum::get(StatusEnum::REVIEW);
		$review2 = StatusEnum::get(StatusEnum::REVIEW);

		Assert::assertTrue($review1->equals($review2));
	}

	public function testNotEquals(): void
	{
		$review = StatusEnum::get(StatusEnum::REVIEW);
		$draft = StatusEnum::get(StatusEnum::DRAFT);

		Assert::assertFalse($review->equals($draft));
	}

	public function testEqualsValue(): void
	{
		$review = StatusEnum::get(StatusEnum::REVIEW);

		Assert::assertTrue($review->equalsValue(StatusEnum::REVIEW));
	}

	public function testNotEqualsValue(): void
	{
		$review = StatusEnum::get(StatusEnum::REVIEW);

		Assert::assertFalse($review->equalsValue(StatusEnum::DRAFT));
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

	public function testNotValidValue(): void
	{
		Assert::assertFalse(StatusEnum::isValidValue('bar'));
	}

	/**
	 * @return mixed[][]|\Generator
	 */
	public function invalidTypeDataProvider(): Generator
	{
		yield 'array' => [
			[],
		];
		yield 'object' => [
			new DateTimeImmutable(),
		];
		yield 'Closure' => [
			static function (): void {
				return;
			},
		];
		yield 'resource' => [
			fopen(__DIR__, 'r'),
		];
	}

	/**
	 * @return mixed[][]|\Generator
	 */
	public function invalidEnumValueDataProvider(): Generator
	{
		yield 'integer, which is not in available values' => [
			0,
		];
		yield 'float' => [
			1.5,
		];
		yield 'false' => [
			false,
		];
		yield 'true' => [
			true,
		];
		yield 'null' => [
			null,
		];

		foreach ($this->invalidTypeDataProvider() as $caseName => $caseData) {
			yield $caseName => $caseData;
		}
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

	public function testCheckInvalidValue(): void
	{
		try {
			StatusEnum::checkValue('foo');
			Assert::fail('Exception expected');
		} catch (\Consistence\Enum\InvalidEnumValueException $e) {
			Assert::assertSame('foo', $e->getValue());
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

	/**
	 * @return mixed[][]|\Generator
	 */
	public function validTypeDataProvider(): Generator
	{
		yield 'integer' => [
			TypeEnum::INTEGER,
		];
		yield 'string' => [
			TypeEnum::STRING,
		];
		yield 'float' => [
			TypeEnum::FLOAT,
		];
		yield 'boolean' => [
			TypeEnum::BOOLEAN,
		];
		yield 'null' => [
			TypeEnum::NULL,
		];
	}

	/**
	 * @dataProvider validTypeDataProvider
	 *
	 * @param mixed $value
	 */
	public function testValidTypes($value): void
	{
		$enum = TypeEnum::get($value);
		Assert::assertInstanceOf(TypeEnum::class, $enum);
		Assert::assertSame($enum->getValue(), $value);
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

}
