<?php

declare(strict_types=1);

namespace App\Controller\Payment;

use Hyperf\Swagger\Annotation as OA;
use App\Controller\AbstractController;
use App\Domain\UseCases\DeletePayment\DeletePayment;
use App\Domain\UseCases\UnsuccessfulMessage;
use Psr\Http\Message\ResponseInterface;

#[OA\HyperfServer('swagger')]
#[OA\Delete(
	path: '/rest/payments/{id}',
	summary: 'Cancel the payment who id match',
	servers: [new OA\Server('http://127.0.0.1:9501')],
	tags: ['Change status'],
	parameters: [
		new OA\Parameter(parameter: 'id', name: 'id', in: 'path', required: true)
	],
	responses: [
		new OA\Response(response: 204, description: 'No content', headers: []),
		new OA\Response(response: 404, description: 'Payment not found with the specified id', headers: []),
	]
)]
class DeletePaymentController extends AbstractController
{

	public function __construct(
		private readonly DeletePayment $deletePayment,
	){
	}

	public function index(string $id): ResponseInterface
	{

		$result = $this->deletePayment->handle($id);

		if ($result instanceof UnsuccessfulMessage and $result->getCode()) {
			return $this->response->withStatus(404);
		}

		$this->deletePayment->handle($id);
		return $this->response->withStatus(204);
	}
}