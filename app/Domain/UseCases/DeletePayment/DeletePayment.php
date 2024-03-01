<?php

declare(strict_types=1);

namespace App\Domain\UseCases\DeletePayment;

use App\Domain\Payment;
use App\Domain\PaymentStatus;
use App\Domain\UseCases\UnsuccessfulMessage;
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

	public function handle(string $id): DeletePaymentSuccessfulMessage|UnsuccessfulMessage
	{

		/** @var Payment $payment */
		$payment = $this->paymentRepository->findById($id);

		if (!($payment instanceof Payment)) {
			return new UnsuccessfulMessage(code: self::UNREACHABLE_ID);
		}

		$payment->updateStatus(PaymentStatus::Canceled);
		
		try {
			$this->paymentRepository->update($payment);
		} catch (\Exception) {
			return new UnsuccessfulMessage();
		}

		return new DeletePaymentSuccessfulMessage(PaymentStatus::Canceled->value);
		
	}
}