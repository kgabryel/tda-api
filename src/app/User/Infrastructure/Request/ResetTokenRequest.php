<?php

namespace App\User\Infrastructure\Request;

use App\Shared\Infrastructure\Request\BasicRequest;

class ResetTokenRequest extends BasicRequest
{
    public function rules(): array
    {
        return [
            'email' => 'required|string|email|exists:users,email,facebook_id,NULL'
        ];
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}
