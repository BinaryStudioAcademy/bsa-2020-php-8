<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Contracts\Cache\Repository as Cache;

class ImageApiService implements Contracts\ImageApiService
{
	const API_URL = "http://image-api:8000";

	private $cache;

	public function __construct(Cache $cache)
	{
		$this->cache = $cache;
	}

	public function getFilters(): array
	{
		if ($this->cache->has('image-filters')) {
			return $this->cache->get('image-filters');
		}

		$response = $this->withCookie(
			fn(PendingRequest $request) => $request->get(self::API_URL . '/api/filters')
		);

		$filters = $response->json();
		$this->cache->set('image-filters', $filters);

		return $filters;
	}

	public function applyFilter(string $image, string $filter): string
	{
		$response = $this->withCookie(
			fn(PendingRequest $request) => $request->post(
				self::API_URL . "/api/filters/${filter}",
				[ "data" => $image ]
			)
		);

		$result = $response->json();

		if (!$response->ok()) {
			throw new \LogicException($result['message']);
		}

		return $result['data'];
	}

	public function withCookie(\Closure $callback): Response
	{
		$cookie = $this->cache->get('cookie', '');
		$response = $callback(Http::withHeaders([
			'Cookie' => $cookie,
		]));

		$cookie = $response->header('Set-Cookie');
		$cookie = $this->cache->set('cookie', $cookie);

		return $response;
	}
}
