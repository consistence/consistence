<?php

declare(strict_types = 1);

namespace Consistence\Enum;

class RolesEnum extends \Consistence\Enum\MultiEnum
{

	public static function getSingleEnumClass(): string
	{
		return RoleEnum::class;
	}

}
