<?php

namespace App\Alarm\Infrastructure\Request\Single;

use App\Shared\Infrastructure\Request\BasicRequest;

class DeactivateRequest extends BasicRequest
{
    public function rules(): array
    {
        return [
            'code' => 'required|string|filled'
        ];
    }

    public function getCode(): string
    {
        return $this->code;
    }
}
