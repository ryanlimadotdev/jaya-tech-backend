<?php

declare(strict_types=1);

namespace HyperfTest\Unit;

use App\Domain\Entities\Identification;
use App\ValueObjects\ValueObjectException;
use Faker\Factory;
use Faker\Provider\pt_BR\Person;
use Faker\Provider\pt_BR\Company;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class IdentificationTest extends TestCase
{

	#[DataProvider('identificationDataProvider')]
	public function testAssertValidCPFCreationSuccessful(string $type, string $number)
	{
		$this->assertInstanceOf(Identification::class, Identification::create(
			$type,
			$number,
		));
	}


	#[DataProvider('invalidIdentificationDataProvider')]
	public function testAssertInvalidDataThrowsValueObjectException(string $type, string $number)
	{
		$this->expectException(ValueObjectException::class);
		Identification::create(
			$type,
			$number,
		);
	}

	public static function identificationDataProvider(): array
	{
		$factory = Factory::create('pt_BR');
		$person = new Person($factory);
		$company = new Company($factory);

		return [['cpf', $person->cpf(false)], ['cnpj', $company->cnpj(false)]];
	}

	public static function invalidIdentificationDataProvider(): array
	{
		$factory = Factory::create('pt_BR');
		$person = new Person($factory);
		$company = new Company($factory);

		return [['cnpj', $person->cpf(false)], ['cpf', $company->cnpj(false)]];
	}

}
