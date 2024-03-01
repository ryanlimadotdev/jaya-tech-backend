<?php

declare(strict_types=1);

namespace HyperfTest;

use GuzzleHttp\Client;
use Hyperf\Testing\Concerns\MakesHttpRequests;
use Hyperf\Testing\Http\TestResponse;

trait PatchHttpRequestTestCase
{

	use MakesHttpRequests;

	public function patch(string $uri, mixed $body = [], $headers = []): TestResponse
	{
		$headers['Content-Type'] = 'application/json';

		$serverConfigurations = require BASE_PATH . '/config/autoload/server.php';

		$httpServer = array_filter($serverConfigurations['servers'], fn($server) => $server['name'] === 'http')[0];
		$port = $httpServer['port'];

		$client = new Client();

		$response = $client->patch("127.0.0.1:$port/$uri", [
			'headers' => $headers,
			'body' => json_encode($body)
		]);

		return $this->createTestResponse($response);
	}
}