<?php

declare(strict_types=1);

namespace App\Controller\Payment;

use Hyperf\Swagger\Annotation as OA;
use App\Controller\AbstractController;
use App\Domain\UseCases\FindOrCreatePayer\FindOrCreatePayer;
use App\Domain\UseCases\PersistPayment\PersistPayment;
use App\Domain\UseCases\UnsuccessfulMessage;
use App\Schema\PaymentCreationRequestSchema;
use App\DTOs\{PayerDTO, PaymentDTO};
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
			)
		]
	),
	tags: ['Generate data'],
	responses: [
		new OA\Response(response: 400, description: 'Payment not provided in the request body',
			content: new OA\JsonContent(properties: [
				new OA\Property(property: 'status', example: 'error'),
				new OA\Property(property: 'message', example: 'payment not provided in the request body')
			])
		),
		new OA\Response(response: 422, description: 'Invalid payment provided.The possible reasons are:A field of the provided payment was null or with invalid values'),
	]
)]
class PersistPaymentController extends AbstractController
{

	public function __construct(
        private readonly PersistPayment $persistPayment,
    ) { 
	}

    public function index(): ResponseInterface
    {

	    $fields = $this->request->all();

	    if (empty($fields)) {
		    return $this->emptyBodyResponse();
	    }

	    $payerFields = $fields['payer'];

	    try {
		    $payerDTO = new PayerDTO(
			    $payerFields['email'],
			    $payerFields['identification']['type'],
			    $payerFields['identification']['number'],
		    );

		    $paymentDTO = new PaymentDTO(
			    (float) $fields['transaction_amount'],
			    (int) $fields['installments'],
			    $fields['token'],
			    $fields['payment_method_id'],
			    $payerDTO,
			    env('WEBHOOK_URL'),
			    new \DateTime(),
			    new \DateTime(),
		    );
	    } catch (\Throwable) {
			return $this->invalidPaymentBody();
	    }

		$result = $this->persistPayment->handle($paymentDTO);

		if ($result instanceof  UnsuccessfulMessage and $result->getCode() === FindOrCreatePayer::InvalidDataProvided) {
			return $this->invalidPaymentBody();
		}

        return $this->response
	        ->withStatus(201)
	        ->json(
	            json($result)
        );
    }

	private function emptyBodyResponse(): ResponseInterface
	{
		return $this->response
			->withStatus(400)
			->json(json([
					'status' => 'error',
					'message' => 'payment not provided in the request body',
				])
			);
	}

	private function invalidPaymentBody(): ResponseInterface
	{
		return $this->response
			->withStatus(422)
			->json(json([
					'status' => 'error',
					'message' => 'Invalid payment provided.The possible reasons are:A field of the provided payment was null or with invalid values',
				])
			);

	}
}
