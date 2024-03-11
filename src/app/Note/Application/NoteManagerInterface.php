<?php

namespace App\Note\Application;

use App\Note\Domain\Entity\Note;
use App\Shared\Application\Dto\CatalogsIdsList;
use App\Shared\Application\Dto\SingleTasksIdsList;
use App\Shared\Application\Dto\TasksGroupsIdsList;
use App\Shared\Domain\Entity\NoteId;
use App\Shared\Domain\Entity\UserId;
use App\Shared\Domain\ValueObject\Hex;

interface NoteManagerInterface
{
    public function update(Note $note): void;

    public function delete(NoteId $noteId): void;

    public function create(
        string $name,
        string $content,
        bool $assignedToDashboard,
        Hex $textColor,
        Hex $backgroundColor,
        CatalogsIdsList $catalogsList,
        SingleTasksIdsList $tasksList,
        TasksGroupsIdsList $tasksGroupsList,
        UserId $userId
    ): Note;
}
