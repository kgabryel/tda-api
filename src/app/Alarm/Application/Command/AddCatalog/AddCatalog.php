<?php

namespace App\Alarm\Application\Command\AddCatalog;

use App\Core\Cqrs\CommandHandler;
use App\Shared\Application\Command\Command;
use App\Shared\Domain\Entity\CatalogId;

/**
 * Przypisuje katalog do alarmu
 */
#[CommandHandler(AddCatalogHandler::class)]
class AddCatalog implements Command
{
    private string $alarmId;
    private CatalogId $catalogId;

    public function __construct(string $alarmId, CatalogId $catalogId)
    {
        $this->alarmId = $alarmId;
        $this->catalogId = $catalogId;
    }

    public function getAlarmId(): string
    {
        return $this->alarmId;
    }

    public function getCatalogId(): CatalogId
    {
        return $this->catalogId;
    }
}
