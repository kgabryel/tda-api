<?php

namespace App\Shared\Application\Dto;

use DateTimeImmutable;

class DatesList
{
    private array $dates;

    public function __construct()
    {
        $this->dates = [];
    }

    public function addDate(DateTimeImmutable $date): void
    {
        $this->dates[] = $date;
    }

    public function get(): array
    {
        return $this->dates;
    }
}
