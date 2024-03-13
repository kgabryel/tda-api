<?php

namespace App\Shared\Application\Service;

use App\User\Domain\Entity\AvailableLanguage;

interface TranslationServiceInterface
{
    public function getTranslation(string $key, AvailableLanguage $language, array $params = []): string;
}
