<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\ImageApiService;
use Illuminate\Contracts\Cache\Repository as Cache;
use Illuminate\Support\Facades\Http;

class ImageApiServiceTest extends TestCase
{
    public function testApiUrl()
    {
        $this->assertEquals(ImageApiService::API_URL, "http://image-api:8000");
	}

	public function testGettingFilters()
	{
		$filters = [
			'filter1',
			'filter2'
		];
		Http::fake([
			ImageApiService::API_URL . '/api/filters' => Http::response($filters, 200, ['Headers'])
		]);

		$imageApiService = $this->createService(
			$this->mock(Cache::class, function ($mock) use($filters) {
				$mock->shouldReceive('has')->with('image-filters')->once()->andReturn(false);
				$mock->shouldReceive('get')->with('cookie', '')->once()->andReturn('');
				$mock->shouldReceive('set')->with('cookie', '')->once()->andReturn('');
				$mock->shouldReceive('set')->with('image-filters', $filters)->once();
			})
		);

		$result = $imageApiService->getFilters();

		$this->assertEquals($result, $filters);
	}

	public function testGettingFiltersFromCache()
	{
		$filters = [
			'filter1',
			'filter2',
		];
		Http::fake([
			ImageApiService::API_URL . '/api/filters' => Http::response([ 'a', 'b' ], 200, ['Headers'])
		]);

		$imageApiService = $this->createService(
			$this->mock(Cache::class, function ($mock) use($filters) {
				$mock->shouldReceive('has')->with('image-filters')->once()->andReturn(true);
				$mock->shouldReceive('get')->with('image-filters')->andReturn($filters)->once();
			})
		);

		$result = $imageApiService->getFilters();

		$this->assertEquals($result, $filters);
	}

	public function testApplyingFilter()
	{
		$filterName = 'filter';
		$imageData = 'image data';
		Http::fake([
			ImageApiService::API_URL . "/api/filters/${filterName}" => Http::response(
				[ 'data' => $imageData . ' updated' ],
				200,
				[ 'Set-Cookie' => 'cookie=cookie' ]
			)
		]);

		$imageApiService = $this->createService(
			$this->mock(Cache::class, function ($mock) {
				$mock->shouldReceive('get')->with('cookie', '')->once()->andReturn('cookie=cached-cookie');
				$mock->shouldReceive('set')->with('cookie', 'cookie=cookie')->once();
			})
		);

		$result = $imageApiService->applyFilter($imageData, $filterName);

		Http::assertSent(function ($request, $response) use ($imageData, $filterName) {
			return $request->hasHeader('Cookie', 'cookie=cached-cookie') &&
				ImageApiService::API_URL . "/api/filters/${filterName}" &&
				$request['data'] === $imageData &&
				$response['data'] === $imageData . ' updated';
		});

		$this->assertEquals($result, $imageData . ' updated');
	}
	
	private function createService(Cache $cache = null): ImageApiService
	{
		return new ImageApiService($cache);
	}
}
