<?php

namespace App\Shared\Application;

interface TasksTypesRepository
{
    public function getTasksTypes(string ...$ids): TasksTypesCollection;
}
