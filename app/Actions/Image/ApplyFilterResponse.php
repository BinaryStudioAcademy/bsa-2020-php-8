<?php

declare(strict_types=1);

namespace App\Actions\Image;

use App\Actions\Contracts\Response;
use App\Values\Image;

class ApplyFilterResponse implements Response
{
	private Image $image;

	public function __construct(Image $image)
	{
		$this->image = $image;
	}

	public function toArray(): array
	{
		return $this->image->toArray();
	}
}
