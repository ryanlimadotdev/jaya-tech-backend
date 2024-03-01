<?php

declare(strict_types=1);

namespace App\Domain;

use App\ValueObjects\Email;
use Ramsey\Uuid\Nonstandard\Uuid;

class Payer extends Entity implements \JsonSerializable
{
	use Getters;
	public function __construct(
		private Email $email,
		private Identification $identification,
		private string $payerEntityType = 'individual',
		private string $payerType = 'customer',
		?string $id = null,
	)
	{
		parent::__construct($id);
	}

	public function jsonSerialize(): object
	{
		return (object) [
				"entity_type" => $this->payerEntityType,
				"type" => $this->payerType,
				"email" => $this->email,
				"identification" => $this->identification
		];
	}
}