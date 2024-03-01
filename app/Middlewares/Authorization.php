<?php

declare(strict_types=1);

namespace App\Middlewares;

use Hyperf\HttpServer\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Authorization implements MiddlewareInterface
{

	#[\Override] public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
	{
		$naoPassou = (bool) random_int(0, 1);
		if ($naoPassou) {
			return (new Response())->withStatus(401);
		}

		return $handler->handle($request);
	}


}