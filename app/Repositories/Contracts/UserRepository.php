<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Entities\User;

interface UserRepository
{
    public function findByEmail(string $email): ?User;

    public function store(User $user): User;
}
