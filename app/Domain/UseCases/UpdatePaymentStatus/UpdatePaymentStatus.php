<?php

declare(strict_types=1);

namespace App\Domain\UseCases\UpdatePaymentStatus;


use App\Domain\Entities\Payment;
use App\Domain\Entities\PaymentStatus;
use App\Repositories\PaymentRepository;

readonly class UpdatePaymentStatus
{

	public function __construct(
		private PaymentRepository $paymentRepository,
	)
	{ }	

	public function handle(string $id, string $newStatus): UpdatePaymentStatusSuccessfulMessage|UpdatePaymentStatusError
	{

		$payment = $this->paymentRepository->findById($id);

		if (! ($payment instanceof Payment)) {
			return UpdatePaymentStatusError::CantLocatePayment;
		}

		try {
			$newStatus = PaymentStatus::from(strtoupper($newStatus));
		} catch (\ValueError $e) {
			return UpdatePaymentStatusError::InvalidStatusProvided;
		}

		$payment->updateStatus($newStatus);

		try {
			$this->paymentRepository->update($payment);
		} catch (\PDOException $e) {
			return UpdatePaymentStatusError::InfrastructureProblems;
		}

		return new UpdatePaymentStatusSuccessfulMessage($newStatus->value);
		
	}
}