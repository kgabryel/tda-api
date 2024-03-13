<?php

namespace App\Task\Infrastructure\Command;

use App\Core\BusUtils;
use App\Core\Cqrs\CommandBus;
use App\Core\Cqrs\EventEmitter;
use App\Core\Cqrs\QueryBus;
use App\Shared\Application\Query\QueryResult;
use App\Shared\Application\UuidInterface;
use App\Shared\Domain\Entity\TaskId;
use App\Shared\Domain\Entity\UserId;
use App\Task\Application\Command\SingleTask\ChangeStatus\ChangeStatus;
use App\Task\Application\Query\FindById\FindById;
use App\Task\Application\Query\FindTaskStatusByName\FindTaskStatusByName;
use App\Task\Application\Query\FindTasksToDisable\FindTasksToDisable;
use App\Task\Domain\Entity\SingleTask;
use App\Task\Domain\Event\SingleTask\TasksModified;
use App\Task\Domain\TaskStatus;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class DisableTasks extends Command
{
    protected $signature = 'tasks:disable';
    private QueryBus $queryBus;
    private CommandBus $commandBus;
    private EventEmitter $eventEmitter;
    private BusUtils $busUtils;
    private UuidInterface $uuid;

    public function __construct(
        QueryBus $queryBus,
        CommandBus $commandBus,
        EventEmitter $eventEmitter,
        BusUtils $busUtils,
        UuidInterface $uuid
    ) {
        parent::__construct();
        $this->queryBus = $queryBus;
        $this->commandBus = $commandBus;
        $this->eventEmitter = $eventEmitter;
        $this->busUtils = $busUtils;
        $this->uuid = $uuid;
    }

    public function handle(): int
    {
        $cronId = $this->uuid->getValue();
        $undoneStatus = $this->queryBus->handle(new FindTaskStatusByName(TaskStatus::UNDONE));
        $tasks = $this->queryBus->handle(new FindTasksToDisable());
        Log::info('tasks:disable', [
            'id' => $cronId,
            'tasks' => array_map(static fn(array $task) => $task['id'], $tasks)
        ]);
        foreach ($tasks as $task) {
            $this->busUtils->setUserId(new UserId($task['user_id']));
            $taskId = new TaskId($task['id']);
            /** @var SingleTask $task */
            $task = $this->queryBus->handle(
                new FindById($taskId->getValue(), QueryResult::DOMAIN_MODEL)
            );
            $this->commandBus->handle(new ChangeStatus($taskId, $undoneStatus->getId()));
            $this->eventEmitter->emit(new TasksModified($task->getUserId(), $taskId));
        }
        Log::info('tasks:disable', [
            'id' => $cronId,
            'finish' => true
        ]);

        return 0;
    }
}
