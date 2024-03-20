<?php

declare(strict_types=1);

namespace App\Domain\UseCases\DeletePayment;

enum DeletePaymentError
{
	case InfrastructureProblems;
	case CantLocatePayment;
}
