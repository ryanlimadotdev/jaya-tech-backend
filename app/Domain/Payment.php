<?php

declare(strict_types=1);

namespace App\Domain;

use DateTime;
use Ramsey\Uuid\Nonstandard\Uuid;

class Payment extends Entity implements \JsonSerializable
{
	use Getters;

	public function __construct(
		private float $transactionAmmount,
		private int $installments,
		private string $token,
		private string $paymentMethodId,
		private Payer $payer,
		private string $notificationUrl,
		private DateTime $createdAt,
		private DateTime $updatedAt,
		private PaymentStatus $status = PaymentStatus::Pending,
		?string $id = null,
		)
	{
		parent::__construct($id);
	}

	public function updateStatus(PaymentStatus $status): void
	{
		$this->updatedAt = new DateTime();
		$this->status = $status;
	}

	public function jsonSerialize(): object
	{
		return (object)[
			'id'	=> $this->id,
			'status'	=> $this->status,
			'transaction_amount' => $this->transactionAmmount,
			'installments' => $this->installments,
			'token' => $this->token,
			'payment_method_id'	=> $this->paymentMethodId,
			'payer' => $this->payer,
			'notification_url' => $this->notificationUrl,
			'created_at' => $this->createdAt->format('Y-m-d'),
			'updated_at' => $this->updatedAt->format('Y-m-d'),
		];	
	}
}