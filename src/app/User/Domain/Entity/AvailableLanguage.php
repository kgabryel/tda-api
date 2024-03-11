<?php

namespace App\User\Domain\Entity;

enum AvailableLanguage: string
{
    case PL = 'pl';
    case EN = 'en';

    public static function getDefault(): AvailableLanguage
    {
        return self::PL;
    }

    public static function getValues(): array
    {
        return array_map(static fn(self $action) => $action->value, self::cases());
    }
}
