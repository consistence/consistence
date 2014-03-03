<?php

namespace Consistence\Annotation;

use Consistence\Type\ArrayType\ArrayType;
use Consistence\Type\Type;

class Annotation extends \Consistence\ObjectPrototype
{

	/** @var string */
	private $name;

	/** @var \Consistence\Annotation\AnnotationField[] */
	private $fields;

	/** @var mixed */
	private $value;

	/**
	 * @param string $name
	 * @param \Consistence\Annotation\AnnotationField[] $fields
	 * @param mixed|null $value
	 */
	private function __construct($name, array $fields = [], $value = null)
	{
		Type::checkType($name, 'string');
		$this->name = $name;
		$this->fields = $fields;
		$this->value = $value;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @return \Consistence\Annotation\AnnotationField[]
	 */
	public function getFields()
	{
		return $this->fields;
	}

	/**
	 * @param string $fieldName
	 * @return \Consistence\Annotation\AnnotationField
	 */
	public function getField($fieldName)
	{
		Type::checkType($fieldName, 'string');
		try {
			return ArrayType::getValueByCallback($this->getFields(), function (AnnotationField $annotationField) use ($fieldName) {
				return $annotationField->getName() === $fieldName;
			});
		} catch (\Consistence\Type\ArrayType\ElementDoesNotExistException $e) {
			throw new \Consistence\Annotation\AnnotationFieldNotFoundException($fieldName, $e);
		}
	}

	/**
	 * @return mixed|null
	 */
	public function getValue()
	{
		return $this->value;
	}

	/**
	 * @param string $name
	 * @return self
	 */
	public static function createAnnotationWithoutParams($name)
	{
		return new self($name);
	}

	/**
	 * @param string $name
	 * @param mixed $value
	 * @return self
	 */
	public static function createAnnotationWithValue($name, $value)
	{
		return new self($name, [], $value);
	}

	/**
	 * @param string $name
	 * @param \Consistence\Annotation\AnnotationField[] $fields
	 * @return self
	 */
	public static function createAnnotationWithFields($name, array $fields)
	{
		return new self($name, $fields);
	}

}
