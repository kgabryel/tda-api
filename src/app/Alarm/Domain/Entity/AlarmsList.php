<?php

namespace App\Alarm\Domain\Entity;

interface AlarmsList
{
    public function updateName(string $name): void;

    public function updateContent(?string $content): void;

    public function getIds(): array;

    public function getConnectedTasksIds(): array;

    public function delete(): void;

    public function disconnect(): void;

    public function getAlarmsInFuture(): AlarmsInFuture;
}
