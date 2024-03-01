<?php

declare(strict_types=1);

namespace App\Helpers;

class Query implements \Stringable
{

	protected bool $hasWhere = false;
	protected bool $possibleDataLoss = false;

	final public function __construct(
		private string $query = '',
		private ?string $table = null,
		private bool $secure = true,
	)
	{ }

	public static function create(
		string $query = '',
		?string $table = null,
		bool $secure = true,
	): static
	{
		return new static($query, $table, $secure);
	}

	protected static function getColumnsString(array $columns): string
	{
		if (empty($columns)) {
			return '*';
		}
		return implode(', ', $columns);
	}

	public function hasWhere(): bool
	{
		return $this->hasWhere;
	}

	public function insert(
		string|array $values,
		array $columns = [],
		?string $table = null
	): static
	{	
		$table = $this->table ?? $table;

		$columnsString = (empty($columns) and !array_is_list($values)) ? 
			self::getColumnsString(array_keys($values)):
			self::getColumnsString($columns);


		if (is_array($values)) {
			$values = implode(', ', $values);
		}

		if ($columnsString !== '*') {
			$columnsString = "($columnsString)";
		}


		$this->query = sprintf('INSERT INTO %s %s VALUES ( %s )', $table, $columnsString, $values);
		

		return $this;
	}

	public function select(
		array $columns = [],
		?string $table = null,
	): static
	{

		$table = $this->table ?? $table;

		$columnsString = self::getColumnsString($columns);

		$this->query = sprintf('SELECT %s', $columnsString);

		if (!is_null($table)) {
			return $this->from($table);
		}

		return $this;
	}

	public function update(
		string|array $values,
		?string $table = null,
	): static
	{
		$this->possibleDataLoss = true;

		$table = $this->table ?? $table;

		$update = [];
		foreach ($values as $column => $value) {
			$update[] = sprintf('%s = %s', $column, $value);
		}

		$update = implode(', ', $update);

		$this->query = sprintf('UPDATE %s SET %s', $table, $update);

		return $this;
	}

	public function delete(
		?string $where = null,
		?string $table = null,
	): static
	{
		$this->possibleDataLoss = true;

		$table = $this->table ?? $table;

		$this->query = sprintf('DELETE FROM %s', $table);

		if (!is_null($where)) {
			return $this->where($where);
		}

		return $this;
	}

	public function from(string $table): static
	{
		$this->query .= " FROM $table";
		return $this;
	}


	public function where(string $clause): static
	{
		$this->hasWhere = true;
		$this->query .= " WHERE $clause";
		return $this;
	}

	public function limit(int|string $limit): static
	{
		$this->query .= sprintf(' LIMIT %s', (string) $limit);
		return $this;
	}
	
	public function	__toString(): string
	{
		if (!$this->hasWhere and $this->secure and $this->possibleDataLoss) {
			throw new \Exception('No where clause provided');
		} 

		return $this->query;
	}
}