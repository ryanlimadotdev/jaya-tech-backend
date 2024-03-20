<?php

declare(strict_types=1);

namespace HyperfTest\Unit;

use App\ValueObjects\Cnpj;
use App\ValueObjects\ValueObjectException;
use Faker\Factory;
use Faker\Provider\pt_BR\Company;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class CnpjTest extends TestCase
{

	#[DataProvider('cnpjProvider')]
	public function testAssertValidCnpjCreationSuccessful(string $cnpj): void
	{
		$cnpj = new Cnpj($cnpj);
		$this->assertInstanceOf(Cnpj::class, $cnpj);
	}

	public function testAssertInvalidCnpjCreationThrowException(): void
	{
		$this->expectException(ValueObjectException::class);
		new Cnpj('35887182061');
	}

	public static function cnpjProvider(): array
	{
		$faker = new Company(Factory::create('pt_BR'));
		return [[$faker->cnpj()]];
	}
}
