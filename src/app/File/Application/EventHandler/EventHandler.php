<?php

namespace App\File\Application\EventHandler;

use App\File\Application\FileManagerInterface;

abstract class EventHandler
{
    protected FileManagerInterface $fileManager;

    public function __construct(FileManagerInterface $fileManager)
    {
        $this->fileManager = $fileManager;
    }
}
