<?php

namespace App\File\Domain\Event;

use App\Core\Cqrs\Event;
use App\File\Domain\Entity\File;

/**
 * Plik zostala usuniety, nalezy zmodyfikowac powiazane zadania i katalogi
 */
class Removed implements Event
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
