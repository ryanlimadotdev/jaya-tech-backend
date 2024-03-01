<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

use App\Controller\IndexController;
use App\Controller\Payment\DeletePaymentController;
use App\Middlewares\Cors;
use Hyperf\HttpServer\Router\Router;
use App\Controller\Payment\GetAllPayments;
use App\Controller\Payment\GetPaymentController;
use App\Controller\Payment\PersistPaymentController;
use App\Controller\Payment\UpdatePaymentStatusController;

Router::addRoute(['GET', 'POST'], '/', [IndexController::class, 'index']);

Router::get('/favicon.ico', function () {
    return '';
});

Router::addGroup('/rest/payments', static function (): void {
	Router::post('', [PersistPaymentController::class, 'index']);
	Router::get('', [GetAllPayments::class, 'index']);
	Router::get('/{id}', [GetPaymentController::class, 'index']);
	Router::patch('/{id}', [UpdatePaymentStatusController::class, 'index']);
	Router::delete('/{id}', [DeletePaymentController::class, 'index']);
	Router::addRoute(['OPTIONS'], '', fn () => null);
	Router::addRoute(['OPTIONS'], '/{id }', fn () => null);
}, ['middleware' => [Cors::class]]);

