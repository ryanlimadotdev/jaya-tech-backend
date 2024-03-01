<?php

declare(strict_types=1);

namespace App\Domain;

use Ramsey\Uuid\Nonstandard\Uuid;

abstract class Entity
{
	use Getters;

	public function __construct(
		protected ?string $id = null,
	)
	{
		if (is_null($this->id)) {
			$this->id = Uuid::uuid4()->toString();
		}
	}
}