<?php

declare(strict_types=1);

namespace App\Schema;

use Hyperf\Swagger\Annotation as OA;
use OpenApi\Attributes\Schema;

#[Schema(
	required: ['transaction_amount', 'installments', 'token', 'payment_method_id', 'payer'],
	properties: [
		new OA\Property(property: 'transaction_amount', description: 'Transaction value', type: 'float', example: 259.9),
		new OA\Property(property: 'installments', description: 'Number of installments', type: 'integer', minimum: 1, example: 1),
		new OA\Property(property: 'token', description: 'User provided identification token', type: 'string'),
		new OA\Property(property: 'payment_method_id', description: 'The identification of the payment method', type: 'string'),
		new OA\Property(property: 'payer', description: 'Payer information', required: ['email', 'identification'], properties: [
			new OA\Property(property: 'email', type: 'string', example: 'example@email.com'),
			new OA\Property(property: 'identification', required: ['type', 'number'], properties: [
				new OA\Property(property: 'type', type: 'string', example: 'CPF', anyOf: ['CPF', 'CNPJ']),
				new OA\Property(property: 'number', type: 'string', example: '12345678910')
			], type: 'object')
		], type: 'object'),
	]
)]
class PaymentCreationRequestSchema
{
	const string REF = '#/components/schemas/PaymentCreationRequestSchema';
}