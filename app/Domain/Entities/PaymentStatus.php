<?php

declare(strict_types=1);

namespace App\Domain\Entities;

enum PaymentStatus: string  {
	case Pending = 'PENDING';
	case Paid = 'PAID';
	case Canceled = 'CANCELED';
}