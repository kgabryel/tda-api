<?php

namespace App\Catalog\Application\Command\UpdateName;

use App\Catalog\Application\Command\ModifyCatalogCommand;
use App\Core\Cqrs\CommandHandler;
use App\Shared\Domain\Entity\CatalogId;

/**
 * Aktualizuje nazwe katalogu
 */
#[CommandHandler(UpdateNameHandler::class)]
class UpdateName extends ModifyCatalogCommand
{
    private string $name;

    public function __construct(CatalogId $id, string $name)
    {
        parent::__construct($id);
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
