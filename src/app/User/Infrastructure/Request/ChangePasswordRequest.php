<?php

namespace App\User\Infrastructure\Request;

use App\Shared\Domain\Config;
use App\Shared\Infrastructure\Request\BasicRequest;

class ChangePasswordRequest extends BasicRequest
{
    public function rules(): array
    {
        return [
            'password' => sprintf('required|string|max:%s', Config::PASSWORD_LENGTH),
            'passwordRepeat' => 'required|string|same:password',
            'oldPassword' => 'required|string'
        ];
    }

    public function getPassword(): string
    {
        return $this->oldPassword;
    }

    public function getNewPassword(): string
    {
        return $this->password;
    }
}
