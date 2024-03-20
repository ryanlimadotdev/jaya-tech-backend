<?php

declare(strict_types=1);

namespace App\Domain\Entities;

use DateTimeImmutable;
use DateTimeInterface;

class Payment extends Entity implements \JsonSerializable
{
	use Getters;

	/**
	 * @throws DomainEntityException
	 */
	public function __construct(
		private readonly float             $transactionAmount,
		private readonly int               $installments,
		private readonly string            $token,
		private readonly string            $paymentMethodId,
		private readonly Payer             $payer,
		private readonly string            $notificationUrl,
		private readonly DateTimeInterface $createdAt,
		private DateTimeInterface          $updatedAt,
		private PaymentStatus              $status = PaymentStatus::Pending,
		?string                            $id = null,
		)
	{
		$this->isValid();
		parent::__construct($id);
	}

	public function updateStatus(PaymentStatus $status): void
	{
		$this->updatedAt = new DateTimeImmutable();
		$this->status = $status;
	}

	public function jsonSerialize(): object
	{
		return (object)[
			'id'	=> $this->id,
			'status'	=> $this->status,
			'transaction_amount' => $this->transactionAmount,
			'installments' => $this->installments,
			'token' => $this->token,
			'payment_method_id'	=> $this->paymentMethodId,
			'payer' => $this->payer,
			'notification_url' => $this->notificationUrl,
			'created_at' => $this->createdAt->format('Y-m-d'),
			'updated_at' => $this->updatedAt->format('Y-m-d'),
		];	
	}

	/**
	 * @throws DomainEntityException
	 */
	protected function isValid(): void
	{
		if ($this->transactionAmount < 0) {
			throw new DomainEntityException('Transaction amount can\'t be a negative number', 0);
		}

		if ($this->installments < 0) {
			throw new DomainEntityException('Installments can\'t be a negative number', 1);
		}
	}
}