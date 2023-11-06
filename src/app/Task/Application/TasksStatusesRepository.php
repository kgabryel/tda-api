<?php

namespace App\Task\Application;

interface TasksStatusesRepository
{
    public function findAll(): array;
}
