<?php

declare(strict_types=1);

namespace App\Domain;

use App\ValueObjects\Cnpj;
use App\ValueObjects\Cpf;
use JsonSerializable;

readonly class Identification implements JsonSerializable
{
	use Getters;
	
	public function __construct(
		public string $payerIdentificationType,
		public Cpf|Cnpj $payerIdentificationNumber,
	)
	{ }

	public static function create(string $identificationType, string $number): Identification
	{
		return new Identification(
			$identificationType,
			match (strtoupper($identificationType)) {
				'CPF' => new Cnpj($number),
				'CNPJ'=> new Cpf($number),
		});
	}

	public function jsonSerialize(): object
	{
		return (object) [
			"type" => $this->payerIdentificationType,
			"number" => $this->payerIdentificationNumber,
		];
	}
}