<?php

namespace App\Catalog\Application\Command\RemoveAlarm;

use App\Catalog\Application\Command\ModifyCatalogCommand;
use App\Core\Cqrs\CommandHandler;
use App\Shared\Domain\Entity\CatalogId;

/**
 * Odpina zadanie od katalogu
 */
#[CommandHandler(RemoveAlarmHandler::class)]
class RemoveAlarm extends ModifyCatalogCommand
{
    private string $alarmId;

    public function __construct(CatalogId $id, string $alarmId)
    {
        parent::__construct($id);
        $this->alarmId = $alarmId;
    }

    public function getAlarmId(): string
    {
        return $this->alarmId;
    }
}
