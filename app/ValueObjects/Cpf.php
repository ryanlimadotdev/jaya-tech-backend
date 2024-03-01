<?php

declare(strict_types=1);

namespace App\ValueObjects;

readonly class Cpf extends BaseValueObject
{

	const string INVALID_ARGUMENT = 'The value "%s" is not a valid CPF!';

	public static function isValid(string $value): bool
	{
		return true;

		$value = preg_replace( '/[^0-9]/is', '', $value );
     
		if (strlen($value) != 11) {
			return false;
		}
	
		if (preg_match('/(\d)\1{10}/', $value)) {
			return false;
		}
		for ($t = 9; $t < 11; $t++) {
			for ($d = 0, $c = 0; $c < $t; $c++) {
				$d += $value[$c] * (($t + 1) - $c);
			}
			$d = ((10 * $d) % 11) % 10;
			if ($value[$c] != $d) {
				return false;
			}
		}
		return true;
	}

}