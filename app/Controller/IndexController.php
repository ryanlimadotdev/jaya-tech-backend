<?php

declare(strict_types=1);

namespace App\Controller;

use Psr\Http\Message\ResponseInterface;

class IndexController extends AbstractController
{
	public function index(): ResponseInterface
	{

		return $this->response->redirect('/index.html');
	}
}