<?php

namespace App\Shared\Infrastructure\Rules;

use App\Shared\Infrastructure\Utils\UserUtils;
use App\Task\Infrastructure\Model\Task;
use App\Task\Infrastructure\Model\TaskGroup;
use Illuminate\Contracts\Validation\Rule;

/**
 * Sprawdza czy zadanie moze zostac przypisane do katalogu, notatki, filmu, pliku lub zakladki
 */
class TaskPinnable implements Rule
{
    public function passes($attribute, $value): bool
    {
        $userId = UserUtils::getLoggedUser()->getId();
        $task = Task::where('user_id', '=', $userId)
            ->where('id', '=', $value)
            ->whereNull('group_id')
            ->first();
        if ($task !== null) {
            return true;
        }

        return TaskGroup::where('id', '=', $value)->where('user_id', '=', $userId)->exists();
    }

    public function message(): string
    {
        return 'Invalid data.';
    }
}
