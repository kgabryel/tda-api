<?php

namespace App\Color\Application\Command\Delete;

use App\Color\Application\Query\FindById\FindById;
use App\Color\Domain\Entity\Color;
use App\Color\Domain\Event\Deleted;
use App\Shared\Application\Command\CommandHandler;
use App\Shared\Application\Query\QueryResult;

class DeleteHandler extends CommandHandler
{
    public function handle(Delete $command): void
    {
        /** @var Color $color */
        $color = $this->queryBus->handle(new FindById($command->getColorId(), QueryResult::DOMAIN_MODEL));
        if (!$color->delete()) {
            return;
        }
        $this->eventEmitter->emit(new Deleted($color->getColorId()));
    }
}
