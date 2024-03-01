<?php

declare(strict_types=1);

namespace App\Domain;

/**
 * Provides a way to access values from private properties
 * without allowing modify then 
 */
trait Getters
{
	public function __get(string $property): mixed
	{
		return $this->$property;
	}	
}