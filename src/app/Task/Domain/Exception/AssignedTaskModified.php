<?php

namespace App\Task\Domain\Exception;

use Exception;
use Throwable;

class AssignedTaskModified extends Exception
{
    public function __construct(
        string $message = 'Cannot update task connected with periodic task.',
        int $code = 0,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
