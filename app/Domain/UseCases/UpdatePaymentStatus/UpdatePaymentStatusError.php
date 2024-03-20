<?php

declare(strict_types=1);

namespace App\Domain\UseCases\UpdatePaymentStatus;

enum UpdatePaymentStatusError
{
	case InfrastructureProblems;
	case CantLocatePayment;
	case InvalidStatusProvided;
}
