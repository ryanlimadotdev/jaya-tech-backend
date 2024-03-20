<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace HyperfTest\Api;

use App\Repositories\PaymentRepository;
use Hyperf\Context\ApplicationContext;
use Hyperf\Testing\Concerns\MakesHttpRequests;
use Hyperf\Testing\Http\Client;
use PHPUnit\Framework\Attributes\AfterClass;
use PHPUnit\Framework\Attributes\Depends;
use PHPUnit\Framework\Attributes\DoesNotPerformAssertions;
use PHPUnit\Framework\TestCase;

class PaymentTest extends TestCase
{

	use MakesHttpRequests;

	public function __construct(string $name)
	{
		parent::__construct($name);
	}

	protected array $paymentJsonStructure = [
		'id',
		'status',
		'transaction_amount',
		'installments',
		'token',
		'payment_method_id',
		'payer' => [
			'entity_type',
			'type',
			'email',
			'identification' => [
				'type',
				'number',
			],
		],
		'notification_url',
		'created_at',
		'updated_at',
	];

    public function testPaymentCreation(): string
    {
	    $payload = [
		    "transaction_amount" => 245.90,
		    "installments" => 3,
		    "token" => "ae4e50b2a8f3h6d9f2c3a4b5d6e7f8g9",
		    "payment_method_id" => "master",
		    "payer" => [
			    "email" => "example_randm@gmail.com",
			    "identification" => [
				    "type" => "CPF",
				    "number" => "79895375085",
			    ],
		    ],
	    ];

	    $expectedStructure = [
		    'id',
		    'created_at',
	    ];

		return $this->json('rest/payments', $payload)
		 	->assertStatus(201)
		 	->assertJsonStructure($expectedStructure)
		    ->decodeResponseJson()['id'];

    }

	#[Depends('testPaymentCreation')]
	public function testGetAllPayments(): void
	{

		$expectedStructure = [
			'*' => $this->paymentJsonStructure,
		];
		
		$this->get('/rest/payments')
			 ->assertStatus(200)
			 ->assertJsonStructure($expectedStructure);

	}

	#[Depends('testPaymentCreation')]
	public function testGetPaymentById(string $id): void
	{
		 $this->get("rest/payments/$id")
			->assertStatus(200)
			->assertJsonStructure($this->paymentJsonStructure);
	}

	#[Depends('testPaymentCreation')]
	public function testPaymentUpdate(string $id): void
	{
		$payload = [
			'status' => 'PAID',
		];

		$headers['Content-Type'] = 'application/json';
		$options = [
			'headers' => $headers,
			'form_params' => $payload
		];

		$client = ApplicationContext::getContainer()->get(Client::class);

		$response = $client->request('PATCH', "rest/payments/$id", $options);

		$this->createTestResponse($response)
			->assertStatus(204)
			->assertNoContent();
	}

	#[Depends('testPaymentCreation')]
	public function testPaymentCancellation(string $id): void
	{
		$this->delete("/rest/payments/$id")
			->assertStatus(204)
			->assertNoContent();
	}

	#[Depends('testPaymentCreation'), AfterClass, DoesNotPerformAssertions]
	public function testEnd(string $id): void
	{
		ApplicationContext::getContainer()->get(PaymentRepository::class)->delete($id);
	}

}
