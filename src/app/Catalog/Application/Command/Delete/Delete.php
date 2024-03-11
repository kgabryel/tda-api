<?php

namespace App\Catalog\Application\Command\Delete;

use App\Catalog\Application\Command\ModifyCatalogCommand;
use App\Core\Cqrs\CommandHandler;

/**
 * Usuwa katalog
 */
#[CommandHandler(DeleteHandler::class)]
class Delete extends ModifyCatalogCommand
{
}
