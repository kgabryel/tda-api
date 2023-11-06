<?php

namespace App\Shared\Infrastructure\Rules;

use App\Alarm\Infrastructure\Model\Alarm;
use App\Shared\Infrastructure\Utils\UserUtils;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\Request;

/**
 * Sprawdza czy alarm moze zostac przypisany do zadania
 */
class CorrectTaskAlarmValue implements Rule
{
    private Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function passes($attribute, $value): bool
    {
        $userId = UserUtils::getLoggedUser()->getId();
        if ($value === null) {
            return true;
        }
        $alarm = Alarm::where('user_id', '=', $userId)->where('id', '=', $value)
            ->whereNull('group_id')
            ->first();
        if ($alarm === null) {
            return false;
        }
        $taskId = $alarm->getTaskId();
        if ($taskId === null) {
            return true;
        }

        return $taskId === $this->request->route('id');
    }

    public function message(): string
    {
        return 'Invalid data.';
    }
}
