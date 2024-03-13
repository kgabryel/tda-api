<?php

namespace App\Task\Infrastructure\Controller;

use App\Shared\Application\UuidInterface;
use App\Shared\Domain\Entity\TaskId;
use App\Shared\Infrastructure\BaseController;
use App\Task\Application\Command\SingleTask\ChangeStatus\ChangeStatus;
use App\Task\Application\Command\SingleTask\Create\Create;
use App\Task\Application\Command\SingleTask\UpdateAlarm\UpdateAlarm;
use App\Task\Application\Command\SingleTask\UpdateContent\UpdateContent;
use App\Task\Application\Command\SingleTask\UpdateDate\UpdateDate;
use App\Task\Application\Command\SingleTask\UpdateMainTask\UpdateMainTask;
use App\Task\Application\Command\SingleTask\UpdateName\UpdateName;
use App\Task\Application\Query\TaskType;
use App\Task\Domain\Entity\StatusId;
use App\Task\Infrastructure\Request\Single\CreateRequest;
use App\Task\Infrastructure\Request\Single\TaskStatusRequest;
use App\Task\Infrastructure\Request\Single\UpdateRequest;
use Illuminate\Http\RedirectResponse;

class SingleTaskController extends BaseController
{
    public function create(CreateRequest $request, UuidInterface $uuid): RedirectResponse
    {
        $id = $uuid->getValue();
        $this->commandBus->handle(new Create($request->getTaskData($id), $request->getAlarmDto($uuid->getValue())));

        return $this->redirectToTask($id);
    }

    private function redirectToTask(string $id): RedirectResponse
    {
        return $this->redirect(
            sprintf('%s?type=%s', route('tasks.findById', ['id' => $id]), TaskType::SINGLE_TASK->value)
        );
    }

    public function changeStatus(string $id, TaskStatusRequest $request): RedirectResponse
    {
        $this->commandBus->handle(new ChangeStatus(new TaskId($id), new StatusId($request->getStatus())));

        return $this->redirectToTask($id);
    }

    public function update(string $id, UpdateRequest $request): RedirectResponse
    {
        if ($request->nameFilled()) {
            $this->commandBus->handle(new UpdateName(new TaskId($id), $request->getName()));
        }
        if ($request->contentFilled()) {
            $this->commandBus->handle(new UpdateContent(new TaskId($id), $request->getText()));
        }
        if ($request->dateFilled()) {
            $this->commandBus->handle(new UpdateDate(new TaskId($id), $request->getDate()));
        }
        if ($request->mainTaskFilled()) {
            $this->commandBus->handle(new UpdateMainTask(new TaskId($id), $request->getMainTask()));
        }
        if ($request->alarmFilled()) {
            $this->commandBus->handle(new UpdateAlarm(new TaskId($id), $request->getAlarm()));
        }

        return $this->redirectToTask($id);
    }
}
