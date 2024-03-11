<?php

namespace App\Alarm\Domain\Entity;

interface AlarmsInFuture
{
    public function getIds(): array;

    public function get(): array;

    public function delete(): void;
}
