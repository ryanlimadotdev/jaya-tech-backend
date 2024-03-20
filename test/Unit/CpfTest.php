<?php

declare(strict_types=1);

namespace HyperfTest\Unit;

use App\ValueObjects\Cpf;
use App\ValueObjects\ValueObjectException;
use Faker\Factory;
use Faker\Provider\pt_BR\Person;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class CpfTest extends TestCase
{

	/**
	 * @throws ValueObjectException
	 */
	#[DataProvider('cpfProvider')]
	public function testAssertValidCPFCreationSuccessful(string $cpf): void
	{
		$cpf = new Cpf($cpf);
		$this->assertInstanceOf(CPF::class, $cpf);
	}

	public function testAssertInvalidCpfCreationThrowException(): void
	{
		$this->expectException(ValueObjectException::class);
		new Cpf('00000000000');
	}

	public static function cpfProvider(): array
	{
		$provider = new Person(Factory::create('pt_BR'));
		return [
			[$provider->cpf(false)]
		];
	}
}
