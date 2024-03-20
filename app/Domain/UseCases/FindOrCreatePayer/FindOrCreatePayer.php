<?php

declare(strict_types=1);

namespace App\Domain\UseCases\FindOrCreatePayer;

use App\ValueObjects\ValueObjectException;
use App\Domain\{Entities\DomainEntityException, Entities\Payer};
use App\DTOs\PayerDTO;
use App\Repositories\PayerRepository;

readonly class FindOrCreatePayer
{

	public function __construct(
		private PayerRepository $payerRepository,
	){
	}

	public function handle(PayerDTO $payerData): Payer|FindOrCreatePayerError
	{
		try {
			$providedPayer = $payerData->toDomain();
		} catch (DomainEntityException|ValueObjectException) {
			return FindOrCreatePayerError::InvalidDataProvided;
		}

		$payer = $this->payerRepository->findByIdentificationNumber($payerData->identificationNumber);

		if ($providedPayer->email->value !==
			$payer?->email->value and
			!is_null($payer)
		) {
			return FindOrCreatePayerError::ConflictingDataProvided;
		}

		if ($payer instanceof Payer) {
			return $payer;
		}


		try {
			$this->payerRepository->save($providedPayer);
		} catch (\PDOException $e) {
			if ($e->getCode() === '23000') {
				return FindOrCreatePayerError::ConflictingDataProvided;
			}
			return FindOrCreatePayerError::InfrastructureProblems;
		}
        
		return $payer;
	}
}