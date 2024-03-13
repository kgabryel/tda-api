<?php

namespace App\Alarm\Application\Command\SingleAlarm\UpdateContent;

use App\Alarm\Application\Command\SingleAlarm\ModifySingleAlarmCommand;
use App\Core\Cqrs\CommandHandler;
use App\Shared\Domain\Entity\AlarmId;

/**
 * Aktualizuje tresc alarmu pojedynczego
 */
#[CommandHandler(UpdateContentHandler::class)]
class UpdateContent extends ModifySingleAlarmCommand
{
    private ?string $content;

    public function __construct(AlarmId $id, string $content)
    {
        parent::__construct($id);
        $this->content = $content;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }
}
