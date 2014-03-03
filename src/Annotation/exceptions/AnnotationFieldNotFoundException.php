<?php

namespace Consistence\Annotation;

class AnnotationFieldNotFoundException extends \Consistence\PhpException implements \Consistence\Annotation\Exception
{

	/** @var string */
	private $fieldName;

	/**
	 * @param string $fieldName
	 * @param \Exception|null $previous
	 */
	public function __construct($fieldName, \Exception $previous = null)
	{
		parent::__construct(sprintf('Field name \'%s\' not found on annotation', $fieldName), $previous);
		$this->fieldName = $fieldName;
	}

	/**
	 * @return string
	 */
	public function getFieldName()
	{
		return $this->fieldName;
	}

}
