<?php

namespace App\Video\Application\Command\UpdateCatalogs;

use App\Core\Cqrs\CommandHandler;
use App\Shared\Domain\Entity\CatalogId;
use App\Shared\Domain\Entity\VideoId;
use App\Video\Application\Command\ModifyVideoCommand;

/**
 * Aktualizuje liste katalogow przypisanych do filmu
 */
#[CommandHandler(UpdateCatalogsHandler::class)]
class UpdateCatalogs extends ModifyVideoCommand
{
    private array $catalogs;

    public function __construct(VideoId $id, CatalogId ...$catalogs)
    {
        parent::__construct($id);
        $this->catalogs = $catalogs;
    }

    public function getCatalogs(): array
    {
        return $this->catalogs;
    }
}
