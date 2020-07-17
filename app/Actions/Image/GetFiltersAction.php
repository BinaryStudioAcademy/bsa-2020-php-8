<?php

declare(strict_types=1);

namespace App\Actions\Image;

use App\Services\Contracts\ImageApiService;

class GetFiltersAction
{
	private ImageApiService $imageApiService;

	public function __construct(ImageApiService $imageApiService)
	{
		$this->imageApiService = $imageApiService;
	}

	public function execute(): array
	{
		return $this->imageApiService->getFilters();
	}
}
