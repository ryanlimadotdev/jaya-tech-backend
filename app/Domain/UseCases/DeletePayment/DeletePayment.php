<?php

declare(strict_types=1);

namespace App\Domain\UseCases\DeletePayment;

use App\Domain\Entities\Payment;
use App\Domain\Entities\PaymentStatus;
use App\Repositories\PaymentRepository;

/**
 * Soft delete a payment by setting his status as "CANCELED"
 */
readonly class DeletePayment
{

	const int UNREACHABLE_ID = 0;

	public function __construct(
		private PaymentRepository $paymentRepository,
	)
	{ }

	public function handle(string $id): DeletePaymentSuccessfulMessage|DeletePaymentError
	{

		$payment = $this->paymentRepository->findById($id);

		if (!($payment instanceof Payment)) {
			return DeletePaymentError::CantLocatePayment;
		}

		$payment->updateStatus(PaymentStatus::Canceled);

		try {
			$this->paymentRepository->update($payment);
		} catch (\Exception) {
			return DeletePaymentError::InfrastructureProblems;
		}

		return new DeletePaymentSuccessfulMessage();

	}
}