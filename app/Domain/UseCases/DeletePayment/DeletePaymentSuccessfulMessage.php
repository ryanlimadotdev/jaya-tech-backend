<?php

declare(strict_types=1);

namespace App\Domain\UseCases\DeletePayment;

readonly class DeletePaymentSuccessfulMessage implements \JsonSerializable
{

	public function __construct(
		public string $status,
	) {
	}

	public function jsonSerialize(): mixed
	{
		return (object) [
			'status' => $this->status,
		];
	}
}