<?php

namespace App\User\Infrastructure\Request;

use App\Shared\Infrastructure\Request\BasicRequest;

class RefreshTokenRequest extends BasicRequest
{
    public function rules(): array
    {
        return [
            'refresh_token' => 'required|string'
        ];
    }

    public function getToken(): string
    {
        return $this->refresh_token;
    }
}
