<?php

declare(strict_types=1);

namespace App\ValueObjects;

use Respect\Validation\Validator;

readonly class Email extends BaseValueObject
{

	const string INVALID_ARGUMENT = 'The value "%s" is not a valid email!';

	public static function isValid(string $value): bool
	{
		return Validator::email()->validate($value);
	}

}