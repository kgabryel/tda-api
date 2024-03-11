<?php

namespace App\File\Domain\Event;

use App\Core\Cqrs\Event;
use App\File\Domain\Entity\File;

/**
 * Plik zostal zmodyfikowany, trzeba zaktualizowac dane w bazie danych
 */
class Updated implements Event
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
