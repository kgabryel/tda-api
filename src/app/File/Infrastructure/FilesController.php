<?php

namespace App\File\Infrastructure;

use App\File\Application\Command\Create\Create;
use App\File\Application\Command\Delete\Delete;
use App\File\Application\Command\ReplaceFile\ReplaceFile;
use App\File\Application\Command\UpdateAssigmentToDashboard\UpdateAssignedToDashboard;
use App\File\Application\Command\UpdateCatalogs\UpdateCatalogs;
use App\File\Application\Command\UpdateName\UpdateName;
use App\File\Application\Command\UpdateTasks\UpdateTasks;
use App\File\Application\Query\Find\Find;
use App\File\Application\Query\FindAll\FindAll;
use App\File\Application\Query\FindById\FindById;
use App\File\Application\ViewModel\File;
use App\File\Infrastructure\Request\CreateRequest;
use App\File\Infrastructure\Request\UpdateRequest;
use App\Shared\Application\Dto\CatalogsIdsList;
use App\Shared\Application\Dto\TasksIdsList;
use App\Shared\Application\Query\QueryResult;
use App\Shared\Domain\Entity\CatalogId;
use App\Shared\Domain\Entity\FileId;
use App\Shared\Infrastructure\BaseController;
use App\Shared\Infrastructure\Utils\CollectionUtils;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FilesController extends BaseController
{
    public function findById(int $id): File
    {
        return $this->queryBus->handle(new FindById(new FileId($id), QueryResult::VIEW_MODEL));
    }

    public function find(Request $request): array
    {
        $ids = CollectionUtils::getNumericValues($request);
        if ($ids === []) {
            return $this->queryBus->handle(new FindAll());
        }

        return $this->queryBus->handle(new Find(...array_map(static fn(int $id) => new FileId($id), $ids)));
    }

    public function delete(int $id): Response
    {
        $this->commandBus->handle(new Delete(new FileId($id)));

        return $this->response->setStatusCode(Response::HTTP_NO_CONTENT);
    }

    public function create(CreateRequest $request): RedirectResponse
    {
        $command = new Create(
            $request->getName(),
            $request->isAssignedToDashboard(),
            new UploadedFile($request->getFile()),
            new CatalogsIdsList(...array_map(static fn(int $id) => new CatalogId($id), $request->getCatalogs() ?? [])),
            new TasksIdsList(...$request->getTasks() ?? [])
        );

        /** @var FileId $id */
        $id = $this->commandBus->handleWithResult($command);

        return $this->redirectToFile($id->getValue());
    }

    private function redirectToFile(int $id): RedirectResponse
    {
        return $this->redirect('files.findById', ['id' => $id]);
    }

    public function update(int $id, UpdateRequest $request): RedirectResponse
    {
        $fileId = new FileId($id);
        if ($request->nameFilled()) {
            $this->commandBus->handle(new UpdateName($fileId, $request->getName()));
        }
        if ($request->assignedToDashboardFilled()) {
            $this->commandBus->handle(new UpdateAssignedToDashboard($fileId, $request->isAssignedToDashboard()));
        }

        if ($request->tasksFilled()) {
            $this->commandBus->handle(new UpdateTasks($fileId, ...$request->getTasks() ?? []));
        }
        if ($request->catalogsFilled()) {
            $this->commandBus->handle(
                new UpdateCatalogs(
                    $fileId,
                    ...array_map(static fn(int $id) => new CatalogId($id), $request->getCatalogs() ?? [])
                )
            );
        }
        if ($request->fileFilled()) {
            $this->commandBus->handle(new ReplaceFile($fileId, new UploadedFile($request->getFile())));
        }

        return $this->redirectToFile($id);
    }

    public function download(int $id, DownloadService $downloadService): StreamedResponse
    {
        return $downloadService->download(
            $this->queryBus->handle(new FindById(new FileId($id), QueryResult::DOMAIN_MODEL))
        );
    }

    public function undoFromDashboard(int $id): RedirectResponse
    {
        $this->commandBus->handle(new UpdateAssignedToDashboard(new FileId($id), false));

        return $this->redirectToFile($id);
    }
}
