<?php

declare(strict_types=1);

namespace App\DTOs;

use App\Domain\{Entities\Identification, Entities\Payer};
use App\ValueObjects\{Email, ValueObjectException};

/**
 * @see Payer
 */
readonly class PayerDTO {
	public function __construct(
		public string $email,
		public string $identificationType,
		public string $identificationNumber,
		public ?string $payerEntityType = null,
		public ?string $payerType = null,
		public ?string $id = null,
	)
	{ }

	public static function fromPayer(Payer $payer): self
	{
		return new self(
			(string) $payer->email,
			$payer->identification->payerIdentificationType,
			(string) $payer->identification->payerIdentificationNumber,
			$payer->payerType,
			$payer->id,
		);
	}

	/**
	 * @throws ValueObjectException
	 */
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