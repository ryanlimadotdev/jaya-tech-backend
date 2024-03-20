<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Domain\{Entities\Entity, Entities\Identification, Entities\Payer};
use App\Helpers\Query;
use App\ValueObjects\Email;

class PayerRepository extends BaseRepository
{

	const string TABLE_NAME = 'payer';

	public function getAll(): array
	{
		return [];
	}

	/**
	 * @param string|int $id
	 * @return Payer
	 */
	public function getById(string|int $id): Entity
	{

		$query = Query::create(table: self::TABLE_NAME);

		$query->select()
			->where('id = ?');

		$fetched = $this->execute((string) $query, [$id], \PDO::FETCH_ASSOC, false);

		return $this->fromRow($fetched);


	}

	public function findByIdentificationNumber(string $identificationNumber): ?Payer
	{
		$query = Query::create(table: self::TABLE_NAME);

		$query->select()
			->where('identification_number = ?');

		$fetched = $this->execute((string) $query, [$identificationNumber], \PDO::FETCH_ASSOC, false);

		return $this->fromRow($fetched);
	}

	public function getByIdentificationNumber(string $identificationNumber): Payer
	{
		return $this->findByIdentificationNumber($identificationNumber);
	}

	protected function fromRow(array|false $row): ?Payer
	{
		if ($row === false) {
			return null;
		}

		return new Payer(
			Email::create($row['email']),
			Identification::create(
				$row['identification_type'],
				$row['identification_number'],
			),
			$row['entity_type'],
			$row['type'],
			$row['id'],
		);
	}

	/** @param \App\Domain\Entities\Payer $payment */
	public function save(Entity $payment): void
	{
		$query = Query::create(
			table: self::TABLE_NAME,
		);

		$query->insert([
			'id' => '?',
			'email' => '?',
			'identification_type' => '?',
			'identification_number' => '?',
			'entity_type' => '?',
			'type' => '?',
		]);

		/** @var \PDOStatement $stmt */
		
		$bindings = [
			$payment->id,
			$payment->email,
			$payment->identification->payerIdentificationType,
			$payment->identification->payerIdentificationNumber,
			$payment->payerEntityType,
			$payment->payerType,
		];
		
		$this->execute((string) $query, $bindings);
	}

	/** @var Payer $payment */
	public function update(Entity $payment): void
	{

	}
	/** @var Payer $entity */
	public function delete(string|int $id): void
	{

	}

}