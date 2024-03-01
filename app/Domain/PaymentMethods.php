<?php

declare(strict_types=1);

namespace App\Domain;

readonly class PaymentMethods
{
	public function __construct(
		private string $id,
	){}
}