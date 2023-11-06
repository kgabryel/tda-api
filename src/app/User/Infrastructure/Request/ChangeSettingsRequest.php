<?php

namespace App\User\Infrastructure\Request;

use App\Shared\Infrastructure\Request\BasicRequest;

class ChangeSettingsRequest extends BasicRequest
{
    public function rules(): array
    {
        return [
            'value' => 'required|boolean'
        ];
    }

    public function getValue(): bool
    {
        return $this->value;
    }
}
