<?php

declare(strict_types=1);

namespace App\Domain\UseCases\FindOrCreatePayer;

use InvalidArgumentException;
use App\Domain\{Payer, UseCases\UnsuccessfulMessage};
use App\DTOs\PayerDTO;
use App\Repositories\PayerRepository;

readonly class FindOrCreatePayer
{

	const int InvalidDataProvided = 1;

	public function __construct(
		private PayerRepository $payerRepository,
	){
	}

	public function handle(PayerDTO $payerData): Payer|UnsuccessfulMessage
	{
		$payer = $this->payerRepository->findByIdentificationNumber($payerData->identificationNumber);

        if ($payer instanceof Payer) {
			return $payer;
		}


		try {
			$payer = $payerData->toDomain();
			$this->payerRepository->save($payer);
		} catch (\Throwable $e) {
			$message = 'Unable to access this feature at this time!';
			$code = 0;
			if ($e instanceof InvalidArgumentException) {
				$message = 'Invalid data entry';
				$code = self::InvalidDataProvided;
			}
			return new UnsuccessfulMessage('Unable to access this feature at this time!', $code);
		}
        
		return $payer;
	}
}