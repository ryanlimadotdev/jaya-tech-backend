<?php

declare(strict_types=1);

namespace App\Domain\Entities;

readonly class PaymentMethods
{
	public function __construct(
		public string $id,
	){}
}