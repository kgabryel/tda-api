<?php

namespace App\Shared\Infrastructure\Service;

use App\Shared\Application\Service\TranslationServiceInterface;
use App\User\Domain\Entity\AvailableLanguage;

class TranslationService implements TranslationServiceInterface
{
    public function getTranslation(string $key, AvailableLanguage $language, array $params = []): string
    {
        return __($key, $params, $language->value);
    }
}
