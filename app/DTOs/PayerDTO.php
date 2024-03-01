<?php

declare(strict_types=1);

namespace App\DTOs;

use App\Domain\{Payer, Identification};
use App\ValueObjects\{Email};

/**
 * @see Payer
 */
readonly class PayerDTO {
	public function __construct(
		public string $email,
		public string $identificationType,
		public string $identificationNumber,
		private ?string $payerEntityType = null,
		private ?string $payerType = null,
		private ?string $id = null,
	)
	{ }

	public static function fromPayer(Payer $payer): self
	{
		return new self(
			$payer->email,
			$payer->identification->payerIdentificationType,
			$payer->identification->payerIdentificationNumber,
			$payer->payerType,
			$payer->id,
		);
	}

	public function toDomain(): Payer
	{
		return new Payer(
			Email::create($this->email),
			Identification::create(
				$this->identificationType,
				$this->identificationNumber,
			)
		);
	}
}