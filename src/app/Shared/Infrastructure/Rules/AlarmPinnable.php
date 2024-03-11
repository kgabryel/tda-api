<?php

namespace App\Shared\Infrastructure\Rules;

use App\Alarm\Infrastructure\Model\Alarm;
use App\Alarm\Infrastructure\Model\AlarmGroup;
use App\Shared\Infrastructure\Utils\UserUtils;
use Illuminate\Contracts\Validation\Rule;

/**
 * Sprawdza czy alarm moze zostac przypisane do katalogu
 */
class AlarmPinnable implements Rule
{
    public function passes($attribute, $value): bool
    {
        $userId = UserUtils::getLoggedUser()->getId();
        $alarm = Alarm::where('user_id', '=', $userId)->where('id', '=', $value)
            ->whereNull('group_id')
            ->first();
        if ($alarm !== null) {
            return true;
        }

        return AlarmGroup::where('id', '=', $value)->where('user_id', '=', $userId)->exists();
    }

    public function message(): string
    {
        return 'Invalid data.';
    }
}
