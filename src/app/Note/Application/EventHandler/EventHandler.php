<?php

namespace App\Note\Application\EventHandler;

use App\Note\Application\NoteManagerInterface;

abstract class EventHandler
{
    protected NoteManagerInterface $noteManager;

    public function __construct(NoteManagerInterface $noteManager)
    {
        $this->noteManager = $noteManager;
    }
}
