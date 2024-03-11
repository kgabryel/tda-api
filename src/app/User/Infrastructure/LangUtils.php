<?php

namespace App\User\Infrastructure;

use App\User\Domain\Entity\AvailableLanguage;

abstract class LangUtils
{
    public static function getLang(mixed $lang): AvailableLanguage
    {
        return AvailableLanguage::tryFrom((string)$lang) ?? AvailableLanguage::getDefault();
    }
}
