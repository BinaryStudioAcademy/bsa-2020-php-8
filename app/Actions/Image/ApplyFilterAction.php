<?php

declare(strict_types=1);

namespace App\Actions\Image;

use App\Services\Contracts\ImageApiService;
use App\Actions\Contracts\Response;
use App\Values\Image;

class ApplyFilterAction
{
	private ImageApiService $imageApiService;

	public function __construct(ImageApiService $imageApiService)
	{
		$this->imageApiService = $imageApiService;
	}

	public function execute(Image $image, string $filter): Response
	{
		$result = $this->imageApiService->applyFilter($image->getSrc(), $filter);

		return new ApplyFilterResponse(
			new Image(
				$image->getId(),
				$result,
			)
		);
	}
}
