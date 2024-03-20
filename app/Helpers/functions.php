<?php

declare(strict_types=1);

namespace App\Helpers;

use Exception;
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
			$json = json_encode($this->data);
			if ($json === false) {
				throw new Exception('The provided data is not serializable');
			}

			return $json;
		}
	};
}