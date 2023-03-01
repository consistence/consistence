<?php

declare(strict_types = 1);

namespace Consistence\Enum;

use Consistence\Type\ArrayType\ArrayType;
use DateTimeImmutable;

class EnumTest extends \Consistence\TestCase
{

	public function testGet(): void
	{
		$review = StatusEnum::get(StatusEnum::REVIEW);
		$this->assertInstanceOf(StatusEnum::class, $review);
	}

	public function testGetValue(): void
	{
		$review = StatusEnum::get(StatusEnum::REVIEW);
		$this->assertSame(StatusEnum::REVIEW, $review->getValue());
	}

	public function testSameInstances(): void
	{
		$review1 = StatusEnum::get(StatusEnum::REVIEW);
		$review2 = StatusEnum::get(StatusEnum::REVIEW);

		$this->assertSame($review1, $review2);
	}

	public function testDifferentInstances(): void
	{
		$review = StatusEnum::get(StatusEnum::REVIEW);
		$draft = StatusEnum::get(StatusEnum::DRAFT);

		$this->assertNotSame($review, $draft);
	}

	public function testEquals(): void
	{
		$review1 = StatusEnum::get(StatusEnum::REVIEW);
		$review2 = StatusEnum::get(StatusEnum::REVIEW);

		$this->assertTrue($review1->equals($review2));
	}

	public function testNotEquals(): void
	{
		$review = StatusEnum::get(StatusEnum::REVIEW);
		$draft = StatusEnum::get(StatusEnum::DRAFT);

		$this->assertFalse($review->equals($draft));
	}

	public function testEqualsValue(): void
	{
		$review = StatusEnum::get(StatusEnum::REVIEW);

		$this->assertTrue($review->equalsValue(StatusEnum::REVIEW));
	}

	public function testNotEqualsValue(): void
	{
		$review = StatusEnum::get(StatusEnum::REVIEW);

		$this->assertFalse($review->equalsValue(StatusEnum::DRAFT));
	}

	public function testGetAvailableValues(): void
	{
		$this->assertEquals([
			'DRAFT' => StatusEnum::DRAFT,
			'REVIEW' => StatusEnum::REVIEW,
			'PUBLISHED' => StatusEnum::PUBLISHED,
		], StatusEnum::getAvailableValues());
	}

	public function testGetAvailableEnums(): void
	{
		$this->assertEquals([
			'DRAFT' => StatusEnum::get(StatusEnum::DRAFT),
			'REVIEW' => StatusEnum::get(StatusEnum::REVIEW),
			'PUBLISHED' => StatusEnum::get(StatusEnum::PUBLISHED),
		], StatusEnum::getAvailableEnums());
	}

	public function testIsValidValue(): void
	{
		$this->assertTrue(StatusEnum::isValidValue(StatusEnum::DRAFT));
	}

	public function testNotValidValue(): void
	{
		$this->assertFalse(StatusEnum::isValidValue(0));
	}

	/**
	 * @return mixed[][]
	 */
	public function invalidTypesProvider(): array
	{
		return [
			[[]],
			[new DateTimeImmutable()],
			[static function (): void {
				return;
			}],
			[fopen(__DIR__, 'r')],
		];
	}

	/**
	 * @return mixed[][]
	 */
	public function invalidEnumValuesProvider(): array
	{
		return array_merge([
			[0],
			[1.5],
			[false],
			[true],
			[null],
		], array_values($this->invalidTypesProvider()));
	}

	/**
	 * @dataProvider invalidEnumValuesProvider
	 *
	 * @param mixed $value
	 */
	public function testInvalidEnumValue($value): void
	{
		try {
			StatusEnum::get($value);
			$this->fail();
		} catch (\Consistence\Enum\InvalidEnumValueException $e) {
			$this->assertSame($value, $e->getValue());
			$this->assertEquals([
				'DRAFT' => StatusEnum::DRAFT,
				'REVIEW' => StatusEnum::REVIEW,
				'PUBLISHED' => StatusEnum::PUBLISHED,
			], $e->getAvailableValues());
			$this->assertSame(StatusEnum::class, $e->getEnumClassName());
		}
	}

	public function testCheckValue(): void
	{
		StatusEnum::checkValue(StatusEnum::DRAFT);
		$this->ok();
	}

	public function testCheckInvalidValue(): void
	{
		try {
			StatusEnum::checkValue('foo');
			$this->fail();
		} catch (\Consistence\Enum\InvalidEnumValueException $e) {
			$this->assertSame('foo', $e->getValue());
			$this->assertEquals([
				'DRAFT' => StatusEnum::DRAFT,
				'REVIEW' => StatusEnum::REVIEW,
				'PUBLISHED' => StatusEnum::PUBLISHED,
			], $e->getAvailableValues());
			$this->assertSame(StatusEnum::class, $e->getEnumClassName());
		}
	}

	public function testComparingDifferentEnums(): void
	{
		$review = StatusEnum::get(StatusEnum::REVIEW);
		$foo = FooEnum::get(FooEnum::FOO);
		try {
			$review->equals($foo);

			$this->fail();
		} catch (\Consistence\Enum\OperationSupportedOnlyForSameEnumException $e) {
			$this->assertSame($review, $e->getExpected());
			$this->assertSame($foo, $e->getGiven());
		}
	}

	public function testAvailableValuesFooEnum(): void
	{
		$this->assertEquals([
			'FOO' => FooEnum::FOO,
		], FooEnum::getAvailableValues());
	}

	public function testIgnoredConstant(): void
	{
		try {
			StatusEnum::get('bar');
			$this->fail();
		} catch (\Consistence\Enum\InvalidEnumValueException $e) {
			$this->assertSame('bar', $e->getValue());
			$this->assertEquals([
				'DRAFT' => StatusEnum::DRAFT,
				'REVIEW' => StatusEnum::REVIEW,
				'PUBLISHED' => StatusEnum::PUBLISHED,
			], $e->getAvailableValues());
		}
	}

	/**
	 * @return mixed[][]
	 */
	public function validTypesProvider(): array
	{
		return ArrayType::mapValuesByCallback(TypeEnum::getAvailableValues(), function ($value): array {
			return [$value];
		});
	}

	/**
	 * @dataProvider validTypesProvider
	 *
	 * @param mixed $value
	 */
	public function testValidTypes($value): void
	{
		$enum = TypeEnum::get($value);
		$this->assertInstanceOf(TypeEnum::class, $enum);
		$this->assertSame($enum->getValue(), $value);
	}

	public function testDuplicateSpecifiedValues(): void
	{
		try {
			DuplicateValuesEnum::get(DuplicateValuesEnum::BAZ);
			$this->fail();
		} catch (\Consistence\Enum\DuplicateValueSpecifiedException $e) {
			$this->assertSame(DuplicateValuesEnum::FOO, $e->getValue());
			$this->assertSame(DuplicateValuesEnum::class, $e->getClass());
		}
	}

}
