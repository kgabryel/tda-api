<?php

namespace App\User\Infrastructure\Request;

use App\Shared\Infrastructure\Request\BasicRequest;
use App\Shared\Infrastructure\Rules\CorrectResetPasswordToken;

class ResetPasswordRequest extends BasicRequest
{
    public function rules(): array
    {
        return [
            'token' => 'required|string',
            'password' => 'required|string',
            'passwordRepeat' => 'required|string|same:password'
        ];
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}
