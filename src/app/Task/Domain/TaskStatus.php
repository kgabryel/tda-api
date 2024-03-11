<?php

namespace App\Task\Domain;

enum TaskStatus: string
{
    case TO_DO = 'to-do';
    case DONE = 'done';
    case UNDONE = 'undone';
    case REJECTED = 'rejected';
    case IN_PROGRESS = 'in-progress';
    case BLOCKED = 'blocked';
}
