<?php

declare(strict_types=1);

namespace App\Helpers;

use Hyperf\Contract\Jsonable;

function json(mixed $data): Jsonable
{
	return new readonly class($data) implements Jsonable
	{
		public function __construct(
			private mixed $data,
		){ }

		public function __toString(): string
		{
			return json_encode($this->data);
		}
	};
}