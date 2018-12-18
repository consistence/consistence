<?php

declare(strict_types = 1);

namespace Consistence\Enum;

use Consistence\Type\Type;

class InvalidEnumValueException extends \Consistence\PhpException
{

	/** @var mixed */
	private $value;

	/** @var mixed[] */
	private $availableValues;

	/** @var string */
	private $enumClassName;

	/**
	 * @param mixed $value
	 * @param string $enumClassName
	 * @param \Throwable|null $previous
	 */
	public function __construct($value, string $enumClassName, ?\Throwable $previous = null)
	{
		if (!is_subclass_of($enumClassName, Enum::class)) {
			// @codeCoverageIgnoreStart
			// cannot be tested because it throws general exception
			throw new \Exception(sprintf(
				'"%s" is not a subclass of "%s"',
				$enumClassName,
				Enum::class
			));
			// @codeCoverageIgnoreEnd
		}

		$availableValues = $enumClassName::getAvailableValues();

		parent::__construct(sprintf(
			'%s [%s] is not a valid value for %s, accepted values: %s',
			$value,
			Type::getType($value),
			$enumClassName,
			implode(', ', $availableValues)
		), $previous);

		$this->value = $value;
		$this->availableValues = $availableValues;
		$this->enumClassName = $enumClassName;
	}

	/**
	 * @return mixed
	 */
	public function getValue()
	{
		return $this->value;
	}

	/**
	 * @return mixed[]
	 */
	public function getAvailableValues(): array
	{
		return $this->availableValues;
	}

	public function getEnumClassName(): string
	{
		return $this->enumClassName;
	}

}
