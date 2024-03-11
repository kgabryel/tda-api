<?php

namespace App\Task\Application\Query\FindById;

use App\Core\Cqrs\QueryHandler;
use App\Shared\Application\Query\Query;
use App\Shared\Application\Query\QueryResult;
use App\Shared\Domain\Entity\TaskId;
use App\Shared\Domain\Entity\TasksGroupId;
use App\Task\Application\Query\TaskType;

/**
 * Pobiera zadanie na podstawie id
 */
#[QueryHandler(FindByIdQueryHandler::class)]
class FindById implements Query
{
    private string $id;
    private QueryResult $result;
    private ?TaskType $taskType;

    public function __construct(string|TaskId|TasksGroupId $id, QueryResult $result, ?TaskType $taskType = null)
    {
        if (is_string($id)) {
            $this->id = $id;
            $this->taskType = $taskType;
        } else {
            $this->id = $id->getValue();
            $this->taskType = TaskType::fromId($id);
        }
        $this->result = $result;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getResult(): QueryResult
    {
        return $this->result;
    }

    public function getTaskType(): ?TaskType
    {
        return $this->taskType;
    }
}
