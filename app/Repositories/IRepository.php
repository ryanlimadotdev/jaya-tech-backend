<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Domain\{Entities\Entity};

interface IRepository
{
	public function getAll(): array;
	public function getById(string|int $id): Entity;
	public function save(Entity $payment): void;
	public function update(Entity $payment): void;
	public function delete(string|int $id): void;
}