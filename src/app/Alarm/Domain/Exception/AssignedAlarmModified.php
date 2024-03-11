<?php

namespace App\Alarm\Domain\Exception;

use Exception;
use Throwable;

class AssignedAlarmModified extends Exception
{
    public function __construct(
        string $message = 'Cannot update alarm connected with periodic alarm.',
        int $code = 0,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
