<?php

namespace App\Alarm\Infrastructure\Request\Periodic;

use App\Alarm\Application\Command\PeriodicAlarm\Deactivate\DeactivateAction;
use App\Shared\Infrastructure\Request\BasicRequest;
use Illuminate\Validation\Rule;

class DeactivateRequest extends BasicRequest
{
    public function rules(): array
    {
        return [
            'action' => ['required', 'string', 'filled', Rule::in(DeactivateAction::getValues())]
        ];
    }

    public function getAction(): DeactivateAction
    {
        return DeactivateAction::from($this->action);
    }
}
