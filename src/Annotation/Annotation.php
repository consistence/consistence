<?php

declare(strict_types = 1);

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
	private function __construct(string $name, array $fields = [], $value = null)
	{
		Type::checkType($name, 'string');
		$this->name = $name;
		$this->fields = $fields;
		$this->value = $value;
	}

	public function getName(): string
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

	public function getField(string $fieldName): AnnotationField
	{
		Type::checkType($fieldName, 'string');
		try {
			return ArrayType::getValueByCallback(
				$this->getFields(),
				function (AnnotationField $annotationField) use ($fieldName): bool {
					return $annotationField->getName() === $fieldName;
				}
			);
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

	public static function createAnnotationWithoutParams(string $name): self
	{
		return new self($name);
	}

	/**
	 * @param string $name
	 * @param mixed $value
	 * @return self
	 */
	public static function createAnnotationWithValue(string $name, $value): self
	{
		return new self($name, [], $value);
	}

	/**
	 * @param string $name
	 * @param \Consistence\Annotation\AnnotationField[] $fields
	 * @return self
	 */
	public static function createAnnotationWithFields(string $name, array $fields): self
	{
		return new self($name, $fields);
	}

}
