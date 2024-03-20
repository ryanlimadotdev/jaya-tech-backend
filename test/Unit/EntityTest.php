<?php

declare(strict_types=1);

namespace HyperfTest\Unit;

use App\Domain\Entities\Entity;
use PHPUnit\Framework\TestCase;

class EntityTest extends TestCase
{

	public function test__construct()
	{
		$uuidPattern = '/^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i';
		$anonymousEntity = new class() extends Entity{};
		$this->assertMatchesRegularExpression($uuidPattern, $anonymousEntity->id);
	}
}
