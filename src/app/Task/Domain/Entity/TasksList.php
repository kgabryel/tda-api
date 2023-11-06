<?php

namespace App\Task\Domain\Entity;

interface TasksList
{
    public function updateName(string $name): void;

    public function updateContent(?string $content): void;

    public function getIds(): array;

    public function delete(): void;

    public function disconnect(): void;

    public function getTasksInFuture(): TasksInFuture;
}
