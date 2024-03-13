<?php

namespace App\Task\Infrastructure\Controller;

use App\Shared\Application\UuidInterface;
use App\Shared\Domain\Entity\TasksGroupId;
use App\Shared\Infrastructure\BaseController;
use App\Task\Application\Command\PeriodicTask\Activate\Activate;
use App\Task\Application\Command\PeriodicTask\Create\Create;
use App\Task\Application\Command\PeriodicTask\Deactivate\Deactivate;
use App\Task\Application\Command\PeriodicTask\Deactivate\DeactivateAction;
use App\Task\Application\Command\PeriodicTask\UpdateContent\UpdateContent;
use App\Task\Application\Command\PeriodicTask\UpdateName\UpdateName;
use App\Task\Application\Query\TaskType;
use App\Task\Infrastructure\Request\Periodic\ActivateRequest;
use App\Task\Infrastructure\Request\Periodic\CreateRequest;
use App\Task\Infrastructure\Request\Periodic\DeactivateRequest;
use App\Task\Infrastructure\Request\Periodic\UpdateRequest;
use Illuminate\Http\RedirectResponse;
use InvalidArgumentException;

class PeriodicTaskController extends BaseController
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
            sprintf('%s?type=%s', route('tasks.findById', ['id' => $id]), TaskType::PERIODIC_TASK->value)
        );
    }

    public function update(string $id, UpdateRequest $request): RedirectResponse
    {
        if ($request->nameFilled()) {
            $this->commandBus->handle(new UpdateName(new TasksGroupId($id), $request->getName()));
        }
        if ($request->contentFilled()) {
            $this->commandBus->handle(new UpdateContent(new TasksGroupId($id), $request->getText()));
        }

        return $this->redirectToTask($id);
    }

    public function deactivate(string $id, DeactivateRequest $request): RedirectResponse
    {
        try {
            $result = $this->commandBus->handleWithResult(
                new Deactivate(new TasksGroupId($id), DeactivateAction::from($request->getAction()))
            );
        } catch (InvalidArgumentException) {
            abort(400);
        }
        if ($result === false) {
            abort(400);
        }

        return $this->redirectToTask($id);
    }

    public function activate(string $id, ActivateRequest $request): RedirectResponse
    {
        $result = $this->commandBus->handleWithResult(new Activate(new TasksGroupId($id), $request->getAction()));
        if ($result === false) {
            abort(400);
        }

        return $this->redirectToTask($id);
    }
}
