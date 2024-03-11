<?php

namespace App\Task\Domain\Entity;

interface TasksInFuture
{
    public function get(): array;

    public function getIds(): array;

    public function reject(TaskStatus $rejectStatus, TaskStatus $doneStatus, TaskStatus $undoneStatus): void;

    public function delete(): void;

    public function activate(TaskStatus $taskStatus): void;
}
