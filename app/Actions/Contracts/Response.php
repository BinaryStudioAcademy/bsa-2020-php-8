<?php

declare(strict_types=1);

namespace App\Actions\Contracts;

interface Response
{
	public function toArray(): array;
}
