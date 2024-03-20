<?php

declare(strict_types=1);

namespace App\Domain\Entities;

/**
 * Provides a way to access values from private properties
 * without allowing to modify then
 */
trait Getters
{
	public function __get(string $property): mixed
	{
		return $this->$property;
	}	
}