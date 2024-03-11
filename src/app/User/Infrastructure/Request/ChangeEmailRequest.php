<?php

namespace App\User\Infrastructure\Request;

use App\Shared\Domain\Config;
use App\Shared\Infrastructure\Request\BasicRequest;

class ChangeEmailRequest extends BasicRequest
{
    public function rules(): array
    {
        return [
            'email' => sprintf('nullable|string|email|max:%s', Config::EMAIL_LENGTH)
        ];
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }
}
