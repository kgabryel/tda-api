<?php

namespace App\Color\Infrastructure;

use App\Color\Application\Command\Create\Create;
use App\Color\Application\Command\Delete\Delete;
use App\Color\Application\Query\FindAll\FindAll;
use App\Color\Application\Query\FindById\FindById;
use App\Color\Application\ViewModel\Color;
use App\Color\Domain\Entity\ColorId;
use App\Color\Infrastructure\Request\ColorRequest;
use App\Shared\Application\Query\QueryResult;
use App\Shared\Domain\ValueObject\Hex;
use App\Shared\Infrastructure\BaseController;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

class ColorsController extends BaseController
{
    public function findById(int $id): Color
    {
        return $this->queryBus->handle(new FindById(new ColorId($id), QueryResult::VIEW_MODEL));
    }

    public function findAll(): array
    {
        return $this->queryBus->handle(new FindAll());
    }

    public function create(ColorRequest $request): RedirectResponse
    {
        $command = new Create($request->getName(), new Hex($request->getColor()));

        /** @var ColorId $id */
        $id = $this->commandBus->handleWithResult($command);

        return $this->redirect('colors.findById', ['id' => $id]);
    }

    public function delete(int $id): Response
    {
        $this->commandBus->handle(new Delete(new ColorId($id)));

        return $this->response->setStatusCode(Response::HTTP_NO_CONTENT);
    }
}
