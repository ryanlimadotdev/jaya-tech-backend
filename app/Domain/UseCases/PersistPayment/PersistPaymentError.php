<?php

declare(strict_types=1);

namespace App\Domain\UseCases\PersistPayment;

enum PersistPaymentError
{
	case InfrastructureProblems;
	case InvalidDataProvided ;
	case CantCreateOrRetrievePayer;
	case ConflictingDataProvided;
}
