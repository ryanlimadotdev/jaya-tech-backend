<?php

declare(strict_types=1);

namespace App\Controller\Payment;

use App\Schema\PaymentSchema;
use Hyperf\Swagger\Annotation as OA;
use App\Controller\AbstractController;
use App\Domain\UseCases\FindPayment\FindPayments;
use Psr\Http\Message\ResponseInterface;


use function App\Helpers\json;

#[OA\HyperfServer('swagger')]
#[OA\Get(
	path: '/rest/payments',
	summary: 'Retrieve all payments',
	servers: [new OA\Server('http://127.0.0.1:9501')],
	tags: ['Get content'],
	responses: [
		new OA\Response(response: 200, content: new OA\MediaType(
			mediaType: 'application/json',
			schema: new OA\Schema(
				type: 'array',
				items: new OA\Items(ref: PaymentSchema::REF)
			)
		))
	]
)]
class GetAllPayments extends AbstractController
{

	public function __construct(
		private readonly FindPayments $findPayments,
	) { }

	public function index(): ResponseInterface
	{
		$this->response->json(
			json(
				$this->findPayments->getAll(),
			),
		);

		return $this->response;
	}
}