<?php

namespace App\Task\Application\Command\PeriodicTask\Create;

use App\Alarm\Application\Command\PeriodicAlarm\Create\Notifications;
use App\Shared\Application\Dto\CatalogsIdsList;
use App\Shared\Domain\Entity\AlarmsGroupId;

//powinno korzystac z portu z modulu alarmow
class AlarmDto
{
    private AlarmsGroupId $alarmId;
    private string $name;
    private ?string $content;
    private CatalogsIdsList $catalogsList;
    private Notifications $notifications;

    public function __construct(
        AlarmsGroupId $alarmId,
        string $name,
        ?string $content,
        CatalogsIdsList $catalogsList,
        Notifications $notifications
    ) {
        $this->alarmId = $alarmId;
        $this->name = $name;
        $this->content = $content;
        $this->catalogsList = $catalogsList;
        $this->notifications = $notifications;
    }

    public function getAlarmId(): AlarmsGroupId
    {
        return $this->alarmId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function getCatalogsList(): CatalogsIdsList
    {
        return $this->catalogsList;
    }

    public function getNotifications(): Notifications
    {
        return $this->notifications;
    }
}
