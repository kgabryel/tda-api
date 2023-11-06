<?php

namespace App\Alarm\Application\Dto;

use App\Alarm\Domain\Entity\NotificationGroup;

class NotificationsGroupsList
{
    private array $groups;

    public function __construct(NotificationGroup ...$groups)
    {
        $this->groups = $groups;
    }

    public function add(NotificationGroup $group): void
    {
        $this->groups[] = $group;
    }

    public function get(): array
    {
        return $this->groups;
    }
}
