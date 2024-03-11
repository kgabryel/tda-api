<?php

namespace App\Task\Infrastructure\Controller;

use App\Shared\Application\Dto\CatalogsIdsList;
use App\Shared\Application\UuidInterface;
use App\Shared\Domain\Entity\BookmarkId;
use App\Shared\Domain\Entity\CatalogId;
use App\Shared\Domain\Entity\FileId;
use App\Shared\Domain\Entity\NoteId;
use App\Shared\Domain\Entity\TasksGroupId;
use App\Shared\Domain\Entity\VideoId;
use App\Shared\Infrastructure\BaseController;
use App\Task\Application\Command\PeriodicTask\Activate\Activate;
use App\Task\Application\Command\PeriodicTask\Create\Create;
use App\Task\Application\Command\PeriodicTask\Create\TaskDto;
use App\Task\Application\Command\PeriodicTask\Deactivate\Deactivate;
use App\Task\Application\Command\PeriodicTask\Deactivate\DeactivateAction;
use App\Task\Application\Command\PeriodicTask\UpdateContent\UpdateContent;
use App\Task\Application\Command\PeriodicTask\UpdateName\UpdateName;
use App\Task\Application\Dto\BookmarksList;
use App\Task\Application\Dto\FilesList;
use App\Task\Application\Dto\NotesList;
use App\Task\Application\Dto\VideosList;
use App\Task\Application\Query\TaskType;
use App\Task\Infrastructure\Request\Periodic\ActivateRequest;
use App\Task\Infrastructure\Request\Periodic\CreateRequest;
use App\Task\Infrastructure\Request\Periodic\DeactivateRequest;
use App\Task\Infrastructure\Request\Periodic\UpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use InvalidArgumentException;

class PeriodicTaskController extends BaseController
{
    public function create(CreateRequest $request, UuidInterface $uuid): RedirectResponse
    {
        $id = $uuid->getValue();
        $taskDto = new TaskDto(
            new TasksGroupId($id),
            $request->getName(),
            $request->getText(),
            new CatalogsIdsList(
                ...array_map(static fn(string $id) => new CatalogId($id), $request->getCatalogs())
            ),
            new FilesList(
                ...array_map(static fn(string $id) => new FileId($id), $request->getFiles())
            ),
            new NotesList(
                ...array_map(static fn(string $id) => new NoteId($id), $request->getNotes())
            ),
            new VideosList(
                ...array_map(static fn(string $id) => new VideoId($id), $request->getVideos())
            ),
            new BookmarksList(
                ...array_map(static fn(string $id) => new BookmarkId($id), $request->getBookmarks())
            ),
            $request->getStart(),
            $request->getStop(),
            $request->getInterval(),
            $request->getIntervalType()
        );
        $this->commandBus->handle(new Create($taskDto, $request->getAlarmDto($uuid->getValue())));

        return redirect(
            sprintf(
                '%s?type=%s',
                route('tasks.findById', ['id' => $id]),
                TaskType::PERIODIC_TASK->value
            ),
            Response::HTTP_SEE_OTHER
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

        return redirect(
            sprintf(
                '%s?type=%s',
                route('tasks.findById', ['id' => $id]),
                TaskType::PERIODIC_TASK->value
            ),
            Response::HTTP_SEE_OTHER
        );
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

        return redirect(
            sprintf(
                '%s?type=%s',
                route('tasks.findById', ['id' => $id]),
                TaskType::PERIODIC_TASK->value
            ),
            Response::HTTP_SEE_OTHER
        );
    }

    public function activate(
        string $id,
        ActivateRequest $request
    ): RedirectResponse {
        $result = $this->commandBus->handleWithResult(new Activate(new TasksGroupId($id), $request->getAction()));
        if ($result === false) {
            abort(400);
        }

        return redirect(
            sprintf(
                '%s?type=%s',
                route('tasks.findById', ['id' => $id]),
                TaskType::PERIODIC_TASK->value
            ),
            Response::HTTP_SEE_OTHER
        );
    }
}
