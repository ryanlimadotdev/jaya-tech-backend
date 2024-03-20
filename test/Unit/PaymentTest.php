<?php

declare(strict_types=1);

namespace HyperfTest\Unit;

use App\Domain\Entities\DomainEntityException;
use App\Domain\Entities\Identification;
use App\Domain\Entities\Payer;
use App\Domain\Entities\Payment;
use App\Domain\Entities\PaymentStatus;
use App\ValueObjects\Email;
use DateTimeImmutable;
use DateTimeInterface;
use Faker\Factory;
use Faker\Provider\pt_BR\Person;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class PaymentTest extends TestCase
{

	public static function paymentDataProvider(): array
	{
		$factory = Factory::create('pt_BR');
		$person = new Person($factory);

		$payer = new Payer(Email::create($factory->email()), Identification::create('cpf', $person->cpf()),);

		return [[rand(0, 1_000_000) / rand(0, 1_000), mt_rand(1, 12), substr($factory->text, 32), $factory->randomElement(['master', 'visa']), $payer, $factory->url(), new DateTimeImmutable(), new DateTimeImmutable(), $factory->randomElement([PaymentStatus::Pending, PaymentStatus::Paid, PaymentStatus::Canceled]), $factory->uuid(),],];
	}

	#[DataProvider('paymentDataProvider')]
	public function test__construct(...$args)
	{
		$payment = new Payment(... $args);
		$this->assertInstanceOf(Payment::class, $payment);
	}

	#[DataProvider('paymentDataProvider')]
	public function testUpdateStatus(float $transactionAmount, int $installments, string $token, string $paymentMethodId, Payer $payer, string $notificationUrl, DateTimeInterface $createdAt, DateTimeInterface $updatedAt,)
	{
		$payment = new Payment($transactionAmount, $installments, $token, $paymentMethodId, $payer, $notificationUrl, $createdAt, $updatedAt,);

		$payment->updateStatus(PaymentStatus::Paid);
		$this->assertNotEquals($payment->updatedAt, $updatedAt);
	}

	#[DataProvider('paymentDataProvider')]
	public function testExpectThrowsExceptionWhenTransactionAmountIsNegative(float $transactionAmount, int $installments, string $token, string $paymentMethodId, Payer $payer, string $notificationUrl, DateTimeInterface $createdAt, DateTimeInterface $updatedAt,)
	{
		$this->expectException(DomainEntityException::class);
		new Payment($transactionAmount * -1, $installments, $token, $paymentMethodId, $payer, $notificationUrl, $createdAt, $updatedAt,);
	}

	#[DataProvider('paymentDataProvider')]
	public function testExpectThrowsExceptionWhenInstallmentsIsNegative(float $transactionAmount, int $installments, string $token, string $paymentMethodId, Payer $payer, string $notificationUrl, DateTimeInterface $createdAt, DateTimeInterface $updatedAt,)
	{
		$this->expectException(DomainEntityException::class);
		new Payment($transactionAmount, $installments * -1, $token, $paymentMethodId, $payer, $notificationUrl, $createdAt, $updatedAt,);
	}
}
