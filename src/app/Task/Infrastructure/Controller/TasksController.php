<?php

namespace App\Task\Infrastructure\Controller;

use App\Shared\Application\Query\QueryResult;
use App\Shared\Domain\Entity\BookmarkId;
use App\Shared\Domain\Entity\CatalogId;
use App\Shared\Domain\Entity\FileId;
use App\Shared\Domain\Entity\NoteId;
use App\Shared\Domain\Entity\TaskId;
use App\Shared\Domain\Entity\TasksGroupId;
use App\Shared\Domain\Entity\VideoId;
use App\Shared\Infrastructure\BaseController;
use App\Shared\Infrastructure\Utils\CollectionUtils;
use App\Task\Application\Command\AddBookmark\AddBookmark;
use App\Task\Application\Command\AddCatalog\AddCatalog;
use App\Task\Application\Command\AddFile\AddFile;
use App\Task\Application\Command\AddNote\AddNote;
use App\Task\Application\Command\AddVideo\AddVideo;
use App\Task\Application\Command\PeriodicTask\Delete\Delete as DeletePeriodicTask;
use App\Task\Application\Command\RemoveBookmark\RemoveBookmark;
use App\Task\Application\Command\RemoveCatalog\RemoveCatalog;
use App\Task\Application\Command\RemoveFile\RemoveFile;
use App\Task\Application\Command\RemoveNote\RemoveNote;
use App\Task\Application\Command\RemoveVideo\RemoveVideo;
use App\Task\Application\Command\SingleTask\Delete\Delete as DeleteSingleTask;
use App\Task\Application\Query\Find\Find;
use App\Task\Application\Query\FindAll\FindAll;
use App\Task\Application\Query\FindById\FindById;
use App\Task\Application\Query\FindTaskStatuses\FindTasksStatuses;
use App\Task\Application\Query\TaskType;
use App\Task\Application\ViewModel\PeriodicTask as PeriodicTaskView;
use App\Task\Application\ViewModel\SingleTask as SingleTaskView;
use App\Task\Domain\Entity\PeriodicTask as PeriodicTaskEntity;
use App\Task\Domain\Entity\SingleTask as SingleTaskEntity;
use App\Task\Domain\Exception\AssignedTaskModified;
use App\Task\Infrastructure\Request\BookmarkRequest;
use App\Task\Infrastructure\Request\CatalogRequest;
use App\Task\Infrastructure\Request\FileRequest;
use App\Task\Infrastructure\Request\NoteRequest;
use App\Task\Infrastructure\Request\VideoRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TasksController extends BaseController
{
    public function findById(
        string $id,
        Request $request
    ): SingleTaskEntity|SingleTaskView|PeriodicTaskEntity|PeriodicTaskView {
        return $this->queryBus->handle(
            new FindById(
                $id,
                QueryResult::VIEW_MODEL,
                TaskType::tryFrom($this->getQueryParam($request, 'type'))
            )
        );
    }

    public function find(Request $request): array
    {
        $ids = CollectionUtils::getStringValues($request);
        if ($ids === []) {
            return $this->queryBus->handle(new FindAll());
        }

        return $this->queryBus->handle(new Find(...$ids));
    }

    public function findAllStatuses(): array
    {
        return $this->queryBus->handle(new FindTasksStatuses());
    }

    public function delete(string $id, Request $request): Response
    {
        $deleteTasks = $this->getQueryParam($request, 'deleteTasks') === 'true';
        $deleteAlarm = $this->getQueryParam($request, 'deleteAlarm') === 'true';
        $task = $this->queryBus->handle(new FindById($id, QueryResult::DOMAIN_MODEL));
        if ($task instanceof SingleTaskEntity) {
            $this->commandBus->handle(
                new DeleteSingleTask(new TaskId($id), $deleteTasks, $deleteAlarm)
            );
        } else {
            $this->commandBus->handle(
                new DeletePeriodicTask(new TasksGroupId($id), $deleteTasks, $deleteAlarm)
            );
        }

        return $this->response->setStatusCode(Response::HTTP_NO_CONTENT);
    }

    public function removeCatalog(string $taskId, int $catalogId): RedirectResponse
    {
        try {
            $this->commandBus->handle(new RemoveCatalog($taskId, new CatalogId($catalogId)));
        } catch (AssignedTaskModified) {
            abort(400);
        }

        return $this->redirectToTask($taskId);
    }

    private function redirectToTask(string $id): RedirectResponse
    {
        $task = $this->queryBus->handle(new FindById($id, QueryResult::DOMAIN_MODEL));

        return $this->redirect(
            sprintf(
                '%s?type=%s',
                route('tasks.findById', ['id' => $task->getTaskId()]),
                $task instanceof SingleTaskEntity ? 'single' : 'periodic'
            )
        );
    }

    public function addCatalog(string $taskId, CatalogRequest $request): RedirectResponse
    {
        try {
            $this->commandBus->handle(new AddCatalog($taskId, new CatalogId($request->getCatalog())));
        } catch (AssignedTaskModified) {
            abort(400);
        }

        return $this->redirectToTask($taskId);
    }

    public function removeNote(string $taskId, int $noteId): RedirectResponse
    {
        try {
            $this->commandBus->handle(new RemoveNote($taskId, new NoteId($noteId)));
        } catch (AssignedTaskModified) {
            abort(400);
        }

        return $this->redirectToTask($taskId);
    }

    public function addNote(string $taskId, NoteRequest $request): RedirectResponse
    {
        try {
            $this->commandBus->handle(new AddNote($taskId, new NoteId($request->getNote())));
        } catch (AssignedTaskModified) {
            abort(400);
        }

        return $this->redirectToTask($taskId);
    }

    public function removeBookmark(string $taskId, int $bookmarkId): RedirectResponse
    {
        try {
            $this->commandBus->handle(new RemoveBookmark($taskId, new BookmarkId($bookmarkId)));
        } catch (AssignedTaskModified) {
            abort(400);
        }

        return $this->redirectToTask($taskId);
    }

    public function addBookmark(string $taskId, BookmarkRequest $request): RedirectResponse
    {
        try {
            $this->commandBus->handle(new AddBookmark($taskId, new BookmarkId($request->getBookmark())));
        } catch (AssignedTaskModified) {
            abort(400);
        }

        return $this->redirectToTask($taskId);
    }

    public function removeFile(string $taskId, int $fileId): RedirectResponse
    {
        try {
            $this->commandBus->handle(new RemoveFile($taskId, new FileId($fileId)));
        } catch (AssignedTaskModified) {
            abort(400);
        }

        return $this->redirectToTask($taskId);
    }

    public function addFile(string $taskId, FileRequest $request): RedirectResponse
    {
        try {
            $this->commandBus->handle(new AddFile($taskId, new FileId($request->getFile())));
        } catch (AssignedTaskModified) {
            abort(400);
        }

        return $this->redirectToTask($taskId);
    }

    public function removeVideo(string $taskId, int $videoId): RedirectResponse
    {
        try {
            $this->commandBus->handle(new RemoveVideo($taskId, new VideoId($videoId)));
        } catch (AssignedTaskModified) {
            abort(400);
        }

        return $this->redirectToTask($taskId);
    }

    public function addVideo(string $taskId, VideoRequest $request): RedirectResponse
    {
        try {
            $this->commandBus->handle(new AddVideo($taskId, new VideoId($request->getVideo())));
        } catch (AssignedTaskModified) {
            abort(400);
        }

        return $this->redirectToTask($taskId);
    }
}
