<?php

namespace App\Note\Infrastructure;

use App\Note\Application\Command\Create\Create;
use App\Note\Application\Command\Delete\Delete;
use App\Note\Application\Command\UpdateAssignedToDashboard\UpdateAssignedToDashboard;
use App\Note\Application\Command\UpdateBackgroundColor\UpdateBackgroundColor;
use App\Note\Application\Command\UpdateCatalogs\UpdateCatalogs;
use App\Note\Application\Command\UpdateContent\UpdateContent;
use App\Note\Application\Command\UpdateName\UpdateName;
use App\Note\Application\Command\UpdateTasks\UpdateTasks;
use App\Note\Application\Command\UpdateTextColor\UpdateTextColor;
use App\Note\Application\Query\Find\Find;
use App\Note\Application\Query\FindAll\FindAll;
use App\Note\Application\Query\FindById\FindById;
use App\Note\Application\ViewModel\Note;
use App\Note\Infrastructure\Request\NoteRequest;
use App\Shared\Application\Dto\CatalogsIdsList;
use App\Shared\Application\Dto\TasksIdsList;
use App\Shared\Application\Query\QueryResult;
use App\Shared\Domain\Entity\CatalogId;
use App\Shared\Domain\Entity\NoteId;
use App\Shared\Domain\ValueObject\Hex;
use App\Shared\Infrastructure\BaseController;
use App\Shared\Infrastructure\Utils\CollectionUtils;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class NotesController extends BaseController
{
    public function findById(int $id): Note
    {
        return $this->queryBus->handle(new FindById(new NoteId($id), QueryResult::VIEW_MODEL));
    }

    public function find(Request $request): array
    {
        $ids = CollectionUtils::getNumericValues($request);
        if ($ids === []) {
            return $this->queryBus->handle(new FindAll());
        }

        return $this->queryBus->handle(new Find(...array_map(static fn(int $id) => new NoteId($id), $ids)));
    }

    public function create(NoteRequest $request): RedirectResponse
    {
        $command = new Create(
            $request->getName(),
            $request->getText(),
            new Hex($request->getTextColor()),
            new Hex($request->getBackgroundColor()),
            $request->isAssignedToDashboard(),
            new CatalogsIdsList(...array_map(static fn(int $id) => new CatalogId($id), $request->getCatalogs())),
            new TasksIdsList(...$request->getTasks())
        );

        /** @var NoteId $id */
        $id = $this->commandBus->handleWithResult($command);

        return $this->redirectToNote($id->getValue());
    }

    private function redirectToNote(int $id): RedirectResponse
    {
        return $this->redirect('notes.findById', ['id' => $id]);
    }

    public function delete(int $id): Response
    {
        $this->commandBus->handle(new Delete(new NoteId($id)));

        return $this->response->setStatusCode(Response::HTTP_NO_CONTENT);
    }

    public function update(int $id, NoteRequest $request): RedirectResponse
    {
        $noteId = new NoteId($id);
        $this->commandBus->handle(new UpdateName($noteId, $request->getName()));
        $this->commandBus->handle(new UpdateContent($noteId, $request->getText()));
        $this->commandBus->handle(new UpdateTextColor($noteId, new Hex($request->getTextColor())));
        $this->commandBus->handle(new UpdateBackgroundColor($noteId, new Hex($request->getBackgroundColor())));
        $this->commandBus->handle(new UpdateAssignedToDashboard($noteId, $request->isAssignedToDashboard()));
        $this->commandBus->handle(new UpdateTasks($noteId, ...$request->getTasks()));
        $this->commandBus->handle(
            new UpdateCatalogs($noteId, ...array_map(static fn(int $id) => new CatalogId($id), $request->getCatalogs()))
        );

        return $this->redirectToNote($id);
    }

    public function undoFromDashboard(int $id): RedirectResponse
    {
        $this->commandBus->handle(new UpdateAssignedToDashboard(new NoteId($id), false));

        return $this->redirectToNote($id);
    }
}
