<?php

namespace App\Alarm\Infrastructure\Request\Periodic;

use App\Alarm\Application\Command\PeriodicAlarm\Activate\ActivateAction;
use App\Shared\Infrastructure\Request\BasicRequest;
use Illuminate\Validation\Rule;

class ActivateRequest extends BasicRequest
{
    public function rules(): array
    {
        return [
            'action' => ['required', 'string', 'filled', Rule::in(ActivateAction::getValues())]
        ];
    }

    public function getAction(): ActivateAction
    {
        return ActivateAction::from($this->action);
    }
}
