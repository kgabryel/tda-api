<?php

namespace App\File\Domain\Event;

use App\Core\Cqrs\AsyncEvent;
use App\File\Domain\Entity\File;

/**
 * Plik zostal dodany, nalezy zmodyfikowac powiazane zadania i katalogi
 */
class Created implements AsyncEvent
{
    private File $file;

    public function __construct(File $file)
    {
        $this->file = $file;
    }

    public function getFile(): File
    {
        return $this->file;
    }
}
