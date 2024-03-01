<?php

declare(strict_types=1);

namespace App\Domain\UseCases;

class UnsuccessfulMessage extends \Exception implements \JsonSerializable
{
	
	public function __construct(
		string $message = "",
		int $code = 0,
	)
	{
		parent::__construct($message, $code);
	}

	public function jsonSerialize(): mixed
	{
		return (object) [
				'status' => 'error',
				'error' => (object) [
					'code' => $this->getCode(),
					'message' => $this->getMessage(),
				]
		];
	}
}
