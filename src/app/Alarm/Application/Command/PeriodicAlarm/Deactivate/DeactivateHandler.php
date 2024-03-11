<?php

namespace App\Alarm\Application\Command\PeriodicAlarm\Deactivate;

use App\Alarm\Application\Command\ModifyAlarmHandler;
use App\Alarm\Application\Command\SingleAlarm\Check\Check;
use App\Alarm\Domain\Entity\PeriodicAlarm;
use App\Alarm\Domain\Event\PeriodicAlarm\Updated;
use App\Alarm\Domain\Event\SingleAlarm\AlarmsModified;

class DeactivateHandler extends ModifyAlarmHandler
{
    private PeriodicAlarm $alarm;

    public function handle(Deactivate $command): bool
    {
        $this->alarm = $this->getPeriodicAlarm($command->getAlarmId());
        if (!$this->alarm->deactivate()) {
            return false;
        }
        $alarms = $this->alarm->getAlarmsInFuture();
        $ids = $alarms->getIds();
        match ($command->getAction()) {
            DeactivateAction::DEACTIVATE => $this->deactivate(),
            DeactivateAction::DELETE => $this->alarm->getAlarmsInFuture()->delete(),
            default => null
        };

        $this->eventEmitter->emit(new Updated($this->alarm));
        $this->eventEmitter->emit(new AlarmsModified($this->alarm->getUserId(), ...$ids));

        return true;
    }

    private function deactivate(): void
    {
        $alarms = $this->alarm->getAlarmsInFuture();
        $ids = $alarms->getIds();
        foreach ($ids as $id) {
            $this->commandBus->handle(new Check($id));
        }
    }
}
