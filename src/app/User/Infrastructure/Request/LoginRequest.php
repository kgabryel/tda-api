<?php

namespace App\User\Infrastructure\Request;

use App\Shared\Domain\Config;
use App\Shared\Infrastructure\Request\BasicRequest;

class LoginRequest extends BasicRequest
{
    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'string',
                'email',
                sprintf('max:%s', Config::EMAIL_LENGTH),
                'exists:users'
            ],
            'password' => sprintf('required|string|max:%s', Config::PASSWORD_LENGTH)
        ];
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}
