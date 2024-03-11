<?php

namespace App\Alarm\Application\Command\AddCatalog;

use App\Alarm\Application\Command\ModifyAlarmHandler;
use App\Alarm\Domain\Event\CatalogsAssigmentChanged;

class AddCatalogHandler extends ModifyAlarmHandler
{
    public function handle(AddCatalog $command): void
    {
        $alarm = $this->getAlarm($command->getAlarmId());
        if ($alarm->addCatalog($command->getCatalogId())) {
            $this->eventEmitter->emit(new CatalogsAssigmentChanged($alarm->getUserId(), $command->getCatalogId()));
        }
    }
}
