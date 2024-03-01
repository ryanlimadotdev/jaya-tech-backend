<?php

declare(strict_types=1);

namespace App\Middlewares;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Cors implements MiddlewareInterface
{
	public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
	{

		$response = $handler->handle($request);

		return $response->withHeader('Access-Control-Allow-Origin', "*")
			->withHeader('Access-Control-Allow-Methods', "PUT, POST, GET, OPTIONS, PATCH, DELETE")
			->withHeader('Access-Control-Allow-Headers', "Accept, Authorization, Content-Type");
	}
}