<?php

namespace App\Bookmark\Infrastructure;

use App\Bookmark\Application\Command\Create\Create;
use App\Bookmark\Application\Command\Delete\Delete;
use App\Bookmark\Application\Command\UpdateAssignedToDashboard\UpdateAssignedToDashboard;
use App\Bookmark\Application\Command\UpdateBackgroundColor\UpdateBackgroundColor;
use App\Bookmark\Application\Command\UpdateCatalogs\UpdateCatalogs;
use App\Bookmark\Application\Command\UpdateHref\UpdateHref;
use App\Bookmark\Application\Command\UpdateIcon\UpdateIcon;
use App\Bookmark\Application\Command\UpdateName\UpdateName;
use App\Bookmark\Application\Command\UpdateTasks\UpdateTasks;
use App\Bookmark\Application\Command\UpdateTextColor\UpdateTextColor;
use App\Bookmark\Application\Query\Find\Find;
use App\Bookmark\Application\Query\FindAll\FindAll;
use App\Bookmark\Application\Query\FindById\FindById;
use App\Bookmark\Application\ViewModel\Bookmark;
use App\Bookmark\Domain\Entity\Bookmark as DomainModel;
use App\Bookmark\Infrastructure\Request\CreateRequest;
use App\Bookmark\Infrastructure\Request\UpdateRequest;
use App\Shared\Application\Dto\CatalogsIdsList;
use App\Shared\Application\Dto\TasksIdsList;
use App\Shared\Application\Query\QueryResult;
use App\Shared\Domain\Entity\BookmarkId;
use App\Shared\Domain\Entity\CatalogId;
use App\Shared\Domain\ValueObject\Hex;
use App\Shared\Infrastructure\BaseController;
use App\Shared\Infrastructure\Utils\CollectionUtils;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BookmarksController extends BaseController
{
    public function findById(int $id): Bookmark
    {
        return $this->queryBus->handle(new FindById(new BookmarkId($id), QueryResult::VIEW_MODEL));
    }

    public function find(Request $request): array
    {
        $ids = CollectionUtils::getNumericValues($request);
        if ($ids === []) {
            return $this->queryBus->handle(new FindAll());
        }

        return $this->queryBus->handle(new Find(...array_map(static fn(int $id) => new BookmarkId($id), $ids)));
    }

    public function create(CreateRequest $request): RedirectResponse
    {
        $command = new Create(
            $request->getName(),
            $request->getHref(),
            new Hex($request->getTextColor()),
            new Hex($request->getBackgroundColor()),
            $request->isAssignedToDashboard(),
            new CatalogsIdsList(...array_map(static fn(int $id) => new CatalogId($id), $request->getCatalogs())),
            new TasksIdsList(...$request->getTasks())
        );

        /** @var BookmarkId $id */
        $id = $this->commandBus->handleWithResult($command);

        return $this->redirectToBookmark($id->getValue());
    }

    private function redirectToBookmark(int $id): RedirectResponse
    {
        return redirect()->route('bookmarks.findById', ['id' => $id], Response::HTTP_SEE_OTHER);
    }

    public function delete(int $id): Response
    {
        $this->commandBus->handle(new Delete(new BookmarkId($id)));

        return $this->response->setStatusCode(Response::HTTP_NO_CONTENT);
    }

    public function update($id, UpdateRequest $request): RedirectResponse
    {
        $bookmarkId = new BookmarkId($id);
        /** @var DomainModel $bookmark */
        $bookmark = $this->queryBus->handle(new FindById(new BookmarkId($id), QueryResult::DOMAIN_MODEL));
        $overrideIcon = $bookmark->getHref() !== $request->getHref() && $bookmark->getIcon() === $request->getIcon();
        $this->commandBus->handle(new UpdateName($bookmarkId, $request->getName()));
        $this->commandBus->handle(new UpdateHref($bookmarkId, $request->getHref(), $overrideIcon));
        if ($overrideIcon) {
            $this->commandBus->handle(new UpdateIcon($bookmarkId, $request->getIcon()));
        }
        $this->commandBus->handle(new UpdateTextColor($bookmarkId, new Hex($request->getTextColor())));
        $this->commandBus->handle(new UpdateBackgroundColor($bookmarkId, new Hex($request->getBackgroundColor())));
        $this->commandBus->handle(new UpdateAssignedToDashboard($bookmarkId, $request->isAssignedToDashboard()));
        $this->commandBus->handle(new UpdateTasks($bookmarkId, ...$request->getTasks()));
        $this->commandBus->handle(
            new UpdateCatalogs(
                $bookmarkId,
                ...array_map(static fn(int $id) => new CatalogId($id), $request->getCatalogs())
            )
        );

        return $this->redirectToBookmark($id);
    }

    public function undoFromDashboard(int $id): RedirectResponse
    {
        $this->commandBus->handle(new UpdateAssignedToDashboard(new BookmarkId($id), false));

        return $this->redirectToBookmark($id);
    }
}
