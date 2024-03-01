<?php

declare(strict_types=1);

namespace App\Repositories;

use Hyperf\DB\DB;

abstract class BaseRepository implements IRepository
{
	protected static DB $db;

	public function __construct(
		DB $db,
	) {
		if (!isset(self::$db)) {
			self::$db = $db;
		}
	}

	public function execute(string $sql, array $bindings = [], int $mode = \PDO::FETCH_DEFAULT, bool $all = true): mixed
	{
		return self::$db->run(
			function (\PDO $pdo) use ($sql, $bindings, $mode, $all) {
				$statement = $pdo->prepare($sql);
				/**
				 * The following statement will be executed in a scope where the method is available
				 */
				$this->bindValues($statement, $bindings); // @phpstan-ignore-line
				$statement->execute();
				if ($all) {
					return $statement->fetchAll($mode);
				}
				return $statement->fetch($mode);
			}
		);

	}

}