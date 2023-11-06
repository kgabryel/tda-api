<?php

namespace App\Alarm\Application\Command\RemoveCatalog;

use App\Core\Cqrs\CommandHandler;
use App\Shared\Application\Command\Command;
use App\Shared\Domain\Entity\CatalogId;

/**
 * Odpina katalog od alarmu
 */
#[CommandHandler(RemoveCatalogHandler::class)]
class RemoveCatalog implements Command
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
