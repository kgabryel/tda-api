<?php

namespace App\Alarm\Application\ViewModel;

use App\Shared\Application\Dto\CatalogsIdsList;
use DateTimeImmutable;
use JsonSerializable;

class SingleAlarm implements JsonSerializable
{
    private string $id;
    private string $name;
    private ?string $content;
    private bool $isChecked;
    private ?string $groupId;
    private ?string $connectedTaskId;
    private CatalogsIdsList $catalogsList;
    private NotificationsList $notificationsList;
    private ?DateTimeImmutable $date;
    private DateTimeImmutable $createdAt;

    public function __construct(
        string $id,
        string $name,
        ?string $content,
        bool $isChecked,
        ?string $groupId,
        ?string $connectedTaskId,
        CatalogsIdsList $catalogsList,
        NotificationsList $notificationsList,
        ?DateTimeImmutable $date,
        DateTimeImmutable $createdAt
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->content = $content;
        $this->isChecked = $isChecked;
        $this->groupId = $groupId;
        $this->connectedTaskId = $connectedTaskId;
        $this->catalogsList = $catalogsList;
        $this->notificationsList = $notificationsList;
        $this->date = $date;
        $this->createdAt = $createdAt;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'content' => $this->content,
            'checked' => $this->isChecked,
            'notifications' => $this->notificationsList->getNotifications(),
            'periodic' => false,
            'group' => $this->groupId,
            'date' => $this->date?->format('Y-m-d'),
            'task' => $this->connectedTaskId,
            'catalogs' => $this->catalogsList->getIds(),
            'order' => $this->createdAt->getTimestamp()
        ];
    }
}
