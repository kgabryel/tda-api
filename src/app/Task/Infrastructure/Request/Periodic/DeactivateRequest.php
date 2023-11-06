<?php

namespace App\Task\Infrastructure\Request\Periodic;

use App\Shared\Infrastructure\Request\BasicRequest;
use App\Task\Application\Command\PeriodicTask\Deactivate\DeactivateAction;
use Illuminate\Validation\Rule;

class DeactivateRequest extends BasicRequest
{
    public function rules(): array
    {
        return [
            'action' => ['required', 'string', 'filled', Rule::in(DeactivateAction::getValues())]
        ];
    }

    public function getAction(): string
    {
        return $this->action;
    }
}
