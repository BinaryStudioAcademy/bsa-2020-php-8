<?php

declare(strict_types=1);

namespace App\Values\Auth;

use App\Values\Auth\UserData;

class Token
{
    private $userData;

    public function __construct(string $token)
    {
        $this->userData = $this->parseToken($token);
    }

    public function getUserData(): UserData
    {
        return $this->userData;
    }

    public static function parse(string $token): UserData
    {
        $tokenObject = new self($token);

        return $tokenObject->getUserData();
    }

    private function parseToken(string $token): UserData 
    {
        list($type, $token) = explode(' ', $token);

        $this->validateAuthType($type);

        $data = json_decode(base64_decode($token));

        return new UserData(
            $data->email,
            $data->password
        );
    }

    private function validateAuthType(string $type)
    {
        if ($type !== 'Bearer') {
            throw new \LogicException('Type of authentication is unknown');
        }
    }
}
