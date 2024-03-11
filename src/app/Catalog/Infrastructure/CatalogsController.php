<?php

namespace App\Catalog\Infrastructure;

use App\Catalog\Application\Command\Create\Create;
use App\Catalog\Application\Command\Delete\Delete;
use App\Catalog\Application\Command\RemoveAlarm\RemoveAlarm;
use App\Catalog\Application\Command\RemoveBookmark\RemoveBookmark;
use App\Catalog\Application\Command\RemoveFile\RemoveFile;
use App\Catalog\Application\Command\RemoveNote\RemoveNote;
use App\Catalog\Application\Command\RemoveTask\RemoveTask;
use App\Catalog\Application\Command\RemoveVideo\RemoveVideo;
use App\Catalog\Application\Command\UpdateAlarms\UpdateAlarms;
use App\Catalog\Application\Command\UpdateAssignedToDashboard\UpdateAssignedToDashboard;
use App\Catalog\Application\Command\UpdateBookmarks\UpdateBookmarks;
use App\Catalog\Application\Command\UpdateFiles\UpdateFiles;
use App\Catalog\Application\Command\UpdateName\UpdateName;
use App\Catalog\Application\Command\UpdateNotes\UpdateNotes;
use App\Catalog\Application\Command\UpdateTasks\UpdateTasks;
use App\Catalog\Application\Command\UpdateVideos\UpdateVideos;
use App\Catalog\Application\Query\Find\Find;
use App\Catalog\Application\Query\FindAll\FindAll;
use App\Catalog\Application\Query\FindById\FindById;
use App\Catalog\Application\ViewModel\Catalog;
use App\Catalog\Infrastructure\Request\CatalogRequest;
use App\Shared\Application\Dto\AlarmsIdsList;
use App\Shared\Application\Dto\BookmarksIdsList;
use App\Shared\Application\Dto\FilesIdsList;
use App\Shared\Application\Dto\NotesIdsList;
use App\Shared\Application\Dto\TasksIdsList;
use App\Shared\Application\Dto\VideosIdsList;
use App\Shared\Application\Query\QueryResult;
use App\Shared\Domain\Entity\BookmarkId;
use App\Shared\Domain\Entity\CatalogId;
use App\Shared\Domain\Entity\FileId;
use App\Shared\Domain\Entity\NoteId;
use App\Shared\Domain\Entity\VideoId;
use App\Shared\Infrastructure\BaseController;
use App\Shared\Infrastructure\Utils\CollectionUtils;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CatalogsController extends BaseController
{
    public function findById(int $id): Catalog
    {
        return $this->queryBus->handle(new FindById(new CatalogId($id), QueryResult::VIEW_MODEL));
    }

    public function find(Request $request): array
    {
        $ids = CollectionUtils::getNumericValues($request);
        if ($ids === []) {
            return $this->queryBus->handle(new FindAll());
        }

        return $this->queryBus->handle(new Find(...array_map(static fn(int $id) => new CatalogId($id), $ids)));
    }

    public function delete(int $id): Response
    {
        $this->commandBus->handle(new Delete(new CatalogId($id)));

        return $this->response->setStatusCode(Response::HTTP_NO_CONTENT);
    }

    public function create(CatalogRequest $request,): RedirectResponse
    {
        $command = new Create(
            $request->getName(),
            $request->isAssignedToDashboard(),
            new TasksIdsList(...$request->getTasks()),
            new NotesIdsList(...array_map(static fn(int $id) => new NoteId($id), $request->getNotes())),
            new BookmarksIdsList(...array_map(static fn(int $id) => new BookmarkId($id), $request->getBookmarks())),
            new FilesIdsList(...array_map(static fn(int $id) => new FileId($id), $request->getFiles())),
            new VideosIdsList(...array_map(static fn(int $id) => new VideoId($id), $request->getVideos())),
            new AlarmsIdsList(...$request->getAlarms())
        );

        /** @var CatalogId $id */
        $id = $this->commandBus->handleWithResult($command);

        return $this->redirectToCatalog($id->getValue());
    }

    private function redirectToCatalog(int $id): RedirectResponse
    {
        return redirect()->route('catalogs.findById', ['id' => $id], Response::HTTP_SEE_OTHER);
    }

    public function update(int $id, CatalogRequest $request,): RedirectResponse
    {
        $this->commandBus->handle(new UpdateName(new CatalogId($id), $request->getName()));
        $this->commandBus->handle(
            new UpdateAssignedToDashboard(new CatalogId($id), $request->isAssignedToDashboard())
        );
        $this->commandBus->handle(new UpdateTasks(new CatalogId($id), ...$request->getTasks()));
        $this->commandBus->handle(new UpdateAlarms(new CatalogId($id), ...$request->getAlarms()));
        $this->commandBus->handle(
            new UpdateBookmarks(
                new CatalogId($id),
                ...array_map(static fn(int $bookmarkId) => new BookmarkId($bookmarkId), $request->getBookmarks())
            )
        );
        $this->commandBus->handle(
            new UpdateFiles(
                new CatalogId($id),
                ...array_map(static fn(int $fileId) => new FileId($fileId), $request->getFiles())
            )
        );
        $this->commandBus->handle(
            new UpdateNotes(
                new CatalogId($id),
                ...array_map(static fn(int $noteId) => new NoteId($noteId), $request->getNotes())
            )
        );
        $this->commandBus->handle(
            new UpdateVideos(
                new CatalogId($id),
                ...array_map(static fn(int $videoId) => new VideoId($videoId), $request->getVideos())
            )
        );

        return $this->redirectToCatalog($id);
    }

    public function removeTask(int $catalogId, string $taskId): RedirectResponse
    {
        $this->commandBus->handle(new RemoveTask(new CatalogId($catalogId), $taskId));

        return $this->redirectToCatalog($catalogId);
    }

    public function removeAlarm(int $catalogId, string $alarmId): RedirectResponse
    {
        $this->commandBus->handle(new RemoveAlarm(new CatalogId($catalogId), $alarmId));

        return $this->redirectToCatalog($catalogId);
    }

    public function removeNote(int $catalogId, int $noteId): RedirectResponse
    {
        $this->commandBus->handle(new RemoveNote(new CatalogId($catalogId), new NoteId($noteId)));

        return $this->redirectToCatalog($catalogId);
    }

    public function removeBookmark(int $catalogId, int $bookmarkId): RedirectResponse
    {
        $this->commandBus->handle(new RemoveBookmark(new CatalogId($catalogId), new BookmarkId($bookmarkId)));

        return $this->redirectToCatalog($catalogId);
    }

    public function removeFile(int $catalogId, int $fileId): RedirectResponse
    {
        $this->commandBus->handle(new RemoveFile(new CatalogId($catalogId), new FileId($fileId)));

        return $this->redirectToCatalog($catalogId);
    }

    public function removeVideo(int $catalogId, int $videoId): RedirectResponse
    {
        $this->commandBus->handle(new RemoveVideo(new CatalogId($catalogId), new VideoId($videoId)));

        return $this->redirectToCatalog($catalogId);
    }

    public function undoFromDashboard(int $id): RedirectResponse
    {
        $this->commandBus->handle(new UpdateAssignedToDashboard(new CatalogId($id), false));

        return $this->redirectToCatalog($id);
    }
}
