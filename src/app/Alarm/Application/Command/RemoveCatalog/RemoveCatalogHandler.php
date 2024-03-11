<?php

namespace App\Alarm\Application\Command\RemoveCatalog;

use App\Alarm\Application\Command\ModifyAlarmHandler;
use App\Alarm\Domain\Event\CatalogsAssigmentChanged;

class RemoveCatalogHandler extends ModifyAlarmHandler
{
    public function handle(RemoveCatalog $command): void
    {
        $alarm = $this->getAlarm($command->getAlarmId());
        if ($alarm->removeCatalog($command->getCatalogId())) {
            $this->eventEmitter->emit(new CatalogsAssigmentChanged($alarm->getUserId(), $command->getCatalogId()));
        }
    }
}
