<?php

declare(strict_types=1);

namespace App\Controller\Payment;

use App\Domain\UseCases\UpdatePaymentStatus\UpdatePaymentStatusError;
use App\Domain\UseCases\UpdatePaymentStatus\UpdatePaymentStatusSuccessfulMessage;
use Hyperf\Swagger\Annotation as OA;
use App\Controller\AbstractController;
use App\Domain\UseCases\UpdatePaymentStatus\UpdatePaymentStatus;
use Psr\Http\Message\ResponseInterface;

#[OA\HyperfServer('swagger')]
#[OA\Patch(
	path: '/rest/payments/{id}',
	summary: 'Route used update the payment status',
	servers: [new OA\Server('http://127.0.0.1:9501')],
	requestBody: new OA\RequestBody(
		required: true,
		content: [new OA\MediaType(
			mediaType: 'application/json',
			schema: new OA\Schema(
				required: ['status'],
				properties: [
					new OA\Property(property: 'status', type: 'string', example: 'PAID')
				]
			)
		)],
	),
	tags: ['Change status'],
	parameters: [
		new OA\Parameter(parameter: 'id', name: 'id', in: 'path', required: true)
	],
	responses: [
		new OA\Response(response: 204, description: 'Updated'),
		new OA\Response(response: 400, description: 'Bad request'),
		new OA\Response(response: 404, description: 'Resource not found')
	]
)]
class UpdatePaymentStatusController extends AbstractController
{

	public function __construct(
		private readonly UpdatePaymentStatus $updatePaymentStatus,
	){ }

	public function index(string $id): ResponseInterface
	{

		if (!isset($this->request->all()['status'])) {
			return $this->response->withStatus(400);
		}

		$result = $this->updatePaymentStatus->handle($id, $this->request->all()['status']);

		if ($result === UpdatePaymentStatusError::CantLocatePayment) {
			return $this->response->withStatus(404);
		}

		if ($result === UpdatePaymentStatusError::InvalidStatusProvided) {
			return $this->response->withStatus(403);
		}

		return $this->response->withStatus(204);
	}
}