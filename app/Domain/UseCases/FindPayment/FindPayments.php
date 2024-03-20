<?php

declare(strict_types=1);

namespace App\Domain\UseCases\FindPayment;

use App\Domain\Entities\Payment;
use App\Repositories\PaymentRepository;
use DomainException;

/**
 * @method array<Payment> getAll()
 * @method Payment getById(int|string $id)
 * @method Payment|null findById(int|string $id)
 */
readonly class FindPayments
{
	public function __construct(
		private PaymentRepository $paymentRepository,
	)
	{ }

	public function __call(string $method, array $arguments): mixed
	{

		If (in_array($method, ['getAll', 'getById', 'findById'])) {
			return $this->paymentRepository->$method(...$arguments);
		}

		throw new DomainException();

	}

}