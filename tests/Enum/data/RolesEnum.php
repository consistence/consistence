<?php

namespace Consistence\Enum;

class RolesEnum extends \Consistence\Enum\MultiEnum
{

	/**
	 * @return string
	 */
	public static function getSingleEnumClass()
	{
		return RoleEnum::class;
	}

}
