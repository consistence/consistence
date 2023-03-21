<?php

declare(strict_types = 1);

namespace Consistence\Enum;

/**
 * This enum is here only because code executed in data providers is not counted as covered and since Enum
 * is implemented using flyweight pattern, each enum type's initialization can be done only once. Also, data
 * providers are executed before all tests, so the initialization of every enum used in data providers will always
 * be done there.
 *
 * This enum should not be used for any other tests other than the special ones dedicated to cover this behaviour.
 */
class CoverageMappedMultiEnum extends \Consistence\Enum\MultiEnum
{

	public static function getSingleEnumClass(): ?string
	{
		return CoverageEnum::class;
	}

}
