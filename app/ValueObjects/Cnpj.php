<?php

declare(strict_types=1);

namespace App\ValueObjects;

use Respect\Validation\Validator;

readonly class Cnpj extends BaseValueObject
{

	const string INVALID_ARGUMENT = 'The value "%s" is not a valid CNPJ!';

	public static function isValid(string $value): bool
	{
		return Validator::cnpj()->validate($value);
	}

}