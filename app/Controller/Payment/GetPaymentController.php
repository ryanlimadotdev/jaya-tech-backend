<?php

declare(strict_types=1);

namespace App\Controller\Payment;

use Hyperf\Swagger\Annotation as OA;
use App\Controller\AbstractController;
use App\Domain\UseCases\FindPayment\FindPayments;
use App\Schema\PaymentSchema;
use Psr\Http\Message\ResponseInterface;

use function App\Helpers\json;

#[OA\HyperfServer('swagger')]
#[OA\Get(
	path: '/rest/payments/{id}',
	summary: 'Retrieve the payment who id match',
	servers: [new OA\Server('http://127.0.0.1:9501')],
	tags: ['Get content'],
	parameters: [
		new OA\Parameter(
			parameter: 'id',
			name: 'id',
			description: 'UUID of the target payment as returned during the creation',
			in: 'path',
			required: true
		)
	],
	responses: [
		new OA\Response(response: 200, content: new OA\MediaType(
			mediaType: 'application/json',
			schema: new OA\Schema(
				ref: PaymentSchema::REF
			)
		)),
		new OA\Response(response: 404, description: 'Unable to find payment with the provided id')
	]
)]
class GetPaymentController extends AbstractController
{

	public function __construct(
		private readonly FindPayments $findPayments
	)
	{
	}

	public function index(string $id): ResponseInterface
	{
		$result = $this->findPayments->findById($id);

		if (is_null($result)) {
			return $this->response->withStatus(404);
		}

		$this->response->json(
			json(
				$result,
			),
		);

		return $this->response;
	}
}