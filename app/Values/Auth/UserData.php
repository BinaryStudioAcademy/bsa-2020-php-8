<?php

declare(strict_types=1);

namespace App\Values\Auth;

use Illuminate\Support\Facades\Hash;

class UserData
{
    public $name;
    public $email;
    private $password;

    public function __construct(string $email, string $password, string $name = '')
    {
        if (empty($email)) {
            throw new \LogicException('email is required');
        }

        if (empty($password)) {
            throw new \LogicException('password is required');
        }

        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
    }

    public function checkPassword($hash): bool
    {
        return Hash::check($this->password, $hash);
    }

    public function __get($name)
    {
        if ($name !== 'password') {
            return null;
        }

        return Hash::make($this->password);
    }
}
