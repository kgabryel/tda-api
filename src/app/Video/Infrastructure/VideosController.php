<?php

namespace App\Video\Infrastructure;

use App\Shared\Application\Dto\CatalogsIdsList;
use App\Shared\Application\Dto\TasksIdsList;
use App\Shared\Application\Query\QueryResult;
use App\Shared\Domain\Entity\CatalogId;
use App\Shared\Domain\Entity\VideoId;
use App\Shared\Infrastructure\BaseController;
use App\Shared\Infrastructure\Utils\CollectionUtils;
use App\Video\Application\Command\Create\Create;
use App\Video\Application\Command\CreateMany\CreateMany;
use App\Video\Application\Command\Delete\Delete;
use App\Video\Application\Command\UpdateAssignedToDashboard\UpdateAssignedToDashboard;
use App\Video\Application\Command\UpdateCatalogs\UpdateCatalogs;
use App\Video\Application\Command\UpdateName\UpdateName;
use App\Video\Application\Command\UpdateTasks\UpdateTasks;
use App\Video\Application\Command\UpdateWatchedValue\UpdateWatchedValue;
use App\Video\Application\Query\Find\Find;
use App\Video\Application\Query\FindAll\FindAll;
use App\Video\Application\Query\FindById\FindById;
use App\Video\Application\ViewModel\Video;
use App\Video\Application\YtServiceInterface;
use App\Video\Infrastructure\Request\CreateRequest;
use App\Video\Infrastructure\Request\UpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class VideosController extends BaseController
{
    public function findById(int $id): Video
    {
        return $this->queryBus->handle(new FindById(new VideoId($id), QueryResult::VIEW_MODEL));
    }

    public function find(Request $request): array
    {
        $ids = CollectionUtils::getNumericValues($request);
        if ($ids === []) {
            return $this->queryBus->handle(new FindAll());
        }

        return $this->queryBus->handle(new Find(...array_map(static fn(int $id) => new VideoId($id), $ids)));
    }

    public function delete(int $id): Response
    {
        $this->commandBus->handle(new Delete(new VideoId($id)));

        return $this->response->setStatusCode(Response::HTTP_NO_CONTENT);
    }

    public function create(CreateRequest $request, YtServiceInterface $ytService): RedirectResponse
    {
        $catalogsList = new CatalogsIdsList(
            ...array_map(static fn(int $id) => new CatalogId($id), $request->getCatalogs())
        );
        $tasksList = new TasksIdsList(...$request->getTasks());
        if ($ytService->isPlaylist($request->getHref())) {
            $command = new CreateMany(
                $request->isAssignedToDashboard(),
                $request->getHref(),
                $catalogsList,
                $tasksList
            );
            $ids = $this->commandBus->handleWithResult($command);

            return redirect(
                sprintf(
                    '%s?ids=[%s]',
                    route('videos.find'),
                    implode(',', array_map(static fn(VideoId $id) => sprintf('"%s"', $id->getValue()), $ids->toArray()))
                ),
                Response::HTTP_SEE_OTHER
            );
        }
        $command = new Create($request->isAssignedToDashboard(), $request->getHref(), $catalogsList, $tasksList);

        /** @var VideoId $id */
        $id = $this->commandBus->handleWithResult($command);

        return redirect(
            sprintf(
                '%s?ids=[%s]',
                route('videos.find'),
                $id->getValue()
            ),
            Response::HTTP_SEE_OTHER
        );
    }

    public function update(int $id, UpdateRequest $request): RedirectResponse
    {
        $videoId = new VideoId($id);
        if ($request->nameFilled()) {
            $this->commandBus->handle(new UpdateName($videoId, $request->getName()));
        }
        if ($request->isWatchedFilled()) {
            $this->commandBus->handle(new UpdateWatchedValue($videoId, $request->isWatched()));
        }
        if ($request->assignedToDashboardFilled()) {
            $this->commandBus->handle(new UpdateAssignedToDashboard($videoId, $request->isAssignedToDashboard()));
        }
        if ($request->tasksFilled()) {
            $this->commandBus->handle(new UpdateTasks($videoId, ...$request->getTasks()));
        }
        if ($request->catalogsFilled()) {
            $this->commandBus->handle(
                new UpdateCatalogs(
                    $videoId,
                    ...array_map(static fn(int $id) => new CatalogId($id), $request->getCatalogs())
                )
            );
        }

        return $this->redirectVideo($id);
    }

    private function redirectVideo(int $id): RedirectResponse
    {
        return redirect()->route('videos.findById', ['id' => $id], Response::HTTP_SEE_OTHER);
    }

    public function undoFromDashboard(int $id): RedirectResponse
    {
        $this->commandBus->handle(new UpdateAssignedToDashboard(new VideoId($id), false));

        return $this->redirectVideo($id);
    }
}
