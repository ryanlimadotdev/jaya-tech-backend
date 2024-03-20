<?php

declare(strict_types=1);

namespace App\Domain\UseCases\PersistPayment;

readonly class PersistPaymentSuccessfulMessage implements \JsonSerializable
{

	public function __construct(
		public string $id,
		public string $createdAt,
	) {
	}

	public function jsonSerialize(): mixed
	{
		return (object) [
			'id' => $this->id,
			'created_at' => $this->createdAt,
		];
	}
}