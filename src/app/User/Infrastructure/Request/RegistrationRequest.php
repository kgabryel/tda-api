<?php

namespace App\User\Infrastructure\Request;

use App\Shared\Domain\Config;
use App\Shared\Infrastructure\Request\BasicRequest;
use App\User\Domain\Entity\AvailableLanguage;
use App\User\Infrastructure\LangUtils;

class RegistrationRequest extends BasicRequest
{
    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'string',
                'email',
                sprintf('max:%s', Config::EMAIL_LENGTH),
                'unique:users'
            ],
            'password' => 'required|string'
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

    public function getLang(): AvailableLanguage
    {
        return LangUtils::getLang($this->lang);
    }
}
