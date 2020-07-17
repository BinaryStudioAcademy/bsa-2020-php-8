<?php

declare(strict_types=1);

namespace App\Services\Contracts;

interface ImageApiService
{
	public function getFilters(): array;

	public function applyFilter(string $image, string $filter): string;
}
