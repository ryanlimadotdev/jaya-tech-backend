<?php

declare(strict_types=1);

namespace App\Schema;

use Hyperf\Swagger\Annotation as OA;
use Hyperf\Swagger\Annotation\Schema;

#[Schema(title: 'PaymentSchema', properties: [
	new OA\Property(property: 'id', type: 'string', default: '0abdd8de-1795-4982-999b-b8a28265b015'),
	new OA\Property(property: 'status', type: 'string', default: 'PENDING'),
	new OA\Property(property: 'transaction_amount', type: 'float', default: 245.9),
	new OA\Property(property: 'installments',type: 'integer', default: 3),
	new OA\Property(property: 'token', type: 'string', default: 'ae4e50b2a8f3h6d9f2c3a4b5d6e7f8g9'),
	new OA\Property(property: 'payment_method_id', type: 'string', default: 'master'),
	new OA\Property(property: 'payer', properties: [
		new OA\Property(property: 'entity_type', type: 'string', default: 'individual'),
		new OA\Property(property: 'type', type: 'string', default: 'customer'),
		new OA\Property(property: 'email', type: 'string', default: 'example_random@gmail.com'),
		new OA\Property(property: 'identification', properties: [
			new OA\Property(property: 'type', type: 'string', default: 'CPF', oneOf: ['CPF', 'CNPJ']),
			new OA\Property(property: 'number', type: 'string', default: '12345678909'),
		], type: 'object'),
	], type: 'object'),
])]
class PaymentSchema
{
	const string REF = '#/components/schemas/PaymentSchema';
}
