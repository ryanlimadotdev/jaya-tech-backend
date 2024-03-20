<?php

declare(strict_types=1);

namespace App\Domain\UseCases\PersistPayment;

use DomainException;
use App\Domain\{Entities\Payer,
	UseCases\FindOrCreatePayer\FindOrCreatePayer,
	UseCases\FindOrCreatePayer\FindOrCreatePayerError};
use App\DTOs\PaymentDTO;
use App\Repositories\PaymentRepository;

readonly class PersistPayment {

	public function __construct(
		private PaymentRepository $paymentRepository,
		private FindOrCreatePayer $findPayerOrCreate,
	){
	}

	public function handle(PaymentDTO $paymentDTO): PersistPaymentSuccessfulMessage|PersistPaymentError|FindOrCreatePayerError
	{
		$result = $this->findPayerOrCreate->handle($paymentDTO->payerDTO);

		if (!$result instanceof Payer) {
			return $result;
		}

		try {
			$payment = $paymentDTO->toDomain($result);
			$this->paymentRepository->save($payment);
		}
		catch (DomainException) {
			return PersistPaymentError::InvalidDataProvided;
		} catch (\PDOException $e) {
			return PersistPaymentError::InfrastructureProblems;
		}

		return new PersistPaymentSuccessfulMessage(
			$payment->id,
			$payment->createdAt->format('Y-m-d H:i:s'),
		);
	}

}