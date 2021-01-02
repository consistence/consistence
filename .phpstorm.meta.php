<?php declare(strict_types = 1);

// Fixes autocompletion for some of the ArrayType functions
// @see https://www.jetbrains.com/help/phpstorm/ide-advanced-metadata.html

namespace PHPSTORM_META {

	use Consistence\Type\ArrayType\ArrayType;

	override(ArrayType::findValue(0), elementType(0));
	override(ArrayType::getValue(0), elementType(0));
	override(ArrayType::findValueByCallback(0), elementType(0));
	override(ArrayType::getValueByCallback(0), elementType(0));
	override(ArrayType::filterByCallback(0), type(0));
	override(ArrayType::filterValuesByCallback(0), type(0));
	override(ArrayType::uniqueValues(0), type(0));
	override(ArrayType::uniqueValuesByCallback(0), type(0));

}
