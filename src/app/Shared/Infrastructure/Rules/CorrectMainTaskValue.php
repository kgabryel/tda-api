<?php

namespace App\Shared\Infrastructure\Rules;

use App\Shared\Infrastructure\Utils\UserUtils;
use App\Task\Infrastructure\Model\Task;
use Illuminate\Contracts\Validation\Rule;

/**
 * Sprawdza czy zadanie moze zostac wykorzystane jako glowne zadanie
 */
class CorrectMainTaskValue implements Rule
{
    public function passes($attribute, $value): bool
    {
        if ($value === null) {
            return true;
        }

        return Task::where('id', '=', $value)
            ->where('user_id', '=', UserUtils::getLoggedUser()->getId())
            ->whereNull('group_id')
            ->whereNull('parent_id')
            ->exists();
    }

    public function message(): string
    {
        return 'Invalid data.';
    }
}
