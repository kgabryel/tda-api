<?php

namespace App\Task\Domain\Entity;

interface SubtasksList
{
    public function getIds(): array;

    public function get(): array;

    public function delete(): void;

    public function disconnect(): void;

    public function setStatus(TaskStatus $status): void;

    public function isEmpty(): bool;
}
