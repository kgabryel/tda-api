<?php

namespace App\User\Infrastructure\Request;

use App\Shared\Infrastructure\Request\BasicRequest;

class ConfirmEmailRequest extends BasicRequest
{
    public function rules(): array
    {
        return [
            'code' => 'required|string'
        ];
    }

    public function getCode(): string
    {
        return $this->code;
    }
}
