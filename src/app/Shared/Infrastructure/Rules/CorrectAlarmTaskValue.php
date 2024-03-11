<?php

namespace App\Shared\Infrastructure\Rules;

use App\Core\Cqrs\QueryBus;
use App\Shared\Application\Query\QueryResult;
use App\Task\Application\Query\FindById\FindById;
use App\Task\Domain\Entity\SingleTask;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\Request;

/**
 * Sprawdza czy zadanie moze zostac przypisane do alarmu
 */
class CorrectAlarmTaskValue implements Rule
{
    private Request $request;
    private QueryBus $queryBus;

    public function __construct(Request $request, QueryBus $queryBus)
    {
        $this->request = $request;
        $this->queryBus = $queryBus;
    }

    public function passes($attribute, $value): bool
    {
        if ($value === null) {
            return true;
        }
        /** @var SingleTask $task */
        $task = $this->queryBus->handle(new FindById($value, QueryResult::DOMAIN_MODEL));

        if (!$task->hasAlarm()) {
            return true;
        }

        return $task->getAlarmId()->getValue() === $this->request->route('id');
    }

    public function message(): string
    {
        return 'Invalid data.';
    }
}
