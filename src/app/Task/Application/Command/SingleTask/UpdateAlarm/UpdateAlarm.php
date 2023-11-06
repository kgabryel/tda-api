<?php

namespace App\Task\Application\Command\SingleTask\UpdateAlarm;

use App\Core\Cqrs\CommandHandler;
use App\Shared\Domain\Entity\AlarmId;
use App\Shared\Domain\Entity\TaskId;
use App\Task\Application\Command\SingleTask\ModifySingleTaskCommand;

/**
 * Aktualizuje alarm przypisany do zadania pojedynczego. Przypina nowy lub odpina aktualny
 */
#[CommandHandler(UpdateAlarmHandler::class)]
class UpdateAlarm extends ModifySingleTaskCommand
{
    private ?AlarmId $alarmId;

    public function __construct(TaskId $id, ?AlarmId $alarmId)
    {
        parent::__construct($id);
        $this->alarmId = $alarmId;
    }

    public function getAlarmId(): ?AlarmId
    {
        return $this->alarmId;
    }
}
