<?php

declare(strict_types=1);

namespace App\DTOs;

use DateTime;
use App\Domain\Payment;

/**
 * @see App\Domain\Payment
 */
readonly class PaymentDTO {
	public function __construct(
		public int|float $transactionAmount,
		public int $installments,
		public string $token,
		public string $paymentMethodId,
		public PayerDTO $payerDTO,
		public string $notificationUrl,
		public DateTime $createdAt,
		public DateTime $updatedAt,
		public ?string $status = null,
		public ?string $id = null,
	)
	{ }

	public static function fromPayer(Payment $payment): self
	{
		return new self(
			$payment->transactionAmount,
			$payment->installments,
			$payment->token,
			$payment->paymentMethodId,
			PayerDTO::fromPayer($payment->payer),
			$payment->notificationUrl,
			$payment->createdAt,
			$payment->updatedAt,
			$payment->status,
			$payment->id,
		);
	}

	public function toDomain(): Payment
	{
		return new Payment(
			$this->transactionAmount,
			$this->installments,
			$this->token,
			$this->paymentMethodId,
			$this->payerDTO->toDomain(),
			$this->notificationUrl,
			$this->createdAt,
			$this->updatedAt,
			$this->status,
			$this->id
		);
	}
}