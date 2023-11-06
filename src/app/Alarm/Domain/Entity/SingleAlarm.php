<?php

namespace App\Alarm\Domain\Entity;

use App\Alarm\Domain\Exception\AssignedAlarmModified;
use App\Shared\Domain\Entity\AlarmId;
use App\Shared\Domain\Entity\AlarmsGroupId;
use App\Shared\Domain\Entity\CatalogId;
use App\Shared\Domain\Entity\TaskId;
use App\Shared\Domain\Entity\UserId;
use App\Shared\Domain\Exception\EntityDeletedException;
use App\Shared\Domain\List\CatalogsListInterface;
use DateTimeImmutable;

class SingleAlarm extends Alarm
{
    private AlarmId $alarmId;
    private ?AlarmsGroupId $alarmsGroupId;
    private ?TaskId $taskId;
    private bool $checked;
    private NotificationsList $notifications;
    private ?DateTimeImmutable $date;

    public function __construct(
        AlarmId $alarmId,
        ?AlarmsGroupId $alarmsGroupId,
        ?TaskId $taskId,
        bool $checked,
        NotificationsList $notifications,
        ?DateTimeImmutable $date,
        UserId $userId,
        string $name,
        ?string $content,
        CatalogsListInterface $catalogsList
    ) {
        parent::__construct($userId, $name, $content, $catalogsList);
        $this->alarmId = $alarmId;
        $this->alarmsGroupId = $alarmsGroupId;
        $this->taskId = $taskId;
        $this->checked = $checked;
        $this->notifications = $notifications;
        $this->date = $date;
    }

    public function removeCatalog(CatalogId $id): bool
    {
        if ($this->alarmsGroupId !== null) {
            throw new AssignedAlarmModified();
        }

        return parent::removeCatalog($id);
    }

    public function updateName(string $name): bool
    {
        if ($this->alarmsGroupId !== null) {
            throw new AssignedAlarmModified();
        }

        return parent::updateName($name);
    }

    public function uncheck(): bool
    {
        if ($this->deleted) {
            throw new EntityDeletedException('Cannot change "name" value, entity was deleted.');
        }
        if (!$this->checked) {
            return false;
        }
        $this->checked = false;

        return true;
    }

    public function check(): bool
    {
        if ($this->deleted) {
            throw new EntityDeletedException('Cannot change "name" value, entity was deleted.');
        }
        if ($this->checked) {
            return false;
        }
        $this->checked = true;
        $this->notifications->check();

        return true;
    }

    public function updateContent(?string $content): bool
    {
        if ($this->alarmsGroupId !== null) {
            throw new AssignedAlarmModified();
        }

        return parent::updateContent($content);
    }

    public function hasTask(): bool
    {
        return $this->taskId !== null;
    }

    public function addCatalog(CatalogId $id): bool
    {
        if ($this->alarmsGroupId !== null) {
            throw new AssignedAlarmModified();
        }

        return parent::addCatalog($id);
    }

    public function updateTask(?TaskId $taskId): bool
    {
        if ($this->alarmsGroupId !== null) {
            throw new AssignedAlarmModified();
        }
        if ($this->taskId?->getValue() === $taskId?->getValue()) {
            return false;
        }
        $this->taskId = $taskId;

        return true;
    }

    public function hasGroup(): bool
    {
        return $this->alarmsGroupId !== null;
    }

    public function getAlarmsGroupId(): ?AlarmsGroupId
    {
        return $this->alarmsGroupId;
    }

    public function getTaskId(): ?TaskId
    {
        return $this->taskId;
    }

    public function setTaskId(?TaskId $taskId): bool
    {
        if ($this->deleted) {
            throw new EntityDeletedException('Cannot change "name" value, entity was deleted.');
        }
        if ($taskId?->getValue() === $this->taskId?->getValue()) {
            return false;
        }
        if ($this->alarmsGroupId !== null && $taskId !== null) {
            throw new AssignedAlarmModified();
        }
        $this->taskId = $taskId;

        return true;
    }

    public function getAlarmId(): AlarmId
    {
        return $this->alarmId;
    }

    public function getNotifications(): array
    {
        return $this->notifications->get();
    }

    public function getNotification(NotificationId $notificationId): Notification
    {
        return $this->notifications->find($notificationId);
    }

    public function deleteNotification(NotificationId $id): void
    {
        if ($this->alarmsGroupId !== null) {
            throw new AssignedAlarmModified();
        }

        $this->notifications->delete($id);
    }

    public function addNotification(Notification $notification, bool $fromGroup = false): bool
    {
        if ($this->deleted) {
            throw new EntityDeletedException('Cannot add notification, entity was deleted.');
        }
        if ($this->alarmsGroupId !== null && !$fromGroup) {
            throw new AssignedAlarmModified();
        }
        if (!$this->checked) {
            return false;
        }
        if ($notification->isChecked()) {
            return false;
        }
        $this->checked = false;

        return true;
    }

    public function isChecked(): bool
    {
        return $this->checked;
    }

    public function getDate(): ?DateTimeImmutable
    {
        return $this->date;
    }
}
