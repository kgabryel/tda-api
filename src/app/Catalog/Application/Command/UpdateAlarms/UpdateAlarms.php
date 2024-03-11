<?php

namespace App\Catalog\Application\Command\UpdateAlarms;

use App\Catalog\Application\Command\ModifyCatalogCommand;
use App\Core\Cqrs\CommandHandler;
use App\Shared\Domain\Entity\CatalogId;

/**
 * Aktualizuje liste alarmow przypisanych do katalogu
 */
#[CommandHandler(UpdateAlarmsHandler::class)]
class UpdateAlarms extends ModifyCatalogCommand
{
    private array $alarms;

    public function __construct(CatalogId $id, string ...$alarms)
    {
        parent::__construct($id);
        $this->alarms = $alarms;
    }

    public function getAlarms(): array
    {
        return $this->alarms;
    }
}
