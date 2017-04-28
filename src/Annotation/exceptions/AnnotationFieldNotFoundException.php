<?php

declare(strict_types = 1);

namespace Consistence\Annotation;

class AnnotationFieldNotFoundException extends \Consistence\PhpException
{

	/** @var string */
	private $fieldName;

	public function __construct(string $fieldName, \Throwable $previous = null)
	{
		parent::__construct(sprintf('Field name \'%s\' not found on annotation', $fieldName), $previous);
		$this->fieldName = $fieldName;
	}

	public function getFieldName(): string
	{
		return $this->fieldName;
	}

}
