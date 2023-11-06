<?php

namespace App\Alarm\Application\ViewModel;

class NotificationsTypesList
{
    private array $ids;

    public function __construct(string ...$ids)
    {
        $this->ids = $ids;
    }

    public function getIds(): array
    {
        return $this->ids;
    }
}
