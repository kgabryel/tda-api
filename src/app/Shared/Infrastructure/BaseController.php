<?php

namespace App\Shared\Infrastructure;

use App\Core\Controller;
use App\Core\Cqrs\CommandBus;
use App\Core\Cqrs\QueryBus;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

class BaseController extends Controller
{
    protected QueryBus $queryBus;
    protected CommandBus $commandBus;

    public function __construct(QueryBus $queryBus, CommandBus $commandBus, Response $response)
    {
        parent::__construct($response);
        $this->queryBus = $queryBus;
        $this->commandBus = $commandBus;
    }

    protected function redirect(string $url, array $params): RedirectResponse
    {
        return redirect()->route($url, $params, Response::HTTP_SEE_OTHER);
    }
}
