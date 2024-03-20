<?php

declare(strict_types=1);

namespace HyperfTest\Unit;

use App\Domain\Entities\Identification;
use App\Domain\Entities\Payer;
use App\ValueObjects\Email;
use Faker\Factory;
use Faker\Provider\pt_BR\{Company, Person};
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class PayerTest  extends TestCase
{

	#[DataProvider('payerDataProvider')]
	public function testMinimalDataPayerCreation(string $email, string $type, string $number): void
	{
		$payer = new Payer(
			Email::create($email),
			Identification::create($type, $number),
		);

		$this->assertInstanceOf(Payer::class, $payer);
	}

	#[DataProvider('payerDataProvider')]
	public function testFullDataPayerCreation(
		string $email,
		string $identificationType,
		string $identificationNumber,
		string $entityType,
		string $type,
		string $id,
	): void
	{
		$payer = new Payer(
			Email::create($email),
			Identification::create($identificationType, $identificationNumber),
			$entityType,
			$type,
			$id,
		);

		$this->assertInstanceOf(Payer::class, $payer);
	}

	public static function payerDataProvider(): array
	{
		$factory = Factory::create('pt_BR');
		$company = new Company($factory);
		$person = new Person($factory);

		return [
			[$factory->email(), 'cpf', $person->cpf(), 'individual', 'costumer', $factory->uuid()],
			[
				$factory->email(),
				'cnpj',
				$company->cnpj(),
				$factory->randomElement(['LTDA', 'S.A', 'ONG']),
				$factory->randomElement(['costumer', 'partner']),
				$factory->uuid()
			],
		];
	}
}