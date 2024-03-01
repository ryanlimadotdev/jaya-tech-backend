<?php

declare(strict_types=1);

namespace App\Domain\UseCases\UpdatePaymentStatus;

readonly class UpdatePaymentStatusSuccessfulMessage implements \JsonSerializable
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