<?php

declare(strict_types=1);

namespace App\Values;

class Image
{
	private string $id;
	private string $src;

	public function __construct(string $id, string $src)
	{
		$this->id = $id;
		$this->src = $src;
	}

	public function getId(): string
	{
		return $this->id;
	}

	public function getSrc(): string
	{
		return $this->src;
	}

	public function toArray()
	{
		return [
			"id" => $this->getId(),
			"src" => $this->getSrc()
		];
	}
}
