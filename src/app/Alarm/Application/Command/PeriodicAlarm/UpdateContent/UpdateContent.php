<?php

namespace App\Alarm\Application\Command\PeriodicAlarm\UpdateContent;

use App\Alarm\Application\Command\PeriodicAlarm\ModifyPeriodicAlarmCommand;
use App\Core\Cqrs\CommandHandler;
use App\Shared\Domain\Entity\AlarmsGroupId;

/**
 * Aktualizuje tresc alarmu okresowego
 */
#[CommandHandler(UpdateContentHandler::class)]
class UpdateContent extends ModifyPeriodicAlarmCommand
{
    private ?string $content;

    public function __construct(AlarmsGroupId $id, string $content)
    {
        parent::__construct($id);
        $this->content = $content;
    }

    public function getContent(): string
    {
        return $this->content;
    }
}
