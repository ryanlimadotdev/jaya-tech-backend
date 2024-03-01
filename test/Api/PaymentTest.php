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
use Hyperf\Testing\TestCase;
use HyperfTest\PatchHttpRequestTestCase;
use Override;

/**
 * @internal
 */
class PaymentTest extends TestCase
{

	use PatchHttpRequestTestCase;

	private string $testPaymentId;

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

	/**
	 * @before
	 */
    public function testPaymentCreation(): void
    {
	    $payload = [
		    "transaction_amount" => 245.90,
		    "installments" => 3,
		    "token" => "ae4e50b2a8f3h6d9f2c3a4b5d6e7f8g9",
		    "payment_method_id" => "master",
		    "payer" => [
			    "email" => "example_random@gmail.com",
			    "identification" => [
				    "type" => "CPF",
				    "number" => "12345678909",
			    ],
		    ],
	    ];

	    $expectedStructure = [
		    'id',
		    'createdAt',
	    ];

		 $this->testPaymentId = $this->json('rest/payments', $payload)
		 	->assertStatus(201)
		 	->assertJsonStructure($expectedStructure)
		    ->decodeResponseJson()['id'];



    }
	
	public function testGetAllPayments(): void
	{

		$expectedStructure = [
			'*' => $this->paymentJsonStructure,
		];
		
		$this->get('/rest/payments')
			 ->assertStatus(200)
			 ->assertJsonStructure($expectedStructure);

	}

	public function testGetPaymentById(): void
	{
		 $this->get('rest/payments/' . $this->testPaymentId)
			->assertStatus(200)
			->assertJsonStructure($this->paymentJsonStructure);
	}

	public function testPaymentUpdate(): void
	{
		$payload = [
			'status' => 'PAID',
		];

		$this->patch('rest/payments/' . $this->testPaymentId , $payload)
			->assertStatus(204)
			->assertNoContent();
	}

	public function testPaymentCancellation(): void
	{
		$this->delete('/rest/payments/' . $this->testPaymentId)
			->assertStatus(204)
			->assertNoContent();

	}

}
