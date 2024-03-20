<?php

declare(strict_types=1);

namespace App\ValueObjects;

use JsonSerializable;

abstract readonly class BaseValueObject implements JsonSerializable
{

	protected const string INVALID_ARGUMENT = '';

	/**
	 * @throws ValueObjectException
	 */
	final public function __construct(
		public string $value,
	)
	{
		if (!static::isValid($this->value)) {
			throw new ValueObjectException(sprintf(static::INVALID_ARGUMENT, $this->value));
		}
	}

	final public function __toString(): string
	{
		return $this->value;
	}

	public function jsonSerialize(): string
	{
		return $this->value;
	}

	public static function create(string $value): static
	{
		return new static($value);
	}

	abstract static function isValid(string $value): bool;
		
}