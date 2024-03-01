<?php

declare(strict_types=1);

namespace App\Domain\UseCases\UpdatePaymentStatus;

use App\Domain\Payment;
use App\Domain\PaymentStatus;
use App\Domain\UseCases\UnsuccessfulMessage;
use App\Repositories\PaymentRepository;

class UpdatePaymentStatus
{

	const int UNREACHABLE_ID = 0;

	public function __construct(
		private PaymentRepository $paymentRepository,
	)
	{ }	

	public function handle(string $id, string $newStatus): UpdatePaymentStatusSuccessfulMessage|UnsuccessfulMessage
	{

		$payment = $this->paymentRepository->findById($id);

		if (! ($payment instanceof Payment)) {
			return  new UnsuccessfulMessage(code: self::UNREACHABLE_ID);
		}

		$payment->updateStatus(PaymentStatus::from(strtoupper($newStatus)));

		try {
			$this->paymentRepository->update($payment);
		} catch (\Exception $e) {
			return new UnsuccessfulMessage();
		}

		return new UpdatePaymentStatusSuccessfulMessage($newStatus);
		
	}
}