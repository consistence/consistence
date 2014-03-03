<?php

namespace Consistence\ClassFinder;

interface ClassFinder
{

	/**
	 * @param string $interfaceName
	 * @return string[] array of class names
	 */
	public function findByInterface($interfaceName);

}
