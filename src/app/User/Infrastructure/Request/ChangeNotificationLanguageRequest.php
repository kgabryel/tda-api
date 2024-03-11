<?php

namespace App\User\Infrastructure\Request;

use App\Shared\Infrastructure\Request\BasicRequest;
use App\User\Domain\Entity\AvailableLanguage;
use Illuminate\Validation\Rule;

class ChangeNotificationLanguageRequest extends BasicRequest
{
    public function rules(): array
    {
        return [
            'value' => ['required', 'string', Rule::in(AvailableLanguage::getValues())]
        ];
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
