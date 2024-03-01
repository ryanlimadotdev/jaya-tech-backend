<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Helpers\Query;
use App\Domain\{Entity, Payment, PaymentStatus};
use DateTime;
use Hyperf\DB\DB;
use PDO;

class PaymentRepository extends BaseRepository
{
	const string TABLE_NAME = 'payments';

	public function __construct(
		private readonly PayerRepository $payerRepository,
		DB $db,
	) {
		parent::__construct($db);
	}


	/**
	 * @return array<Payment>
	 */
	public function getAll(): array
	{
		$query = Query::create(table: self::TABLE_NAME)->select();
		$fetched = $this->execute((string) $query, [], PDO::FETCH_ASSOC);

		return array_map($this->fromRow(...), $fetched === false ? [] : $fetched);
	}

	public function findById(string|int $id): ?Entity
	{
		$query = Query::create(
			table: self::TABLE_NAME,
		);

		$query->select()->where('id = ?');

		$fetched = $this->execute((string) $query, [$id], PDO::FETCH_ASSOC, false);


		return $this->fromRow($fetched);
	}

	public function getById(string|int $id): Entity
	{
		return $this->findById($id) ;
	}

	/**
	 * @param Payment $payment
	 * @return void
	 */
	public function save(Entity $payment): void
	{
		$query = Query::create(
			table: self::TABLE_NAME,
		);

		$query->insert([
			'id' => '?',
			'transaction_amount' => '?',
			'installments' => '?',
			'token' => '?',
			'payment_method_id' => '?',
			'notification_url' => '?',
			'status' => '?',
			'created_at' => '?',
			'updated_at' => '?',
			'payer_id' => '?'
		]);

		/** @var PDOStatement $stmt */
		
		$bindings = [
			$payment->id,
			$payment->transactionAmmount,
			$payment->installments,
			$payment->token,
			$payment->paymentMethodId,
			$payment->notificationUrl,
			$payment->status->value,
			$payment->createdAt->format('Y-m-d H:i:s'),
			$payment->updatedAt->format('Y-m-d H:i:s'),
			$payment->payer->id,
		];

		$this->execute((string) $query, $bindings);

	}

	/**
	 * @param Payment $payment
	 * @return void
	 */
	public function update(Entity $payment): void
	{
		$query = Query::create(table: self::TABLE_NAME);
		$query->update([
			'transaction_amount' => '?',
			'installments' => '?',
			'token' => '?',
			'payment_method_id' => '?',
			'notification_url' => '?',
			'status' => '?',
			'created_at' => '?',
			'updated_at' => '?',
			'payer_id' => '?'
		]);

		$query->where("id = ?");

		$bindings = [
			$payment->transactionAmmount,
			$payment->installments,
			$payment->token,
			$payment->paymentMethodId,
			$payment->notificationUrl,
			$payment->status->value,
			$payment->createdAt->format('Y-m-d H:i:s'),
			$payment->updatedAt->format('Y-m-d H:i:s'),
			$payment->payer->id,
			$payment->id,
		];
		
		$this->execute((string) $query, $bindings);
		
		
	}
	

	/**
	 * @param string|int $id
	 * @return void
	 */
	public function delete(string|int $id): void
	{
		$query = Query::create(
			table: self::TABLE_NAME,
		);

		$query->delete('id = ?');

		$this->execute((string) $query, [$id]);
	}


	/**
	 * @param array|false $row
	 * @return Payment|null
	 */
	protected function fromRow(array|false $row): ?Payment
	{
		if ($row === false) {
			return null;
		}

		return new Payment(
			floatval($row['transaction_amount']),
			$row['installments'],
			$row['token'],
			$row['payment_method_id'],
			$this->payerRepository->getById($row['payer_id']),
			$row['notification_url'],
			DateTime::createFromFormat('Y-m-d H:i:s', $row['created_at']),
			DateTime::createFromFormat('Y-m-d H:i:s', $row['updated_at']),
			PaymentStatus::from($row['status']),
			$row['id'],
		);
	}

}