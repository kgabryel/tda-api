<?php

namespace App\Task\Infrastructure\Request\Periodic;

use App\Shared\Infrastructure\Request\BasicRequest;
use App\Task\Application\Command\PeriodicTask\Activate\ActivateAction;
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
