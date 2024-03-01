<?php

declare(strict_types=1);

namespace App\Domain\UseCases\PersistPayment;

use App\Domain\{Payer, Payment, UseCases\FindOrCreatePayer\FindOrCreatePayer, UseCases\UnsuccessfulMessage};
use App\DTOs\PaymentDTO;
use App\Repositories\PaymentRepository;

readonly class PersistPayment {

	public function __construct(
		private PaymentRepository $paymentRepository,
		private FindOrCreatePayer $findPayerOrCreate,
	){
	}

	public function handle(PaymentDTO $paymentDTO): PersistPaymentSuccessfulMessage|UnsuccessfulMessage
	{
		$result = $this->findPayerOrCreate->handle($paymentDTO->payerDTO);

		if (!$result instanceof Payer) {
			return $result;
		}

        $payment = new Payment(
            $paymentDTO->transactionAmount,
            $paymentDTO->installments,
            $paymentDTO->token,
            $paymentDTO->paymentMethodId,
            $result,
            $paymentDTO->notificationUrl,
            $paymentDTO->createdAt,
			$paymentDTO->updatedAt,
        );

		try {
			$this->paymentRepository->save($payment);
		}
		catch (\Exception $exception) {
			return new UnsuccessfulMessage('Unable to access this feature at this time!', 0);
		}
		return new PersistPaymentSuccessfulMessage(
			$payment->id,
			$payment->createdAt->format('Y-m-d H:i:s')
		);

	}

}