<?php

declare(strict_types=1);

namespace App\Domain\Entities;

use App\ValueObjects\Cnpj;
use App\ValueObjects\Cpf;
use App\ValueObjects\ValueObjectException;
use JsonSerializable;

readonly class Identification implements JsonSerializable
{
	use Getters;
	
	public function __construct(
		public string $payerIdentificationType,
		public Cpf|Cnpj $payerIdentificationNumber,
	)
	{ }

	/**
	 * @throws ValueObjectException
	 */
	public static function create(string $identificationType, string $number): Identification
	{
		return new Identification(
			$identificationType,
			match (strtoupper($identificationType)) {
				'CNPJ' => new Cnpj($number),
				'CPF'=> new Cpf($number),
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