<?php

declare(strict_types=1);

namespace App\Controller\Payment;

use App\Domain\UseCases\FindOrCreatePayer\FindOrCreatePayerError;
use App\Domain\UseCases\PersistPayment\PersistPaymentError;
use App\Domain\UseCases\PersistPayment\PersistPaymentSuccessfulMessage;
use DateTime;
use App\Controller\AbstractController;
use App\Domain\UseCases\FindOrCreatePayer\Exceptions\FindOrCreatePayerException;
use App\Domain\UseCases\PersistPayment\Exceptions\PersistPaymentException;
use App\Domain\UseCases\PersistPayment\PersistPayment;
use App\DTOs\{PayerDTO, PaymentDTO};
use App\Request\PaymentCreationRequest;
use App\Schema\PaymentCreationRequestSchema;
use Hyperf\Swagger\Annotation as OA;
use Hyperf\Validation\ValidationException;
use Psr\Http\Message\ResponseInterface;
use function App\Helpers\json;
use function Hyperf\Support\env;

#[OA\HyperfServer('swagger')]
#[OA\Post(
	path: '/rest/payments',
	summary: 'Route used to create payments',
	servers: [new OA\Server('http://127.0.0.1:9501')],
	requestBody: new OA\RequestBody(
		description: 'Payment request body',
		required: true,
		content: [
			new OA\MediaType(
				mediaType: 'application/json',
				schema: new OA\Schema(ref: PaymentCreationRequestSchema::REF),
			),
		],
	),
	tags: ['Generate data'],
	responses: [
		new OA\Response(response: 400, description: 'Payment not provided in the request body',
			content: new OA\JsonContent(properties: [
				new OA\Property(property: 'status', example: 'error'),
				new OA\Property(property: 'message', example: 'payment not provided in the request body'),
			]),
		),
		new OA\Response(response: 422, description: 'Invalid payment provided.The possible reasons are:A field of the provided payment was null or with invalid values'),
	]
)]
class PersistPaymentController extends AbstractController
{

	public function __construct(
		private readonly PersistPayment $persistPayment,
	)
	{
	}

	public function index(PaymentCreationRequest $request): ResponseInterface
	{

		$fields = $this->request->all();

		if (empty($fields)) {
			return $this->emptyBodyResponse();
		}

		try {
			$request->validated();
		} catch (ValidationException $e) {
			return $this->invalidPaymentBody();
		}

		$payerFields = $fields['payer'];

		$payerDTO = new PayerDTO(
			$payerFields['email'],
			$payerFields['identification']['type'],
			$payerFields['identification']['number']
		);

		$paymentDTO = new PaymentDTO(
			(float)$fields['transaction_amount'],
			(int)$fields['installments'],
			$fields['token'],
			$fields['payment_method_id'],
			$payerDTO,
			env('WEBHOOK_URL'),
			new DateTime(),
			new DateTime()
		);

		$result = $this->persistPayment->handle($paymentDTO);
		if ($result instanceof PersistPaymentSuccessfulMessage) {
			return $this->response->withStatus(201)->json(json($result));
		}

		return match ($result) {
			PersistPaymentError::InfrastructureProblems =>
				$this->infrastructureError(),
			PersistPaymentError::InvalidDataProvided,
			PersistPaymentError::CantCreateOrRetrievePayer =>
				$this->invalidPaymentBody(),
			FindOrCreatePayerError::ConflictingDataProvided =>
				$this->conflictPayerBody(),
			default => $this->response->withStatus(501)
		};
	}

	private function emptyBodyResponse(): ResponseInterface
	{
		return $this->response
			->withStatus(400)
			->json(json([
					'status' => 'error',
					'message' => 'payment not provided in the request body',
				]),
			);
	}

	private function invalidPaymentBody(): ResponseInterface
	{
		return $this->response
			->withStatus(422)
			->json([
					'status' => 'error',
					'message' => 'Invalid payment provided.The possible reasons are:A field of the provided payment was null or with invalid values'
				]);
	}

	private function conflictPayerBody(): ResponseInterface
	{
		return $this->response
			->withStatus(409)
			->json([
					'status' => 'error',
					'message' => 'Some information about the payer are in conflict with the previous payer creation'
				]);
	}

	private function infrastructureError()
	{
		return $this->response
			->withStatus(500)
			->json([
				'status' => 'error',
				'message' => 'Internal problems'
			]);
	}
}
