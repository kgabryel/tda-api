<?php

namespace App\Task\Infrastructure\Request\Single;

use App\Core\Cqrs\QueryBus;
use App\Shared\Infrastructure\Request\BasicRequest;
use App\Task\Application\Query\FindTaskStatuses\FindTasksStatuses;
use App\Task\Application\ViewModel\TaskStatus;
use Illuminate\Validation\Rule;

class TaskStatusRequest extends BasicRequest
{
    /** @var int[] */
    private array $taskStatuses;

    public function __construct(
        QueryBus $queryBus,
        array $query = [],
        array $request = [],
        array $attributes = [],
        array $cookies = [],
        array $files = [],
        array $server = [],
        $content = null
    ) {
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);
        $this->taskStatuses = array_map(
            static fn(TaskStatus $status) => $status->getId(),
            $queryBus->handle(new FindTasksStatuses())
        );
    }

    public function rules(): array
    {
        return [
            'status' => ['required', 'integer', Rule::in($this->taskStatuses)]
        ];
    }

    public function getStatus(): int
    {
        return $this->status;
    }
}
