<?php

declare(strict_types = 1);

namespace Consistence\ClassFinder;

interface ClassFinder
{

	/**
	 * @param string $interfaceName
	 * @return string[] array of class names
	 */
	public function findByInterface(string $interfaceName);

}
